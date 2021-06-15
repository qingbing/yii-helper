<?php
/**
 * @link        http://www.phpcorner.net
 * @author      qingbing<780042175@qq.com>
 * @copyright   Chengdu Qb Technology Co., Ltd.
 */

namespace YiiHelper\features\routeRecord\services\interfaces;


use YiiHelper\services\interfaces\ICurdService;

/**
 * 接口 ： 路由记录
 *
 * Interface IRouteRecordService
 * @package YiiHelper\features\routeRecord\services\interfaces
 */
interface IRouteRecordService extends ICurdService
{
    /**
     * 编辑路由配置
     *
     * @param array $params
     * @return bool
     */
    public function editRecordConfig(array $params): bool;
}