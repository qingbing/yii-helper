<?php
/**
 * @link        http://www.phpcorner.net
 * @author      qingbing<780042175@qq.com>
 * @copyright   Chengdu Qb Technology Co., Ltd.
 */

namespace YiiHelper\features\accessLog\controllers;


use Exception;
use YiiHelper\abstracts\RestController;
use YiiHelper\features\accessLog\services\AccessLogService;
use YiiHelper\features\accessLog\services\interfaces\IAccessLogService;
use YiiHelper\models\accessLog\AccessLogs;
use YiiHelper\models\routeManager\RouteSystems;
use Zf\Helper\Traits\Models\TLabelYesNo;

/**
 * 控制器 ： 接口访问日志
 *
 * Class AccessLogController
 * @package YiiHelper\features\routeManager\controllers
 *
 * @property-read IAccessLogService $service
 */
class AccessLogController extends RestController
{
    public $serviceInterface = IAccessLogService::class;
    public $serviceClass     = AccessLogService::class;

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
            ['system_code', 'exist', 'label' => '系统别名', 'targetClass' => RouteSystems::class, 'targetAttribute' => 'code'],

            ['trace_id', 'string', 'label' => 'Trace ID'],
            ['url_path', 'string', 'label' => '接口路径'],
            ['method', 'in', 'label' => '请求方法', 'range' => array_keys(AccessLogs::methods())],
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
                'id', 'exist', 'label' => '接口日志', 'targetClass' => AccessLogs::class, 'targetAttribute' => 'id'
            ],
        ]);
        // 业务处理
        $res = $this->service->view($params);
        // 渲染结果
        return $this->success($res, '接口访问日志详情');
    }
}