<?php
/**
 * @link        http://www.phpcorner.net
 * @author      qingbing<780042175@qq.com>
 * @copyright   Chengdu Qb Technology Co., Ltd.
 */

namespace YiiHelper\components\interfaceManager\assist;


use yii\base\BaseObject;
use YiiHelper\components\interfaceManager\InterfaceManager;

/**
 * 抽象类 ： 路由日志自定义
 * Class RouteLogBase
 * @package YiiHelper\components
 */
abstract class RouteLogBase extends BaseObject implements IRouteLog
{
    /**
     * @var InterfaceManager 路由日志组件
     */
    protected $interfaceManager;

    /**
     * 路由日志构造函数
     *
     * @param InterfaceManager $interfaceManager
     * @param array $config
     */
    public function __construct(InterfaceManager $interfaceManager, $config = [])
    {
        parent::__construct($config);
        $this->interfaceManager = $interfaceManager;
    }
}