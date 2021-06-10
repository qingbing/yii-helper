<?php
/**
 * @link        http://www.phpcorner.net
 * @author      qingbing<780042175@qq.com>
 * @copyright   Chengdu Qb Technology Co., Ltd.
 */

namespace YiiHelper\controllers;


use Exception;
use YiiHelper\abstracts\RestController;
use YiiHelper\actions\ClearCache;
use YiiHelper\features\form\actions\FormOption;
use YiiHelper\features\tableHeader\actions\TableHeader;
use YiiHelper\models\System;
use YiiHelper\services\interfaces\IPubService;
use YiiHelper\services\PubService;

/**
 * 控制器 ： 公共服务
 *
 * Class PubController
 * @package YiiHelper\controllers
 *
 * @property-read IPubService $service
 */
class PubController extends RestController
{
    protected $serviceInterface = IPubService::class;
    protected $serviceClass     = PubService::class;

    /**
     * 操作集合
     *
     * @return array
     */
    public function actions()
    {
        return [
            // 清除系统缓存
            'clear-cache'  => [
                'class' => ClearCache::class
            ],
            // 获取表头类型选项
            'header-options' => [
                'class' => TableHeader::class
            ],
            // 获取表单类型选项
            'form-options' => [
                'class' => FormOption::class
            ],
        ];
    }

    /**
     * 选项卡(系统)
     *
     * @return array
     * @throws Exception
     */
    public function actionOptionSystems()
    {
        // 业务处理
        $res = $this->service->systems();
        // 渲染结果
        return $this->success($res, '系统');
    }

    /**
     * 选项卡(路由类型)
     *
     * @return array
     * @throws Exception
     */
    public function actionOptionRouteTypes()
    {
        // 参数验证和获取
        $params = $this->validateParams([
            ['system_alias', 'exist', 'label' => '系统别名', 'targetClass' => System::class, 'targetAttribute' => 'alias'],
        ]);
        // 业务处理
        $res = $this->service->routeTypes($params);
        // 渲染结果
        return $this->success($res, '路由类型');
    }
}
