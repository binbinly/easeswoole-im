<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/10/17
 * Time: 10:30
 */

namespace App\Storage;


use App\Storage\Bean\OnlineUserBean;
use App\Utility\Pool\RedisObject;
use App\Utility\Pool\RedisPool;
use EasySwoole\Component\Singleton;
use EasySwoole\Component\TableManager;
use Swoole\Table;
use Swoole\WebSocket\Server;

/**
 * 所有在线用户
 * Class OnlineUser
 * @package App\Storage
 */
class OnlineUser
{
    const TABLE_ONLINE_USER = 'online_user';
    const REDIS_ONLINE_USER_LIST = 'im_online_user';

    use Singleton;

    protected $table = null;

    private function __construct()
    {
        TableManager::getInstance()->add(self::TABLE_ONLINE_USER, [
            'user_id' => ['type' => Table::TYPE_INT, 'size' => 4],
            'avatar' => ['type' => Table::TYPE_STRING, 'size' => 80],
            'username' => ['type' => Table::TYPE_STRING, 'size' => 60]
        ]);

        $this->table = TableManager::getInstance()->get(self::TABLE_ONLINE_USER);
    }

    /**
     * 添加一个用户
     * @param $fd
     * @param OnlineUserBean $userBean
     * @throws \EasySwoole\Component\Pool\Exception\PoolEmpty
     * @throws \EasySwoole\Component\Pool\Exception\PoolException
     */
    public function add($fd, OnlineUserBean $userBean){
        $this->table->set($fd, $userBean->toArray());

        /** @var RedisObject $redis */
        $redis = RedisPool::defer();
        $redis->hSet(self::REDIS_ONLINE_USER_LIST, $userBean->getUserId(), $fd);
    }

    /**
     * 获取用户对应的fd
     * @param $user_id
     * @return bool|string
     * @throws \EasySwoole\Component\Pool\Exception\PoolEmpty
     * @throws \EasySwoole\Component\Pool\Exception\PoolException
     */
    public static function getFd($user_id) {
        /** @var RedisObject $redis */
        $redis = RedisPool::defer();
        if($redis->hExists(self::REDIS_ONLINE_USER_LIST, $user_id)) {
            return $redis->hGet(self::REDIS_ONLINE_USER_LIST, $user_id);
        }
        return false;
    }

    /**
     * @param $fd
     * @param null $field
     * @return array|bool|string
     */
    public function get($fd, $field = null){
        if($this->table->exist($fd)) {
            return $this->table->get($fd, $field);
        }
        return false;
    }

    /**
     * 删除一个用户
     * @param $user_id
     * @throws \EasySwoole\Component\Pool\Exception\PoolEmpty
     * @throws \EasySwoole\Component\Pool\Exception\PoolException
     */
    public function del($user_id){
        /** @var RedisObject $redis */
        $redis = RedisPool::defer();
        if($redis->hExists(self::REDIS_ONLINE_USER_LIST, $user_id)) {
            $this->table->del($redis->hGet(self::REDIS_ONLINE_USER_LIST, $user_id));
            $redis->hDel(self::REDIS_ONLINE_USER_LIST, $user_id);
        }
    }

    /**
     * 定时检测在线情况
     * @param Server $server
     * @throws \EasySwoole\Component\Pool\Exception\PoolEmpty
     * @throws \EasySwoole\Component\Pool\Exception\PoolException
     */
    public function checkOnline(Server $server){
        if($this->table->count() > 0) {
            foreach ($this->table as $fd => $user) {
                if (!$server->isEstablished($fd)) {
                    $this->table->del($fd);
                    $this->offline($user);
                }
            }
        }
    }

    /**
     * 玩家下线清除记录
     * @param $user
     * @throws \EasySwoole\Component\Pool\Exception\PoolEmpty
     * @throws \EasySwoole\Component\Pool\Exception\PoolException
     */
    public function offline($user){
        if(!$user['user_id']) return;
        $this->del($user['user_id']);
        KeFu::del($user['user_id']);
        AdminUser::del($user['user_id']);
    }
}