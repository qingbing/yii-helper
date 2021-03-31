<?php
/**
 * @link        http://www.phpcorner.net
 * @author      qingbing<780042175@qq.com>
 * @copyright   Chengdu Qb Technology Co., Ltd.
 */

namespace YiiHelper\components;

/**
 * 扩展用户登录组件
 *
 * Class User
 * @package YiiHelper\components
 *
 * @property $username
 */
abstract class User extends \yii\web\User
{
    abstract public function getUsername();
}