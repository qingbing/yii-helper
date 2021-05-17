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
     * 获取路由类型列表
     *
     * @param array $params
     * @return array
     */
    public function getRouteTypes(array $params): array;
}