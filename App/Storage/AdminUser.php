<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/10/15
 * Time: 10:50
 */

namespace App\Storage;


use App\Storage\Bean\AdminUserBean;
use App\Utility\Pool\RedisObject;
use App\Utility\Pool\RedisPool;

class AdminUser
{
    const REDIS_KEY_ADMIN_USER_PREFIX = 'im_admin_user:';

    /**
     * 添加一个客服
     * @param AdminUserBean $user
     * @throws \EasySwoole\Component\Pool\Exception\PoolEmpty
     * @throws \EasySwoole\Component\Pool\Exception\PoolException
     */
    public static function add(AdminUserBean $user){
        /** @var RedisObject $redis */
        $redis = RedisPool::defer();
        $redis->sAdd(self::REDIS_KEY_ADMIN_USER_PREFIX.$user->getGroupId(), $user->getUserId());
    }

    /**
     * 获取用户组用户
     * @param $group_id
     * @throws \EasySwoole\Component\Pool\Exception\PoolEmpty
     * @throws \EasySwoole\Component\Pool\Exception\PoolException
     * @return array
     */
    public static function get($group_id = 1) {
        /** @var RedisObject $redis */
        $redis = RedisPool::defer();
        $list = $redis->sMembers(self::REDIS_KEY_ADMIN_USER_PREFIX.$group_id);
        return $list ?: [];
    }

    /**
     * 删除一个客服
     * @param $user_id
     * @param $group_id
     * @throws \EasySwoole\Component\Pool\Exception\PoolEmpty
     * @throws \EasySwoole\Component\Pool\Exception\PoolException
     */
    public static function del(int $user_id, int $group_id = 1){
        /** @var RedisObject $redis */
        $redis = RedisPool::defer();
        $redis->sRem(self::REDIS_KEY_ADMIN_USER_PREFIX.$group_id, $user_id);
    }
}