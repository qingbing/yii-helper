<?php
/**
 * @link        http://www.phpcorner.net
 * @author      qingbing<780042175@qq.com>
 * @copyright   Chengdu Qb Technology Co., Ltd.
 */

namespace YiiHelper\services\interfaces;


/**
 * 接口 ： 公共服务
 *
 * Interface IPubService
 * @package YiiHelper\services\interfaces
 */
interface IPubService extends IService
{
    /**
     * 选项卡(系统)
     *
     * @param array $params
     * @return array
     */
    public function systems(array $params = []): array;

    /**
     * 选项卡(路由类型)
     *
     * @param array $params
     * @return array
     */
    public function routeTypes(array $params): array;
}