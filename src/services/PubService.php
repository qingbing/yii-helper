<?php
/**
 * @link        http://www.phpcorner.net
 * @author      qingbing<780042175@qq.com>
 * @copyright   Chengdu Qb Technology Co., Ltd.
 */

namespace YiiHelper\services;


use YiiHelper\abstracts\Service;
use YiiHelper\models\routeLog\RouteType;
use YiiHelper\models\System;
use YiiHelper\services\interfaces\IPubService;

/**
 * 服务 ： 公共服务
 *
 * Class PubService
 * @package YiiHelper\services
 */
class PubService extends Service implements IPubService
{
    /**
     * 选项卡(系统)
     *
     * @param array $params
     * @return array
     */
    public function systems(array $params = []): array
    {
        $isOption = isset($params['is_option']) ? !!$params['is_option'] : true;
        return System::all($isOption);
    }

    /**
     * 选项卡(路由类型)
     *
     * @param array $params
     * @return array
     */
    public function routeTypes(array $params): array
    {
        $isOption = isset($params['is_option']) ? !!$params['is_option'] : true;
        return RouteType::all($params['system_alias'], $isOption);
    }
}