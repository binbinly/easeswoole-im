<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/10/15
 * Time: 11:45
 */

namespace App\WebSocket\Action;


use EasySwoole\Spl\SplBean;

class ActionPayload extends SplBean
{
    protected $cmd;

    protected $data;

    protected $ext = [];

    /**
     * @return mixed
     */
    public function getCmd()
    {
        return $this->cmd;
    }

    /**
     * @param mixed $cmd
     */
    public function setCmd($cmd): void
    {
        $this->cmd = $cmd;
    }

    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param mixed $data
     */
    public function setData($data): void
    {
        $this->data = $data;
    }

    /**
     * @return mixed
     */
    public function getExt()
    {
        return $this->ext;
    }

    /**
     * @param mixed $ext
     */
    public function setExt($ext): void
    {
        $this->ext = $ext;
    }
}