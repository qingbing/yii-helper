<?php
/**
 * @link        http://www.phpcorner.net
 * @author      qingbing<780042175@qq.com>
 * @copyright   Chengdu Qb Technology Co., Ltd.
 */

namespace YiiHelper\features\login\services\loginType;


use YiiHelper\features\login\services\loginType\abstracts\LoginBase;

/**
 * 通过邮箱登录
 *
 * Class LoginByEmail
 * @package YiiHelper\services\login
 */
class LoginByEmail extends LoginBase
{
    /**
     * 获取登录类型
     *
     * @return string
     */
    public function getType(): string
    {
        return 'email';
    }
}