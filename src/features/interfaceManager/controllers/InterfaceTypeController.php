<?php
/**
 * @link        http://www.phpcorner.net
 * @author      qingbing<780042175@qq.com>
 * @copyright   Chengdu Qb Technology Co., Ltd.
 */

namespace YiiHelper\features\interfaceManager\controllers;


use Exception;
use YiiHelper\abstracts\RestController;
use YiiHelper\features\interfaceManager\services\interfaces\IInterfaceTypeService;
use YiiHelper\features\interfaceManager\services\InterfaceTypeService;
use YiiHelper\models\interfaceManager\InterfaceSystems;
use YiiHelper\models\interfaceManager\InterfaceType;

/**
 * 控制器 ： 接口类型
 *
 * Class SystemTypeController
 * @package YiiHelper\features\interfaceManager\controllers
 *
 * @property-read IInterfaceTypeService $service
 */
class InterfaceTypeController extends RestController
{
    public $serviceInterface = IInterfaceTypeService::class;
    public $serviceClass     = InterfaceTypeService::class;

    /**
     * 接口类型搜索列表
     *
     * @return array
     * @throws Exception
     */
    public function actionList()
    {
        // 参数验证和获取
        $params = $this->validateParams([
            ['system_alias', 'string', 'label' => '系统别名'],
            ['type', 'string', 'label' => '接口分类'],
            ['type_name', 'string', 'label' => '类型名称'],
        ], null, true);

        // 业务处理
        $res = $this->service->list($params);
        // 渲染结果
        return $this->success($res, '接口类型列表');
    }

    /**
     * 添加接口类型
     *
     * @return array
     * @throws Exception
     */
    public function actionAdd()
    {
        $systemAlias = $this->getParam('system_alias');
        // 参数验证和获取
        $params = $this->validateParams([
            [['system_alias', 'type', 'type_name'], 'required'],
            [
                'system_alias', 'exist',
                'label'           => '系统别名',
                'targetClass'     => InterfaceSystems::class,
                'targetAttribute' => 'system_alias'
            ],
            [
                'type', 'unique',
                'label'           => '接口分类',
                'targetClass'     => InterfaceType::class,
                'targetAttribute' => 'type',
                'filter'          => ['=', 'system_alias', $systemAlias]
            ],
            [
                'type_name', 'unique',
                'label'           => '类型名称',
                'targetClass'     => InterfaceType::class,
                'targetAttribute' => 'type_name',
                'filter'          => ['=', 'system_alias', $systemAlias]
            ],
            ['description', 'string', 'label' => '类型描述'],
            ['sort_order', 'integer', 'label' => '排序'],
        ]);

        // 业务处理
        $res = $this->service->add($params);
        // 渲染结果
        return $this->success($res, '添加接口类型成功');
    }

    /**
     * 编辑接口类型
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
                'id', 'exist', 'label' => '类型ID', 'targetClass' => InterfaceType::class, 'targetAttribute' => 'id'
            ],
            [
                'system_alias', 'exist',
                'label'           => '系统别名',
                'targetClass'     => InterfaceSystems::class,
                'targetAttribute' => 'system_alias'
            ],
            [
                'type_name', 'unique',
                'label'           => '类型名称',
                'targetClass'     => InterfaceType::class,
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
        return $this->success($res, '接口类型编辑成功');
    }

    /**
     * 删除接口类型
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
                'id', 'exist', 'label' => '类型ID', 'targetClass' => InterfaceType::class, 'targetAttribute' => 'id'
            ],
        ]);
        // 业务处理
        $res = $this->service->del($params);
        // 渲染结果
        return $this->success($res, '删除接口类型成功');
    }

    /**
     * 查看接口类型详情
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
                'id', 'exist', 'label' => '类型ID', 'targetClass' => InterfaceType::class, 'targetAttribute' => 'id'
            ],
        ]);
        // 业务处理
        $res = $this->service->view($params);
        // 渲染结果
        return $this->success($res, '接口类型详情');
    }
}