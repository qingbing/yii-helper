<?php
/**
 * @link        http://www.phpcorner.net
 * @author      qingbing<780042175@qq.com>
 * @copyright   Chengdu Qb Technology Co., Ltd.
 */

namespace YiiHelper\features\routeRecord\controllers;


use Exception;
use YiiHelper\abstracts\RestController;
use YiiHelper\features\routeRecord\services\interfaces\IRouteTypeService;
use YiiHelper\features\routeRecord\services\RouteTypeService;
use YiiHelper\models\interfaceLogs\InterfaceSystem;
use YiiHelper\models\routeLog\RouteType;

/**
 * 控制器 ： 路由类型
 *
 * Class RouteTypeController
 * @package YiiHelper\features\replaceSetting\controllers
 *
 * @property-read IRouteTypeService $service
 */
class RouteTypeController extends RestController
{
    protected $serviceClass     = RouteTypeService::class;
    protected $serviceInterface = IRouteTypeService::class;

    /**
     * 获取系统
     *
     * @return array
     * @throws Exception
     */
    public function actionSystemType()
    {
        // 参数验证和获取
        $params = $this->validateParams([
            ['keyword', 'string', 'label' => '关键字'],
        ]);
        // 业务处理
        $res = $this->service->getSystemType($params);
        // 渲染结果
        return $this->success($res, '系统');
    }

    /**
     * 路由类型搜索列表
     *
     * @return array
     * @throws Exception
     */
    public function actionList()
    {
        // 参数验证和获取
        $params = $this->validateParams([
            ['system_alias', 'string', 'label' => '系统别名'],
            ['route_type', 'string', 'label' => '路由分类'],
            ['type_name', 'string', 'label' => '类型名称'],
        ], null, true);

        // 业务处理
        $res = $this->service->list($params);
        // 渲染结果
        return $this->success($res, '路由类型列表');
    }

    /**
     * 添加路由类型
     *
     * @return array
     * @throws Exception
     */
    public function actionAdd()
    {
        $systemAlias = $this->getParam('system_alias');
        // 参数验证和获取
        $params = $this->validateParams([
            [['system_alias', 'route_type', 'type_name'], 'required'],
            [
                'system_alias', 'exist',
                'label'           => '系统别名',
                'targetClass'     => InterfaceSystem::class,
                'targetAttribute' => 'alias'
            ],
            [
                'route_type', 'unique',
                'label'           => '类型别名',
                'targetClass'     => RouteType::class,
                'targetAttribute' => 'route_type',
                'filter'          => ['=', 'system_alias', $systemAlias]
            ],
            [
                'type_name', 'unique',
                'label'           => '类型名称',
                'targetClass'     => RouteType::class,
                'targetAttribute' => 'type_name',
                'filter'          => ['=', 'system_alias', $systemAlias]
            ],
            ['description', 'string', 'label' => '类型描述'],
            ['sort_order', 'integer', 'label' => '排序'],
        ]);

        // 业务处理
        $res = $this->service->add($params);
        // 渲染结果
        return $this->success($res, '添加路由类型成功');
    }

    /**
     * 编辑路由类型
     *
     * @return array
     * @throws Exception
     */
    public function actionEdit()
    {
        // 参数验证和获取
        $id          = $this->getParam('id');
        $systemAlias = $this->getParam('system_alias');
        $params      = $this->validateParams([
            [['id', 'system_alias', 'type_name'], 'required'],
            [
                'id', 'exist', 'label' => '类型ID', 'targetClass' => RouteType::class, 'targetAttribute' => 'id'
            ],
            [
                'system_alias', 'exist',
                'label'           => '系统别名',
                'targetClass'     => InterfaceSystem::class,
                'targetAttribute' => 'alias'
            ],
            [
                'type_name', 'unique',
                'label'           => '类型名称',
                'targetClass'     => RouteType::class,
                'targetAttribute' => 'type_name',
                'filter'          => [
                    'and',
                    ['=', 'system_alias', $systemAlias],
                    ['!=', 'id', $id],
                ]
            ],
            ['description', 'string', 'label' => '类型描述'],
            ['sort_order', 'integer', 'label' => '排序'],
        ]);
        // 业务处理
        $res = $this->service->edit($params);
        // 渲染结果
        return $this->success($res, '路由类型编辑成功');
    }

    /**
     * 删除路由类型
     *
     * @return array
     * @throws Exception
     */
    public function actionDel()
    {
        // 参数验证和获取
        $params = $this->validateParams([
            ['id', 'required'],
            [
                'id', 'exist', 'label' => '类型ID', 'targetClass' => RouteType::class, 'targetAttribute' => 'id'
            ],
        ]);
        // 业务处理
        $res = $this->service->del($params);
        // 渲染结果
        return $this->success($res, '删除路由类型成功');
    }

    /**
     * 查看路由类型详情
     *
     * @return array
     * @throws Exception
     */
    public function actionView()
    {
        // 参数验证和获取
        $params = $this->validateParams([
            ['id', 'required'],
            [
                'id', 'exist', 'label' => '类型ID', 'targetClass' => RouteType::class, 'targetAttribute' => 'id'
            ],
        ]);
        // 业务处理
        $res = $this->service->view($params);
        // 渲染结果
        return $this->success($res, '路由类型详情');
    }
}