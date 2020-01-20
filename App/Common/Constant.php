<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/10/15
 * Time: 15:30
 */

namespace App\Common;


class Constant
{
    const IM_TOKEN_PREFIX = 'im_token:';
    const REDIS_TOKEN_PREFIX = 'token:';

    const USER_TYPE_GUEST = 0;  //游客
    const USER_TYPE_KEFU = 1;   //客服
    const USER_TYPE_ADMIN = 2;  //官方用户
}