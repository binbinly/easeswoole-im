<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/10/15
 * Time: 11:24
 */

namespace App\WebSocket;


class WebSocketAction
{
    const SYSTEM_MESSAGE = 'system.message';    //系统消息

    const FRIEND_MESSAGE = 'friend.message';    //聊天消息

    const MESSAGE_TEXT = 'message.text';    //文本消息

    const USER_STATUS = 'user.status';       // 用户状态

    const USER_IN_ROOM = 'room.in';      // 进入房间
    const USER_OUT_ROOM = 'room.out';     // 离开房间

    const HTTP_TOKEN_GET = 'token.index';   //客户端获取token

    public static $actionList = [
        self::MESSAGE_TEXT => '文本消息',
        self::USER_STATUS => '上线下线'
    ];
}