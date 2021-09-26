<?php
/**
 * @link        http://www.phpcorner.net
 * @author      qingbing<780042175@qq.com>
 * @copyright   Chengdu Qb Technology Co., Ltd.
 */

namespace YiiHelper\helpers;


use Yii;
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
            return Yii::$app->getRequest()->getUserIP();
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
            return Yii::$app->getRequest()->getUserIP();
        });
    }

    /**
     * 获取是否登录的 storeKey
     *
     * @return string
     */
    protected static function getIsGuestKey()
    {
        return __CLASS__ . ':isGuest';
    }

    /**
     * 获取当前是否登录
     *
     * @return mixed|null
     */
    public static function getIsGuest()
    {
        return DataStore::get(static::getIsGuestKey(), function () {
            return Yii::$app->getUser()->getIsGuest();
        });
    }

    /**
     * 设置当前是否登录
     *
     * @param bool $isGuest
     */
    public static function setIsGuest($isGuest)
    {
        DataStore::set(static::getIsGuestKey(), $isGuest);
    }

    /**
     * 获取登录用户id的 storeKey
     *
     * @return string
     */
    protected static function getStoreUidKey()
    {
        return __CLASS__ . ':loginUid';
    }

    /**
     * 获取当前登录用户id
     *
     * @return mixed|null
     */
    public static function getUid()
    {
        return DataStore::get(static::getStoreUidKey(), function () {
            return Yii::$app->getUser()->getIsGuest() ? 0 : Yii::$app->getUser()->getId();
        });
    }

    /**
     * 设置当前登录用户id
     *
     * @param mixed $uid
     */
    public static function setUid($uid)
    {
        DataStore::set(static::getStoreUidKey(), $uid);
    }
}