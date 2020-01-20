<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/10/18
 * Time: 10:55
 */

namespace App\WebSocket\Controller;


use App\Storage\OnlineUser;

class Token extends Base
{
    public function index(){
        $fd = $this->caller()->getClient()->getFd();
        $token = OnlineUser::getInstance()->get($fd, 'token');
        if($token) {
            $message = new \App\WebSocket\Action\Token([
                'token' => $token
            ]);
            $this->response()->setMessage($message->__toString());
        }
    }
}