<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/10/15
 * Time: 10:44
 */

namespace App\Common;


/**
 * 用户管理器
 * Class AvatarManage
 * @package App\Utility
 */
class UserManage
{
    public static function makeAvatar($avatarId)
    {
        if(is_numeric($avatarId)) {
            return '/avatar/a' . $avatarId . '.jpg';
        }else{
            return $avatarId;
        }
    }

    public static function format($userData) {
        if(isset($userData['avatar']) && $userData['avatar']) {
            $userData['avatar'] = self::makeAvatar($userData['avatar']);
        }else{
            $userData['avatar'] = self::makeAvatar(0);
        }
        return $userData;
    }
}