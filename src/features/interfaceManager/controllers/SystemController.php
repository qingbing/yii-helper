<?php
/**
 * @link        http://www.phpcorner.net
 * @author      qingbing<780042175@qq.com>
 * @copyright   Chengdu Qb Technology Co., Ltd.
 */

namespace YiiHelper\features\interfaceManager\controllers;


use YiiHelper\abstracts\RestController;
use YiiHelper\features\interfaceManager\services\interfaces\ISystemService;
use YiiHelper\features\interfaceManager\services\SystemService;
use YiiHelper\models\interfaceManager\InterfaceSystems;
use YiiHelper\validators\JsonValidator;
use Zf\Helper\Traits\Models\TLabelEnable;
use Zf\Helper\Traits\Models\TLabelYesNo;

/**
 * 服务 ： 系统管理
 *
 * Class SystemController
 * @package YiiHelper\features\interfaceManager\controllers
 */
class SystemController extends RestController
{
    protected $serviceInterface = ISystemService::class;
    protected $serviceClass     = SystemService::class;

    /**
     * 系统搜索列表
     *
     * @return array
     * @throws \Exception
     */
    public function actionList()
    {
        // 参数验证和获取
        $params = $this->validateParams([
            ['system_alias', 'string', 'label' => '系统标记'],
            ['system_name', 'string', 'label' => '系统名称'],
            ['is_enable', 'in', 'label' => '启用状态', 'range' => array_keys(TLabelEnable::enableLabels())],
            ['is_allow_new_interface', 'in', 'label' => '接受新接口', 'range' => array_keys(TLabelYesNo::isLabels())],
            ['is_record_field', 'in', 'label' => '记录新字段', 'range' => array_keys(TLabelYesNo::isLabels())],
            ['is_open_access_log', 'in', 'label' => '开启访问日志', 'range' => array_keys(TLabelYesNo::isLabels())],
            ['is_open_validate', 'in', 'label' => '开启接口校验', 'range' => array_keys(TLabelYesNo::isLabels())],
            ['is_strict_validate', 'in', 'label' => '开启严格校验', 'range' => array_keys(TLabelYesNo::isLabels())],
        ], null, true);

        // 业务处理
        $res = $this->service->list($params);
        // 渲染结果
        return $this->success($res, '系统列表');
    }

    /**
     * 添加系统
     *
     * @return array
     * @throws \Exception
     */
    public function actionAdd()
    {
        // 参数验证和获取
        $params = $this->validateParams([
            [['system_alias', 'system_name', 'sort_order'], 'required'],
            ['system_alias', 'unique', 'label' => '系统别名', 'targetClass' => InterfaceSystems::class, 'targetAttribute' => 'system_alias'],
            ['system_name', 'unique', 'label' => '系统名称', 'targetClass' => InterfaceSystems::class, 'targetAttribute' => 'system_name'],
            ['description', 'string', 'label' => '系统描述'],
            ['uri_prefix', 'string', 'label' => 'URI前缀'],
            ['rule', 'string', 'label' => '调用规则类型'],
            ['ext', JsonValidator::class, 'label' => '扩展字段'],
            ['sort_order', 'integer', 'label' => '排序'],
            ['is_enable', 'in', 'label' => '启用状态', 'range' => array_keys(TLabelEnable::enableLabels())],
            ['is_allow_new_interface', 'in', 'label' => '接受新接口', 'range' => array_keys(TLabelYesNo::isLabels())],
            ['is_record_field', 'in', 'label' => '记录新字段', 'range' => array_keys(TLabelYesNo::isLabels())],
            ['is_open_access_log', 'in', 'label' => '开启访问日志', 'range' => array_keys(TLabelYesNo::isLabels())],
            ['is_open_validate', 'in', 'label' => '开启接口校验', 'range' => array_keys(TLabelYesNo::isLabels())],
            ['is_strict_validate', 'in', 'label' => '开启严格校验', 'range' => array_keys(TLabelYesNo::isLabels())],
        ]);
        // 业务处理
        $res = $this->service->add($params);
        // 渲染结果
        return $this->success($res, '添加系统成功');
    }

    /**
     * 编辑系统
     *
     * @return array
     * @throws \Exception
     */
    public function actionEdit()
    {
        // 参数获取
        $alias = $this->getParam('system_alias');
        // 参数验证和获取
        $params = $this->validateParams([
            [['id'], 'required'], // 必填字段减少，为了表格编辑
            ['id', 'exist', 'label' => '系统ID', 'targetClass' => InterfaceSystems::class, 'targetAttribute' => 'id'],
            ['system_alias', 'exist', 'label' => '系统别名', 'targetClass' => InterfaceSystems::class, 'targetAttribute' => 'system_alias'],
            ['system_name', 'unique', 'label' => '系统名称', 'targetClass' => InterfaceSystems::class, 'targetAttribute' => 'system_name', 'filter' => ['!=', 'system_alias', $alias]],
            ['description', 'string', 'label' => '系统描述'],
            ['uri_prefix', 'string', 'label' => 'URI前缀'],
            ['rule', 'string', 'label' => '调用规则类型'],
            ['ext', JsonValidator::class, 'label' => '扩展字段'],
            ['sort_order', 'integer', 'label' => '排序'],
            ['is_enable', 'in', 'label' => '启用状态', 'range' => array_keys(TLabelEnable::enableLabels())],
            ['is_allow_new_interface', 'in', 'label' => '接受新接口', 'range' => array_keys(TLabelYesNo::isLabels())],
            ['is_record_field', 'in', 'label' => '记录新字段', 'range' => array_keys(TLabelYesNo::isLabels())],
            ['is_open_access_log', 'in', 'label' => '开启访问日志', 'range' => array_keys(TLabelYesNo::isLabels())],
            ['is_open_validate', 'in', 'label' => '开启接口校验', 'range' => array_keys(TLabelYesNo::isLabels())],
            ['is_strict_validate', 'in', 'label' => '开启严格校验', 'range' => array_keys(TLabelYesNo::isLabels())],
        ]);
        // 业务处理
        $res = $this->service->edit($params);
        // 渲染结果
        return $this->success($res, '编辑系统成功');
    }

    /**
     * 删除系统
     *
     * @return array
     * @throws \Exception
     */
    public function actionDel()
    {
        // 参数验证和获取
        $params = $this->validateParams([
            [['id'], 'required'],
            ['id', 'exist', 'label' => '系统ID', 'targetClass' => InterfaceSystems::class, 'targetAttribute' => 'id'],
        ]);
        // 业务处理
        $res = $this->service->del($params);
        // 渲染结果
        return $this->success($res, '删除系统成功');
    }

    /**
     * 查看系统详情
     *
     * @return array
     * @throws \Exception
     */
    public function actionView()
    {
        // 参数验证和获取
        $params = $this->validateParams([
            [['id'], 'required'],
            ['id', 'exist', 'label' => '系统ID', 'targetClass' => InterfaceSystems::class, 'targetAttribute' => 'id'],
        ]);
        // 业务处理
        $res = $this->service->view($params);
        // 渲染结果
        return $this->success($res, '系统详情');
    }
}