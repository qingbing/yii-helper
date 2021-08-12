<?php
/**
 * @link        http://www.phpcorner.net
 * @author      qingbing<780042175@qq.com>
 * @copyright   Chengdu Qb Technology Co., Ltd.
 */

namespace YiiHelper\features\interfaceManager\controllers;


use Exception;
use YiiHelper\abstracts\RestController;
use YiiHelper\features\interfaceManager\services\InterfaceRouteLogService;
use YiiHelper\features\interfaceManager\services\interfaces\IInterfaceRouteLogService;
use YiiHelper\models\interfaceManager\InterfaceRouteLogs;
use YiiHelper\models\interfaceManager\InterfaceSystems;
use YiiHelper\models\interfaceManager\InterfaceType;
use Zf\Helper\Traits\Models\TLabelYesNo;

/**
 * 控制器 ： 路由日志查询
 *
 * Class InterfaceRouteLogController
 * @package YiiHelper\features\interfaceManager\controllers
 *
 * @property-read IInterfaceRouteLogService $service
 */
class InterfaceRouteLogController extends RestController
{
    public $serviceInterface = IInterfaceRouteLogService::class;
    public $serviceClass     = InterfaceRouteLogService::class;

    /**
     * 路由日志列表
     *
     * @return array
     * @throws Exception
     */
    public function actionList()
    {
        // 参数验证和获取
        $params = $this->validateParams([
            ['system_alias', 'exist', 'label' => '系统别名', 'targetClass' => InterfaceSystems::class, 'targetAttribute' => 'system_alias'],
            ['interface_type', 'exist', 'label' => '接口分类', 'targetClass' => InterfaceType::class, 'targetAttribute' => 'type'],
            ['is_operate', 'in', 'label' => '是否操作类', 'range' => array_keys(TLabelYesNo::yesNoLabels())],
            ['interface_id', 'string', 'label' => '接口ID'],

            ['trace_id', 'string', 'label' => 'Trace ID'],
            ['method', 'in', 'label' => '请求方法', 'range' => array_keys(InterfaceRouteLogs::methods())],
            ['is_success', 'in', 'label' => '是否成功', 'range' => array_keys(TLabelYesNo::yesNoLabels())],

            ['ip', 'string', 'label' => '操作IP'],
            ['uid', 'string', 'label' => 'UID'],
            ['message', 'string', 'label' => '消息关键字'],
            ['keyword', 'string', 'label' => '关键字'],

            ['start_at', 'datetime', 'label' => '开始时间', 'format' => 'php:Y-m-d H:i:s'],
            ['end_at', 'datetime', 'label' => '结束时间', 'format' => 'php:Y-m-d H:i:s'],
        ], null, true);

        // 业务处理
        $res = $this->service->list($params);
        // 渲染结果
        return $this->success($res, '路由访问列表');
    }

    /**
     * 查看路由日志详情
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
                'id', 'exist', 'label' => '路由日志', 'targetClass' => InterfaceRouteLogs::class, 'targetAttribute' => 'id'
            ],
        ]);
        // 业务处理
        $res = $this->service->view($params);
        // 渲染结果
        return $this->success($res, '路由日志详情');
    }
}