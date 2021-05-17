<?php
/**
 * @link        http://www.phpcorner.net
 * @author      qingbing<780042175@qq.com>
 * @copyright   Chengdu Qb Technology Co., Ltd.
 */

namespace YiiHelper\features\routeRecord\controllers;


use Exception;
use YiiHelper\abstracts\RestController;
use YiiHelper\features\routeRecord\services\interfaces\IRouteRecordService;
use YiiHelper\features\routeRecord\services\RouteRecordService;
use YiiHelper\models\interfaceLogs\InterfaceSystem;
use YiiHelper\models\routeLog\RouteRecord;
use YiiHelper\models\routeLog\RouteType;
use Zf\Helper\Traits\Models\TLabelYesNo;

/**
 * 控制器 ： 路由记录
 *
 * Class RouteRecordController
 * @package YiiHelper\features\routeRecord\controllers
 *
 * @property-read IRouteRecordService $service
 */
class RouteRecordController extends RestController
{
    protected $serviceClass     = RouteRecordService::class;
    protected $serviceInterface = IRouteRecordService::class;

    /**
     * 获取路由类型
     *
     * @return array
     * @throws Exception
     */
    public function actionRouteType()
    {
        // 参数验证和获取
        $params = $this->validateParams([
            ['system_alias', 'required', 'label' => '系统别名'],
            [
                'system_alias', 'exist',
                'label'           => '系统别名',
                'targetClass'     => InterfaceSystem::class,
                'targetAttribute' => 'alias'
            ],
        ]);
        // 业务处理
        $res = $this->service->getRouteTypes($params);
        // 渲染结果
        return $this->success($res, '路由类型');
    }

    /**
     * 路由搜索列表
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
            ['route', 'string', 'label' => '路由'],
            ['is_operate', 'in', 'label' => '是否操作', 'range' => array_keys(TLabelYesNo::yesNoLabels())],
        ], null, true);
        // 业务处理
        $res = $this->service->list($params);
        // 渲染结果
        return $this->success($res, '路由列表');
    }

    /**
     * 编辑路由
     *
     * @return array
     * @throws Exception
     */
    public function actionEdit()
    {
        // 参数验证和获取
        $params = $this->validateParams([
            ['id', 'required'],
            [
                'id', 'exist', 'label' => '类型ID', 'targetClass' => RouteRecord::class, 'targetAttribute' => 'id'
            ],
            [
                'route_type', 'exist',
                'label'           => '路由类型',
                'targetClass'     => RouteType::class,
                'targetAttribute' => 'route_type',
            ],
            ['description', 'string', 'label' => '类型描述'],
            ['sort_order', 'integer', 'label' => '排序', 'default' => 1],
            ['is_operate', 'in', 'label' => '是否操作', 'default' => 0, 'range' => array_keys(TLabelYesNo::yesNoLabels())],
        ]);
        // 业务处理
        $res = $this->service->edit($params);
        // 渲染结果
        return $this->success($res, '路由编辑成功');
    }

    /**
     * 删除路由
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
                'id', 'exist', 'label' => '类型ID', 'targetClass' => RouteRecord::class, 'targetAttribute' => 'id'
            ],
        ]);
        // 业务处理
        $res = $this->service->del($params);
        // 渲染结果
        return $this->success($res, '删除路由成功');
    }

    /**
     * 查看路由详情
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
                'id', 'exist', 'label' => '类型ID', 'targetClass' => RouteRecord::class, 'targetAttribute' => 'id'
            ],
        ]);
        // 业务处理
        $res = $this->service->view($params);
        // 渲染结果
        return $this->success($res, '路由详情');
    }

    /**
     * 编辑路由日志配置
     *
     * @return array
     * @throws Exception
     */
    public function actionEditLogConfig()
    {
        // 参数验证和获取
        $params = $this->validateParams([
            [['id', 'is_logging'], 'required'],
            [
                'id', 'exist', 'label' => '类型ID', 'targetClass' => RouteRecord::class, 'targetAttribute' => 'id'
            ],
            ['is_logging', 'in', 'label' => '是否记录', 'default' => 0, 'range' => array_keys(TLabelYesNo::yesNoLabels())],
            ['message', 'string', 'label' => '操作提示', 'default' => ''],
            ['key_fields', 'string', 'label' => '关键字标志', 'default' => ''],
        ]);
        // 业务处理
        $res = $this->service->editLogConfig($params);
        // 渲染结果
        return $this->success($res, '编辑路由日志配置成功');
    }
}