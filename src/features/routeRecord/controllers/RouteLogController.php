<?php
/**
 * @link        http://www.phpcorner.net
 * @author      qingbing<780042175@qq.com>
 * @copyright   Chengdu Qb Technology Co., Ltd.
 */

namespace YiiHelper\features\routeRecord\controllers;


use Exception;
use YiiHelper\abstracts\RestController;
use YiiHelper\features\routeRecord\services\interfaces\IRouteLogService;
use YiiHelper\features\routeRecord\services\RouteLogService;
use YiiHelper\models\routeLog\RouteAccessLog;
use Zf\Helper\Traits\Models\TLabelYesNo;

/**
 * 控制器 ： 路由日志
 *
 * Class RouteLogController
 * @package YiiHelper\features\routeRecord\controllers
 */
class RouteLogController extends RestController
{
    protected $serviceInterface = IRouteLogService::class;
    protected $serviceClass     = RouteLogService::class;

    /**
     * 路由日志搜索列表
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
            ['trace_id', 'string', 'label' => 'Trace ID'],
            ['keyword', 'string', 'label' => '关键字'],
            ['is_operate', 'in', 'label' => '是否操作', 'range' => array_keys(TLabelYesNo::yesNoLabels())],
            ['is_success', 'in', 'label' => '是否成功', 'range' => array_keys(TLabelYesNo::yesNoLabels())],
            ['ip', 'string', 'label' => '访问IP'],
            ['uid', 'string', 'label' => 'UID'],
            ['message', 'string', 'label' => '消息关键字'],
            ['route', 'string', 'label' => '路由'],
        ], null, true);

        // 业务处理
        $res = $this->service->list($params);
        // 渲染结果
        return $this->success($res, '路由日志列表');
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
                'id', 'exist', 'label' => '日志ID', 'targetClass' => RouteAccessLog::class, 'targetAttribute' => 'id'
            ],
        ]);
        // 业务处理
        $res = $this->service->view($params);
        // 渲染结果
        return $this->success($res, '路由日志详情');
    }
}