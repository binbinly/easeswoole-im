<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/10/15
 * Time: 14:34
 */

namespace App\Common;


/**
 * 系统工具类
 * Class SysTool
 * @package App\Utility
 */
class Common
{
    /**
     * 毫秒时间戳
     * @return float
     */
    public static function micTime() {
        list($msec, $sec) = explode(' ', microtime());
        return (float)sprintf('%.0f', (floatval($msec) + floatval($sec)) * 1000);
    }
}