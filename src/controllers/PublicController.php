<?php
/**
 * @link        http://www.phpcorner.net
 * @author      qingbing<780042175@qq.com>
 * @copyright   Chengdu Qb Technology Co., Ltd.
 */

namespace YiiHelper\controllers;


use YiiHelper\abstracts\RestController;
use YiiHelper\actions\ClearCache;
use YiiHelper\features\form\actions\FormOption;
use YiiHelper\features\interfaceManager\actions\InterfaceSystemOptions;
use YiiHelper\features\interfaceManager\actions\InterfaceTypeOptions;
use YiiHelper\features\routeManager\actions\SystemOptions;
use YiiHelper\features\tableHeader\actions\TableHeader;
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
class PublicController extends RestController
{
    public $serviceInterface = IPubService::class;
    public $serviceClass     = PubService::class;

    /**
     * 操作集合
     *
     * @return array
     */
    public function actions()
    {
        return [
            // 清除系统缓存
            'clear-cache'    => [
                'class' => ClearCache::class
            ],
            // 获取表头类型选项
            'header-options' => [
                'class' => TableHeader::class
            ],
            // 获取表单类型选项
            'form-options'   => [
                'class' => FormOption::class
            ],
            // 获取表单类型选项
            'option-systems' => [
                'class' => SystemOptions::class
            ],
        ];
    }
}
