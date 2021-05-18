<?php
/**
 * @link        http://www.phpcorner.net
 * @author      qingbing<780042175@qq.com>
 * @copyright   Chengdu Qb Technology Co., Ltd.
 */

namespace YiiHelper\services\interfaces;

/**
 * 接口 ： 个人信息中心
 *
 * Interface IPersonalService
 * @package YiiHelper\services\interfaces
 */
interface IPersonalService extends IService
{
    /**
     * 个人信息
     *
     * @return mixed
     */
    public function info();

    /**
     * 修改个人信息
     *
     * @param array $params
     * @return bool
     */
    public function changeInfo(array $params): bool;

    /**
     * 修改个人密码
     *
     * @param array $params
     * @return bool
     */
    public function resetPassword(array $params): bool;
}