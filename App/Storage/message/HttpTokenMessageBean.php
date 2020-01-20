<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/10/18
 * Time: 11:22
 */

namespace App\Storage\message;


use EasySwoole\Spl\SplBean;

/**
 * http请求token令牌
 * Class HttpTokenMessageBean
 * @package App\Storage\message
 */
class HttpTokenMessageBean extends SplBean
{
    protected $token;

    /**
     * @return mixed
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * @param mixed $token
     */
    public function setToken($token): void
    {
        $this->token = $token;
    }
}