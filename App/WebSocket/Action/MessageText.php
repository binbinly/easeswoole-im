<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/10/15
 * Time: 14:39
 */

namespace App\WebSocket\Action;


use App\Storage\message\UserChatMessageBean;
use App\Common\Common;
use App\WebSocket\WebSocketAction;

class MessageText extends ActionPayload
{
    protected $cmd = WebSocketAction::MESSAGE_TEXT;

    public function __construct(UserChatMessageBean $bean, $autoCreateProperty = false)
    {
        $bean->setTimestamp(Common::micTime());

        $this->setData($bean->toArray());
        parent::__construct($this->getData(), $autoCreateProperty);
    }
}