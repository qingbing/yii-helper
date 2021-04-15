<?php
/**
 * @link        http://www.phpcorner.net
 * @author      qingbing<780042175@qq.com>
 * @copyright   Chengdu Qb Technology Co., Ltd.
 */

namespace YiiHelper\services\login;


use YiiHelper\services\login\abstracts\LoginBase;

/**
 * 通过手机号登录
 *
 * Class LoginByMobile
 * @package YiiHelper\services\login
 */
class LoginByMobile extends LoginBase
{
    /**
     * 获取登录类型
     *
     * @return string
     */
    public function getType(): string
    {
        return 'mobile';
    }
}