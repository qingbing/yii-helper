<?php
/**
 * @link        http://www.phpcorner.net
 * @author      qingbing<780042175@qq.com>
 * @copyright   Chengdu Qb Technology Co., Ltd.
 */

namespace YiiHelper\features\interfaceManager\services\interfaces;


use YiiHelper\services\interfaces\IService;

/**
 * 接口 ： 路由日志查询
 *
 * Interface IInterfaceRouteLogService
 * @package YiiHelper\features\interfaceManager\services\interfaces
 */
interface IInterfaceRouteLogService extends IService
{
    /**
     * 路由访问日志列表
     *
     * @param array|null $params
     * @return array
     */
    public function list(array $params = []): array;

    /**
     * 查看路由访问日志详情
     *
     * @param array $params
     * @return mixed
     */
    public function view(array $params);
}