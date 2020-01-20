<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/10/14
 * Time: 18:11
 */

namespace App\WebSocket;


use App\Utility\JsonHelper;
use EasySwoole\Socket\AbstractInterface\ParserInterface;
use EasySwoole\Socket\Client\WebSocket;
use EasySwoole\Socket\Bean\Caller;
use EasySwoole\Socket\Bean\Response;

/**
 * Class WebSocketParser
 *
 * 此类是自定义的 websocket 消息解析器
 * 此处使用的设计是使用 json string 作为消息格式
 * 当客户端消息到达服务端时，会调用 decode 方法进行消息解析
 * 会将 websocket 消息 转成具体的 Class -> Action 调用 并且将参数注入
 *
 * @package App\WebSocket
 */
class WebSocketParser implements ParserInterface
{
    /**
     * 解码上来的消息
     * @param string $raw 消息内容
     * @param WebSocket $client 当前的客户端
     * @return Caller|null
     */
    public function decode($raw, $client): ?Caller
    {
        $caller = new Caller;
        // 聊天消息 {"cmd":"cmd","data":{"content":"111"}},"ext":mixed
        if ($raw !== 'PING') {
            $payload = JsonHelper::decode($raw);
            if(isset($payload['cmd']) && in_array($payload['cmd'], array_keys(WebSocketAction::$actionList))) {
                $route = $payload['cmd'];
                $params = $payload['data'] ? $payload['data'] : [];
                isset($payload['ext']) && $params['ext'] = $payload['ext'];
                list($controller, $action) = explode('.', $route);
                $controllerClass = "\\App\\WebSocket\\Controller\\" . ucfirst($controller);
                if (!class_exists($controllerClass)) {
                    $controllerClass = "\\App\\WebSocket\\Controller\\Index";
                    $action = 'found';
                }
                $caller->setClient($caller);
                $caller->setControllerClass($controllerClass);
                $caller->setAction($action);
                $caller->setArgs($params);
            }else{
                $caller->setControllerClass("\\App\\WebSocket\\Controller\\Index");
                $caller->setAction('found');
            }
        } else {
            $caller->setControllerClass("\\App\\WebSocket\\Controller\\Index");
            $caller->setAction('heartbeat');
        }
        return $caller;
    }

    /**
     * 打包下发的消息
     * @param Response $response 控制器返回的响应
     * @param WebSocket $client 当前的客户端
     * @return string|null
     */
    public function encode(Response $response, $client): ?string
    {
        return $response->getMessage();
    }
}