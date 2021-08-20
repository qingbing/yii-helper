<?php
/**
 * @link        http://www.phpcorner.net
 * @author      qingbing<780042175@qq.com>
 * @copyright   Chengdu Qb Technology Co., Ltd.
 */

namespace YiiHelper\features\permission\services\interfaces;


use YiiHelper\services\interfaces\ICurdService;

/**
 * 接口 : 角色管理
 *
 * Interface IRoleService
 * @package YiiHelper\features\permission\services\interfaces
 */
interface IRoleService extends ICurdService
{
    /**
     * 为角色分配菜单
     *
     * @param array $params
     * @return bool
     */
    public function assignMenu(array $params = []): bool;
}