<?php
/**
 * @link        http://www.phpcorner.net
 * @author      qingbing<780042175@qq.com>
 * @copyright   Chengdu Qb Technology Co., Ltd.
 */

namespace YiiHelper\helpers;


use Yii;
use yii\base\InvalidConfigException;
use YiiHelper\models\operateLog\OperateLog;
use YiiHelper\models\permission\PermissionApi;
use YiiHelper\models\permission\PermissionMenu;
use YiiHelper\models\user\User;
use YiiHelper\models\user\UserAccount;

class Instance
{
    /**
     * 获取账户模型实例
     *
     * @return object|UserAccount
     * @throws InvalidConfigException
     */
    public static function modelUserAccount()
    {
        return Yii::createObject(UserAccount::class);
    }

    /**
     * 获取用户模型实例
     *
     * @return object|User
     * @throws InvalidConfigException
     */
    public static function modelUser()
    {
        return Yii::createObject(User::class);
    }

    /**
     * 获取操作模型实例
     *
     * @return object|User
     * @throws InvalidConfigException
     */
    public static function modelOperateLog()
    {
        return Yii::createObject(OperateLog::class);
    }

    /**
     * 获取 api后端路径 实例
     *
     * @return object|PermissionApi
     * @throws InvalidConfigException
     */
    public static function modelPermissionApi()
    {
        return Yii::createObject(PermissionApi::class);
    }

    /**
     * 获取 前端路径 实例
     *
     * @return object|PermissionMenu
     * @throws InvalidConfigException
     */
    public static function modelPermissionMenu()
    {
        return Yii::createObject(PermissionMenu::class);
    }
}