<?php
/**
 * @link        http://www.phpcorner.net
 * @author      qingbing<780042175@qq.com>
 * @copyright   Chengdu Qb Technology Co., Ltd.
 */

namespace YiiHelper\components\routeManager;


use yii\base\BaseObject;

/**
 * 抽象类 ： 路由日志自定义
 *
 * Class RouteLogBase
 * @package YiiHelper\components
 */
abstract class RouteLogBase extends BaseObject implements IRouteLog
{
    /**
     * @var RouteManager 路由日志组件
     */
    protected $routeManager;

    /**
     * 路由日志构造函数
     *
     * @param RouteManager $routeManager
     * @param array $config
     */
    public function __construct(RouteManager $routeManager, $config = [])
    {
        parent::__construct($config);
        $this->routeManager = $routeManager;
    }
}