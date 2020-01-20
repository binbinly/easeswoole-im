<?php


namespace App\WebSocket\Controller;


class Index extends Base
{
    public function heartbeat()
    {
        $this->response()->setMessage('PONG');
    }

    public function found()
    {
        $this->response()->setMessage('not found');
    }
}