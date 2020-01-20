<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/10/16
 * Time: 11:11
 */

namespace App\Storage;


use App\Storage\Bean\KeFuBean;
use App\Utility\Pool\RedisObject;
use App\Utility\Pool\RedisPool;

class KeFu
{
    const REDIS_KEY_KEFU_USER = 'im_kefu_user';

    /**
     * 添加一个客服
     * @param KeFuBean $user
     * @throws \EasySwoole\Component\Pool\Exception\PoolEmpty
     * @throws \EasySwoole\Component\Pool\Exception\PoolException
     */
    public static function add(KeFuBean $user){
        /** @var RedisObject $redis */
        $redis = RedisPool::defer();
        $redis->hSet(self::REDIS_KEY_KEFU_USER, $user->getUserId(), $user->__toString());
    }

    /**
     * 删除一个客服
     * @param $user_id
     * @throws \EasySwoole\Component\Pool\Exception\PoolEmpty
     * @throws \EasySwoole\Component\Pool\Exception\PoolException
     */
    public static function del(int $user_id){
        /** @var RedisObject $redis */
        $redis = RedisPool::defer();
        $redis->hDel(self::REDIS_KEY_KEFU_USER, $user_id);
    }
}