<?php
/**
 * @link        http://www.phpcorner.net
 * @author      qingbing<780042175@qq.com>
 * @copyright   Chengdu Qb Technology Co., Ltd.
 */

namespace YiiHelper\features\routeManager\actions;


use Exception;
use yii\base\Action;
use YiiHelper\models\routeManager\RouteSystems;
use YiiHelper\traits\TResponse;

/**
 * 操作 ： 接口系统类型选项
 *
 * Class SystemTypeOptions
 * @package YiiHelper\features\routeManager\actions
 */
class SystemTypeOptions extends Action
{
    use TResponse;

    /**
     * 接口系统类型选项
     *
     * @return array
     * @throws Exception
     */
    public function run()
    {
        return $this->success(RouteSystems::types());
    }
}