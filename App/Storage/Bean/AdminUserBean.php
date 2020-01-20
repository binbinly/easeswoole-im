<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/10/16
 * Time: 11:44
 */

namespace App\Storage\Bean;


/**
 * 后台用户
 * Class KeFuBean
 * @package App\Storage\Bean
 */
class AdminUserBean extends GuestUserBean
{
    protected $group_id;

    /**
     * @return mixed
     */
    public function getGroupId()
    {
        return $this->group_id;
    }

    /**
     * @param mixed $group_id
     */
    public function setGroupId($group_id): void
    {
        $this->group_id = $group_id;
    }
}