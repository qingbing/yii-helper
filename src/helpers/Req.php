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
}