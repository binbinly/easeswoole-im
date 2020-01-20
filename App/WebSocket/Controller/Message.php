<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/10/15
 * Time: 16:41
 */

namespace App\WebSocket\Controller;


use App\Common\MessageType;
use App\Storage\message\UserChatMessageBean;
use App\Task\SendMessageTask;
use App\Common\UserManage;
use EasySwoole\EasySwoole\Task\TaskManager;

class Message extends Base
{
    public function text(){
        $params = $this->caller()->getArgs();
        $message = new UserChatMessageBean([
            'content' => $params['mine']['content'],
            'avatar' => UserManage::makeAvatar($params['mine']['avatar']),
            'type' => $params['to']['type'],
            'fromid' => $params['mine']['id'],
            'cid' => uniqid()
        ]);
        switch ($params['to']['type']) {
            case MessageType::MESSAGE_KEFU:
            case MessageType::MESSAGE_FRIEND:
                $message->setUsername($params['mine']['username']);
                $message->setId($params['mine']['id']);
                $message->setSid($params['to']['id']);
                break;
            case MessageType::MESSAGE_GROUP:
                $message->setUsername($params['to']['groupname']);
                $message->setId($params['to']['id']);
                $message->setSid($params['mine']['id']);
                break;
            default:
                break;
        }
        $task = new SendMessageTask($message);
        TaskManager::getInstance()->async($task);
    }
}