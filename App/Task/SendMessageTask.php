<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/10/15
 * Time: 11:12
 */

namespace App\Task;


use App\Common\MessageType;
use App\Model\MessageModel;
use App\Storage\AdminUser;
use App\Storage\message\UserChatMessageBean;
use App\Storage\OnlineUser;
use App\WebSocket\Action\MessageText;
use EasySwoole\EasySwoole\ServerManager;
use EasySwoole\EasySwoole\Trigger;
use EasySwoole\MysqliPool\Mysql;
use EasySwoole\Task\AbstractInterface\TaskInterface;
use Throwable;

class SendMessageTask implements TaskInterface
{

    /** @var UserChatMessageBean  */
    protected $message;

    public function __construct(UserChatMessageBean $message)
    {
        $this->message = $message;
    }

    function run(int $taskId, int $workerIndex)
    {
        // TODO: Implement run() method.

        /** @var \swoole_websocket_server $server */
        $server = ServerManager::getInstance()->getSwooleServer();

        $message = new MessageText($this->message);
        switch($this->message->getType()) {
            case MessageType::MESSAGE_KEFU:
            case MessageType::MESSAGE_FRIEND:
                $fd = OnlineUser::getFd($this->message->getSid());
                if($fd && $server->isEstablished($fd)) {
                    $this->push($server, $fd, $message);
                }
                break;
            case MessageType::MESSAGE_GROUP:
                $group_id = $this->message->getId();
                $userList = AdminUser::get($group_id);
                foreach($userList as $user_id) {
                    if($user_id == $this->message->getSid()) continue;
                    $fd = OnlineUser::getFd($user_id);
                    if($server->isEstablished($fd)) {
                        $this->push($server, $fd, $message);
                    }
                }
                break;
        }

        //入库
        $db = Mysql::defer('mysql');
        $model = new MessageModel($db);
        $model->add($this->message);
        return true;
    }

    /**
     * 消息重发
     * @param \swoole_websocket_server $server
     * @param $fd
     * @param $message
     * @param int $num 重发次数
     */
    private function push(\swoole_websocket_server $server, $fd, $message, $num = 3){
        if($num <= 0) return;
        $isSuccess = $server->push($fd, $message);
        if(!$isSuccess) {//发送失败，重新发送
            $num--;
            $this->push($server, $fd, $message, $num);
        }
    }

    function onException(Throwable $throwable, int $taskId, int $workerIndex)
    {
        // TODO: Implement onException() method.
        Trigger::getInstance()->error('[SendMessage]:'.$throwable->getMessage());
    }
}