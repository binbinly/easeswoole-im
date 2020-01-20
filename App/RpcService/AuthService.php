<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/10/21
 * Time: 15:47
 */

namespace App\RpcService;

use App\Utility\JsonHelper;
use App\Utility\Pool\RedisObject;
use App\Utility\Pool\RedisPool;
use App\Common\Constant;

class AuthService extends BaseService
{
    /**
     * 用户登录
     * @throws \EasySwoole\Component\Pool\Exception\PoolEmpty
     * @throws \EasySwoole\Component\Pool\Exception\PoolException
     */
    public function login(){
        $token = $this->params['token'];
        if(true) {
            $user['id'] = 100;
            $user['username'] = 'test';
            /** @var RedisObject $redis */
            $redis = RedisPool::defer();
            $redis->setex(Constant::REDIS_TOKEN_PREFIX.$token, 7200, JsonHelper::encode($user));
            $this->response()->setResult($user);
        }
    }

    /**
     * 用户登出
     * @throws \EasySwoole\Component\Pool\Exception\PoolEmpty
     * @throws \EasySwoole\Component\Pool\Exception\PoolException
     */
    public function logout(){
        $token = $this->params['token'];
        if(!$token) return $this->error('token not found');
        /** @var RedisObject $redis */
        $redis = RedisPool::defer();
        $redis->del(Constant::REDIS_TOKEN_PREFIX.$token);
        return $this->success();
    }
}