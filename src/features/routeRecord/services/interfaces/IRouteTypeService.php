<?php
/**
 * @link        http://www.phpcorner.net
 * @author      qingbing<780042175@qq.com>
 * @copyright   Chengdu Qb Technology Co., Ltd.
 */

namespace YiiHelper\features\routeRecord\services\interfaces;


use YiiHelper\services\interfaces\ICurdService;

/**
 * 接口 ： 路由类型
 *
 * Interface IRouteTypeService
 * @package YiiHelper\features\routeRecord\services\interfaces
 */
interface IRouteTypeService extends ICurdService
{
    /**
     * 获取系统
     *
     * @return array
     */
    public function getSystemType(): array;
}