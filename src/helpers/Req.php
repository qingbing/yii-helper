<?php
/**
 * @link        http://www.phpcorner.net
 * @author      qingbing<780042175@qq.com>
 * @copyright   Chengdu Qb Technology Co., Ltd.
 */

namespace YiiHelper\helpers;


use Zf\Helper\DataStore;

/**
 * 请求助手
 *
 * Class Req
 * @package YiiHelper\helpers
 */
class Req
{
    /**
     * 获取客户端 IP
     * @return mixed|null
     */
    public static function getUserIp()
    {
        return DataStore::get(__CLASS__ . ":client-ip", function () {
            return \Yii::$app->getRequest()->getUserIP();
        });
    }

    /**
     * 获取直接访问的IP(refer-client-ip)
     *
     * @return mixed|null
     */
    public static function getAccessIp()
    {
        return DataStore::get(__CLASS__ . ":access-ip", function () {
            if (isset($_SERVER['REMOTE_ADDR'])) {
                return $_SERVER['REMOTE_ADDR'];
            }
            return \Yii::$app->getRequest()->getUserIP();
        });
    }
}