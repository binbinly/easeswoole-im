<?php
/**
 * Created by PhpStorm.
 * User: yf
 * Date: 2018/5/28
 * Time: 下午6:33
 */

namespace EasySwoole\EasySwoole;


use App\Common\GRpc\GRpcCmd;
use App\Common\GRpc\GRPCTool;
use App\Process\CheckOnlineProcess;
use App\RpcService\AuthService;
use App\RpcService\MemberService;
use App\Storage\AdminUser;
use App\Storage\KeFu;
use App\Storage\OnlineUser;
use App\WebSocket\WebSocketEvent;
use App\WebSocket\WebSocketParser;
use EasySwoole\EasySwoole\Swoole\EventRegister;
use EasySwoole\EasySwoole\AbstractInterface\Event;
use EasySwoole\Rpc\NodeManager\RedisManager;
use EasySwoole\Rpc\Rpc;
use EasySwoole\Socket\Dispatcher;
use EasySwoole\Http\Request;
use EasySwoole\Http\Response;
use Swoole\WebSocket\Frame;
use Swoole\WebSocket\Server;
use Co;

class EasySwooleEvent implements Event
{

    public static function initialize()
    {
        // TODO: Implement initialize() method.
        date_default_timezone_set('Asia/Shanghai');

        $configData = Config::getInstance()->getConf('MYSQL');
        $config = new \EasySwoole\Mysqli\Config($configData);
        $poolConf = \EasySwoole\MysqliPool\Mysql::getInstance()->register('mysql', $config);
        $poolConf->setMaxObjectNum(20);

        //初始化redis数据
        Co\run(function () {
            $redisConf = Config::getInstance()->getConf('REDIS');
            $redis = new \Swoole\Coroutine\Redis();
            $redis->connect($redisConf['host'], $redisConf['port']);
            if($redisConf['auth']) {
                $redis->auth($redisConf['auth']);
            }
            $redis->del(OnlineUser::REDIS_ONLINE_USER_LIST);
            $redis->del(KeFu::REDIS_KEY_KEFU_USER);
            $keys = $redis->keys(AdminUser::REDIS_KEY_ADMIN_USER_PREFIX.'*');
            if($keys) {
                foreach($keys as $key) {
                    $redis->del($key);
                }
            }
            $keys = $redis->keys('__rpcNodes*');
            if($keys) {
                foreach($keys as $key) {
                    $redis->del($key);
                }
            }
        });
    }

    public static function mainServerCreate(EventRegister $register)
    {
        // TODO: Implement mainServerCreate() method.
        /**
         * **************** websocket控制器 **********************
         */
        OnlineUser::getInstance();

        \Sentry\init(Config::getInstance()->getConf('SENTRY'));

        // 创建一个 Dispatcher 配置
        $conf = new \EasySwoole\Socket\Config();
        // 设置 Dispatcher 为 WebSocket 模式
        $conf->setType(\EasySwoole\Socket\Config::WEB_SOCKET);
        // 设置解析器对象
        $conf->setParser(new WebSocketParser());
        // 创建 Dispatcher 对象 并注入 config 对象
        $dispatch = new Dispatcher($conf);
        // 给server 注册相关事件 在 WebSocket 模式下  on message 事件必须注册 并且交给 Dispatcher 对象处理
        $register->set(EventRegister::onMessage, function (Server $server, Frame $frame) use ($dispatch) {
            $dispatch->dispatch($server, $frame->data, $frame);
        });
        // 注册服务事件
        //自定义握手事件
        $webSocketEvent = new WebSocketEvent();
        $register->set(EventRegister::onHandShake, function (\Swoole\Http\Request $request, \Swoole\Http\Response $response) use ($webSocketEvent) {
            $webSocketEvent->onHandShake($request, $response);
        });
        //$register->add(EventRegister::onOpen, [WebSocketEvent::class, 'onOpen']);
        $register->add(EventRegister::onClose, [WebSocketEvent::class, 'onClose']);

        //注册RPC服务
        self::rpcRegister();
        //添加自定义进场
        self::addProcess();

        GRPCTool::getInstance()->setCmd(GRpcCmd::CHECK_LOGIN)->send(['sessionid'=>'123123']);
    }

    public static function onRequest(Request $request, Response $response): bool
    {
        // TODO: Implement onRequest() method.
        return true;
    }

    public static function afterRequest(Request $request, Response $response): void
    {
        // TODO: Implement afterAction() method.
    }

    protected static function rpcRegister(){
        $redisConfig = Config::getInstance()->getConf('REDIS');
        $rpcConfig = Config::getInstance()->getConf('RPC_SERVER');
        $config = new \EasySwoole\Rpc\Config();
        $config->setListenPort($rpcConfig['port']);
        $config->setServerIp($rpcConfig['ip']);//注册提供rpc服务的ip
        $config->setNodeManager(new RedisManager($redisConfig['host'], $redisConfig['port'], $redisConfig['auth']));//注册节点管理器
        $config->getBroadcastConfig()->setSecretKey($rpcConfig['secret_key']);        //设置秘钥

        $rpc = Rpc::getInstance($config);;
        $rpc->add(new AuthService());
        $rpc->add(new MemberService());

        $rpc->attachToServer(ServerManager::getInstance()->getSwooleServer());
    }

    protected static function addProcess(){
        $processConfig = new \EasySwoole\Component\Process\Config();
        $processConfig->setProcessName('checkOnline');
        ServerManager::getInstance()->getSwooleServer()->addProcess((new CheckOnlineProcess($processConfig))->getProcess());
    }
}