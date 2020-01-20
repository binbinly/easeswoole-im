<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/10/18
 * Time: 11:29
 */

namespace App\WebSocket\Action;


use App\Storage\message\HttpTokenMessageBean;
use App\WebSocket\WebSocketAction;

class Token extends ActionPayload
{
    protected $cmd = WebSocketAction::HTTP_TOKEN_GET;

    public function __construct(array $data = null, bool $autoCreateProperty = false)
    {
        $userMessage = new HttpTokenMessageBean($data);
        $this->setData($userMessage->toArray());
        parent::__construct($this->getData(), $autoCreateProperty);
    }
}