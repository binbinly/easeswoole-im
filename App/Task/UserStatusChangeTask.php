<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/10/23
 * Time: 17:06
 */

namespace App\Task;


use App\Storage\AdminUser;
use App\Storage\message\OnlineStatusBean;
use App\Storage\message\SystemMessageBean;
use App\Storage\OnlineUser;
use App\WebSocket\Action\OnlineStatus;
use EasySwoole\EasySwoole\ServerManager;
use EasySwoole\EasySwoole\Trigger;
use EasySwoole\Task\AbstractInterface\TaskInterface;

class UserStatusChangeTask implements TaskInterface
{
    /** @var SystemMessageBean  */
    protected $message;

    public function __construct(OnlineStatusBean $message)
    {
        $this->message = $message;
    }

    function run(int $taskId, int $workerIndex)
    {
        // TODO: Implement run() method.

        /** @var \swoole_websocket_server $server */
        $server = ServerManager::getInstance()->getSwooleServer();

        $message = new OnlineStatus($this->message);
        $userList = AdminUser::get(1);

        foreach($userList as $user_id) {
            $fd = OnlineUser::getFd($user_id);
            if($server->isEstablished($fd)) {
                $server->push($fd, $message);
            }
        }
    }

    function onException(\Throwable $throwable, int $taskId, int $workerIndex)
    {
        // TODO: Implement onException() method.
        Trigger::getInstance()->error('[UserStatusChange]:'.$throwable->getMessage());
    }
}