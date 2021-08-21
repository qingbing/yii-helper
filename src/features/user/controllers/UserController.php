<?php
/**
 * @link        http://www.phpcorner.net
 * @author      qingbing<780042175@qq.com>
 * @copyright   Chengdu Qb Technology Co., Ltd.
 */

namespace YiiHelper\features\user\controllers;


use YiiHelper\abstracts\RestController;
use YiiHelper\features\permission\actions\AssignUserRole;
use YiiHelper\features\permission\actions\UserPermission;

/**
 * 控制器 : 用户管理
 *
 * Class UserController
 * @package YiiHelper\features\user\controllers
 */
class UserController extends RestController
{
    public function actions()
    {
        return [
            // 给用户分配角色
            'assign-role' => AssignUserRole::class,
            // 当前用户权限，可以为访客
            'permission'  => UserPermission::class,
        ];
    }
}