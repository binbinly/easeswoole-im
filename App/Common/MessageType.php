<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/10/15
 * Time: 14:36
 */

namespace App\Common;


/**
 * 消息类型
 * Class MessageType
 * @package App\Common
 */
class MessageType
{
    //广播消息
    const MESSAGE_BROADCAST = 'broadcast';
    //好友消息
    const MESSAGE_FRIEND = 'friend';
    //房间消息
    const MESSAGE_GROUP = 'group';
    //客服消息
    const MESSAGE_KEFU = 'kefu';
}