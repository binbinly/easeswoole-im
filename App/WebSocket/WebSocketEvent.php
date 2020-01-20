<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/10/14
 * Time: 18:35
 */

namespace App\WebSocket;


use App\Storage\Bean\AdminUserBean;
use App\Storage\Bean\KeFuBean;
use App\Storage\KeFu;
use App\Storage\message\OnlineStatusBean;
use App\Storage\OnlineUser;
use App\Task\UserStatusChangeTask;
use App\Common\Constant;
use App\Storage\AdminUser;
use App\Storage\Bean\OnlineUserBean;
use App\Utility\Pool\RedisObject;
use App\Utility\Pool\RedisPool;
use App\Common\UserManage;
use App\WebSocket\Action\OnlineStatus;
use EasySwoole\EasySwoole\ServerManager;
use EasySwoole\EasySwoole\Task\TaskManager;
use Swoole\Http\Request;
use Swoole\Http\Response;
use Exception;

class WebSocketEvent
{
    /**
     * 握手事件
     *
     * @param Request  $request
     * @param Response $response
     * @return bool
     */
    public function onHandShake(Request $request, Response $response)
    {
        /** 此处自定义握手规则 返回 false 时中止握手 */
        if (!$this->customHandShake($request, $response)) {
            $response->end();
            return false;
        }

        /** 此处是  RFC规范中的WebSocket握手验证过程 必须执行 否则无法正确握手 */
        if ($this->secWebsocketAccept($request, $response)) {
            $response->end();
            return true;
        }

        $response->end();
        return false;
    }

    /**
     * 链接被关闭时
     * @param \Swoole\Server $server
     * @param int $fd
     * @param int $reactorId 当服务器主动关闭连接时，底层会设置此参数为-1
     * @throws Exception
     */
    static function onClose(\Swoole\Server $server, int $fd, int $reactorId)
    {
        $user = OnlineUser::getInstance()->get($fd);
        if($user && $reactorId >= 0) {
            OnlineUser::getInstance()->offline($fd);

            //群发上线通知
            $offlineStatus = new OnlineStatusBean();
            $offlineStatus->setId($user['user_id']);
            $offlineStatus->setStatus(OnlineStatus::STATUS_OFFLINE);
            TaskManager::getInstance()->sync(new UserStatusChangeTask($offlineStatus));
        }
    }

    /**
     * 自定义握手事件
     *
     * @param Request  $request
     * @param Response $response
     * @return bool
     */
    protected function customHandShake(Request $request, Response $response): bool
    {
        /**
         * 这里可以通过 http request 获取到相应的数据
         * 进行自定义验证后即可
         * (注) 浏览器中 JavaScript 并不支持自定义握手请求头 只能选择别的方式 如get参数
         */
        //$headers = $request->header;
        //$cookie = $request->cookie;
        $token = $request->get['token'] ?? '';
        if($token) {
            try {
                /** @var RedisObject $redis */
                $redis = RedisPool::defer();
                $exist = $redis->exists(Constant::IM_TOKEN_PREFIX . $request->get['token']);
                if ($exist) {
                    //token只能使用一次
                    $redis->del(Constant::IM_TOKEN_PREFIX . $request->get['token']);
                    $jwt = new JwtUtility();
                    $userData = $jwt->decode($request->get['token']);
                    if($userData) {
                        $userType = $userData['type'] ?? 0;
                        $fd = $request->fd;
                        if($oldFd = OnlineUser::getInstance()->getFd($userData['user_id'])) {
                            /** @var \swoole_websocket_server $server */
                            $server = ServerManager::getInstance()->getSwooleServer();
                            if($server->isEstablished($oldFd)) {
                                $server->disconnect($oldFd, 1000, '账号已在其他设备登录');
                                OnlineUser::getInstance()->del($userData['user_id']);
                            }
                        }

                        if($userType == Constant::USER_TYPE_KEFU) {//客服登录
                            $bean = new KeFuBean(UserManage::format($userData));
                            KeFu::add($bean);
                        }
                        if(in_array($userType, [Constant::USER_TYPE_ADMIN, Constant::USER_TYPE_KEFU])) {
                            $bean = new AdminUserBean(UserManage::format($userData));
                            AdminUser::add($bean);
                        }
                        $bean = new OnlineUserBean(UserManage::format($userData));
                        OnlineUser::getInstance()->add($fd, $bean);
                        //群发上线通知
                        $onlineStatus = new OnlineStatusBean();
                        $onlineStatus->setId($bean->getUserId());
                        $onlineStatus->setStatus(OnlineStatus::STATUS_ONLINE);
                        TaskManager::getInstance()->sync(new UserStatusChangeTask($onlineStatus));
                        return true;
                    }
                }else{
                    $userData = [
                        'user_id' => 100,
                        'username' => 'test'
                    ];
                    $bean = new KeFuBean(UserManage::format($userData));
                    KeFu::add($bean);
                    $bean = new OnlineUserBean(UserManage::format($userData));
                    OnlineUser::getInstance()->add($request->fd, $bean);
                    return true;
                }
            }catch (Exception $e) {
                return false;
            }
        }
        return false;
    }

    /**
     * RFC规范中的WebSocket握手验证过程
     * 以下内容必须强制使用
     *
     * @param Request  $request
     * @param Response $response
     * @return bool
     */
    protected final function secWebSocketAccept(Request $request, Response $response): bool
    {
        // ws rfc 规范中约定的验证过程
        if (!isset($request->header['sec-websocket-key'])) {
            // 需要 Sec-WebSocket-Key 如果没有拒绝握手
            var_dump('shake fai1 3');
            return false;
        }
        if (0 === preg_match('#^[+/0-9A-Za-z]{21}[AQgw]==$#', $request->header['sec-websocket-key'])
            || 16 !== strlen(base64_decode($request->header['sec-websocket-key']))
        ) {
            //不接受握手
            var_dump('shake fai1 4');
            return false;
        }

        $key = base64_encode(sha1($request->header['sec-websocket-key'] . '258EAFA5-E914-47DA-95CA-C5AB0DC85B11', true));
        $headers = array(
            'Upgrade'               => 'websocket',
            'Connection'            => 'Upgrade',
            'Sec-WebSocket-Accept'  => $key,
            'Sec-WebSocket-Version' => '13',
            'KeepAlive'             => 'off',
        );

        if (isset($request->header['sec-websocket-protocol'])) {
            $headers['Sec-WebSocket-Protocol'] = $request->header['sec-websocket-protocol'];
        }

        // 发送验证后的header
        foreach ($headers as $key => $val) {
            $response->header($key, $val);
        }

        // 接受握手 还需要101状态码以切换状态
        $response->status(101);
        return true;
    }
}