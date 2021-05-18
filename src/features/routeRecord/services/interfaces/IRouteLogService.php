<?php
/**
 * @link        http://www.phpcorner.net
 * @author      qingbing<780042175@qq.com>
 * @copyright   Chengdu Qb Technology Co., Ltd.
 */

namespace YiiHelper\features\routeRecord\services\interfaces;

use YiiHelper\services\interfaces\IService;

/**
 * 接口 ： 路由日志
 *
 * Interface IRouteLogService
 * @package YiiHelper\features\routeRecord\services\interfaces
 */
interface IRouteLogService extends IService
{
    /**
     * 路由日志列表
     *
     * @param array|null $params
     * @return array
     */
    public function list(array $params = []): array;

    /**
     * 查看路由日志详情
     *
     * @param array $params
     * @return mixed
     */
    public function view(array $params);
}