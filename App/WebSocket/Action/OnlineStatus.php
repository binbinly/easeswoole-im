<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/10/23
 * Time: 17:15
 */

namespace App\WebSocket\Action;


use App\Storage\message\OnlineStatusBean;
use App\WebSocket\WebSocketAction;

class OnlineStatus extends ActionPayload
{
    const STATUS_ONLINE = 'online';
    const STATUS_OFFLINE = 'offline';

    protected $cmd = WebSocketAction::USER_STATUS;

    public function __construct(OnlineStatusBean $bean, $autoCreateProperty = false)
    {
        $this->setData($bean->toArray());
        parent::__construct($this->getData(), $autoCreateProperty);
    }
}