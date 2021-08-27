<?php
/**
 * @link        http://www.phpcorner.net
 * @author      qingbing<780042175@qq.com>
 * @copyright   Chengdu Qb Technology Co., Ltd.
 */

namespace YiiHelper\controllers;


use YiiHelper\abstracts\RestController;
use YiiHelper\features\form\actions\FormOption;
use YiiHelper\features\permission\actions\UserPermission;
use YiiHelper\features\routeManager\actions\SystemOptions;
use YiiHelper\features\routeManager\actions\SystemTypeOptions;
use YiiHelper\features\tableHeader\actions\TableHeader;

/**
 * 控制器 ： 公共服务
 *
 * Class PubController
 * @package YiiHelper\controllers
 */
class PublicController extends RestController
{
    /**
     * 操作集合
     *
     * @return array
     */
    public function actions()
    {
        return [
            // 当前用户权限，可以为访客
            'permission'          => UserPermission::class,
            // 接口系统选项
            'option-systems'      => SystemOptions::class,
            // 接口系统类型选项
            'option-type-systems' => SystemTypeOptions::class,

            // 获取表头类型选项
            'header-options'      => TableHeader::class,
            // 获取表单类型选项
            'form-options'        => FormOption::class,
        ];
    }
}
