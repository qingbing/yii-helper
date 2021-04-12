<?php
/**
 * @link        http://www.phpcorner.net
 * @author      qingbing<780042175@qq.com>
 * @copyright   Chengdu Qb Technology Co., Ltd.
 */

namespace YiiHelper\components;

use YiiHelper\models\UserAccount;

/**
 * 扩展用户登录组件
 *
 * Class User
 * @package YiiHelper\components
 *
 * @property $username
 */
class User extends \yii\web\User
{
    /**
     * @var array 支持的用户登录类型
     */
    public $loginTypes = [
        UserAccount::TYPE_EMAIL,
    ];

    /**
     * 返回登录用户名
     *
     * @return string
     */
    public function getNickname()
    {
        return '';
    }
}