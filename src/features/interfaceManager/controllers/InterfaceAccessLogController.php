<?php
/**
 * @link        http://www.phpcorner.net
 * @author      qingbing<780042175@qq.com>
 * @copyright   Chengdu Qb Technology Co., Ltd.
 */

namespace YiiHelper\features\interfaceManager\controllers;


use Exception;
use YiiHelper\abstracts\RestController;
use YiiHelper\features\interfaceManager\services\InterfaceAccessLogService;
use YiiHelper\features\interfaceManager\services\interfaces\IInterfaceAccessLogService;
use YiiHelper\models\interfaceManager\InterfaceAccessLogs;
use YiiHelper\models\interfaceManager\InterfaceSystems;
use YiiHelper\models\interfaceManager\InterfaceType;
use Zf\Helper\Traits\Models\TLabelYesNo;

/**
 * 控制器 ： 接口访问日志
 *
 * Class InterfaceAccessLogController
 * @package YiiHelper\features\interfaceManager\controllers
 *
 * @property-read IInterfaceAccessLogService $service
 */
class InterfaceAccessLogController extends RestController
{
    protected $serviceInterface = IInterfaceAccessLogService::class;
    protected $serviceClass     = InterfaceAccessLogService::class;

    /**
     * 接口访问日志列表
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
            ['method', 'in', 'label' => '请求方法', 'range' => array_keys(InterfaceAccessLogs::methods())],
            ['is_intercept', 'in', 'label' => '是否中断', 'range' => array_keys(TLabelYesNo::yesNoLabels())],
            ['is_success', 'in', 'label' => '是否成功', 'range' => array_keys(TLabelYesNo::yesNoLabels())],

            ['ip', 'string', 'label' => '访问IP'],
            ['uid', 'string', 'label' => 'UID'],
            ['message', 'string', 'label' => '消息关键字'],

            ['start_at', 'datetime', 'label' => '访问开始时间', 'format' => 'php:Y-m-d H:i:s'],
            ['end_at', 'datetime', 'label' => '访问结束时间', 'format' => 'php:Y-m-d H:i:s'],
        ], null, true);

        // 业务处理
        $res = $this->service->list($params);
        // 渲染结果
        return $this->success($res, '接口访问日志列表');
    }

    /**
     * 查看接口访问日志详情
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
                'id', 'exist', 'label' => '接口日志', 'targetClass' => InterfaceAccessLogs::class, 'targetAttribute' => 'id'
            ],
        ]);
        // 业务处理
        $res = $this->service->view($params);
        // 渲染结果
        return $this->success($res, '接口访问日志详情');
    }
}