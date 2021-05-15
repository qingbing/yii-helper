<?php
/**
 * @link        http://www.phpcorner.net
 * @author      qingbing<780042175@qq.com>
 * @copyright   Chengdu Qb Technology Co., Ltd.
 */

namespace YiiHelper\features\login\services\interfaces;

/**
 * 接口类 ： 用户登录
 *
 * Interface LoginService
 * @package YiiHelper\features\login\services\interfaces
 */
interface ILoginService
{
    /**
     * 获取支持的登录类型
     *
     * @return array
     */
    public function getSupportTypes(): array;

    /**
     * 账户登录
     *
     * @param array $params
     * @return bool
     */
    public function signIn(array $params): bool;

    /**
     * 用户退出登录
     *
     * @return bool
     */
    public function signOut(): bool;

    /**
     * 判断是否用户登录
     *
     * @return bool
     */
    public function isLogin(): bool;
}