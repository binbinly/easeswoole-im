<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/10/23
 * Time: 9:40
 */

namespace App\Model;


use App\Model\Bean\MessageBean;
use App\Storage\message\UserChatMessageBean;
use EasySwoole\EasySwoole\Trigger;

class MessageModel extends BaseModel
{
    protected $table = 'message';

    protected $primaryKey = 'id';

    /**
     * 添加一条记录
     * @param UserChatMessageBean $bean
     * @return bool
     * @throws \EasySwoole\Mysqli\Exceptions\ConnectFail
     * @throws \EasySwoole\Mysqli\Exceptions\PrepareQueryFail
     * @throws \Throwable
     */
    public function add(UserChatMessageBean $bean): bool
    {
        $modelBean = new MessageBean($bean->toArray());
        $modelBean->setId($bean->getCid());
        $modelBean->setToId($bean->getId());
        $modelBean->setCreatedAt(time());
        $modelBean->setUserId($bean->getFromid());
        $ret = $this->getDbConnection()->insert($this->table, $modelBean->toArray(null, $modelBean::FILTER_NOT_NULL));
        if($ret === false) {
            Trigger::getInstance()->error('[DB]message add err:'.$this->getDbConnection()->getLastError());
        }
        return $ret;
    }
}