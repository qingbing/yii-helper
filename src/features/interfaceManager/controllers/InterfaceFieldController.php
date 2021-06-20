<?php
/**
 * @link        http://www.phpcorner.net
 * @author      qingbing<780042175@qq.com>
 * @copyright   Chengdu Qb Technology Co., Ltd.
 */

namespace YiiHelper\features\interfaceManager\controllers;

use Exception;
use YiiHelper\abstracts\RestController;
use YiiHelper\features\interfaceManager\services\InterfaceFieldService;
use YiiHelper\features\interfaceManager\services\interfaces\IInterfaceFieldService;
use YiiHelper\models\interfaceManager\InterfaceFields;
use YiiHelper\models\interfaceManager\Interfaces;
use YiiHelper\validators\JsonValidator;
use Zf\Helper\Exceptions\CustomException;
use Zf\Helper\Traits\Models\TLabelYesNo;

/**
 * 控制器 ： 接口字段管理
 *
 * Class InterfaceFieldController
 * @package YiiHelper\features\interfaceManager\controllers
 *
 * @property-read IInterfaceFieldService $service
 */
class InterfaceFieldController extends RestController
{
    protected $serviceInterface = IInterfaceFieldService::class;
    protected $serviceClass     = InterfaceFieldService::class;

    /**
     * 接口字段列表
     *
     * @return array
     * @throws Exception
     */
    public function actionList()
    {
        // 参数验证和获取
        $params = $this->validateParams([
            [['alias'], 'required'],
            ['alias', 'exist', 'label' => '接口别名', 'targetClass' => Interfaces::class, 'targetAttribute' => 'alias'],
        ]);
        // 业务处理
        $res = $this->service->list($params);
        // 渲染结果
        return $this->success($res, '接口字段列表');
    }

    /**
     * 添加接口字段
     *
     * @return array
     * @throws Exception
     */
    public function actionAdd()
    {
        throw new CustomException("未开放功能");
        $interfaceAlias = $this->getParam('interface_alias');
        $parentField    = $this->getParam('parent_field', '');
        $type           = $this->getParam('type', '');
        $dataArea       = $this->getParam('data_area', '');
        // 参数验证和获取
        $rules = [
            [['interface_alias', 'field', 'alias', 'type', 'data_area'], 'required'],
            ['interface_alias', 'exist', 'label' => '接口别名', 'targetClass' => Interfaces::class, 'targetAttribute' => 'alias'],
            ['parent_field', 'string', 'label' => '上级字段别名', 'default' => ''],
            [
                'field',
                'unique',
                'label'           => '字段名',
                'targetClass'     => InterfaceFields::class,
                'targetAttribute' => 'field',
                'filter'          => [
                    'and',
                    ['=', 'interface_alias', $interfaceAlias],
                    ['=', 'parent_field', $parentField],
                    ['=', 'type', $type],
                    ['=', 'data_area', $dataArea],
                ]
            ],
            ['name', 'string', 'label' => '字段意义'],
            ['default', 'string', 'label' => '默认值'],
            ['type', 'in', 'label' => '字段类型', 'range' => array_keys(InterfaceFields::types())],
            ['data_area', 'in', 'label' => '数据区域', 'range' => array_keys(InterfaceFields::dataAreas())],
            ['data_type', 'in', 'label' => '数据类型', 'range' => array_keys(InterfaceFields::dataTypes())],
            ['description', 'string', 'label' => '字段描述'],
            ['is_required', 'in', 'label' => '是否必填', 'range' => array_keys(TLabelYesNo::isLabels())],
            ['is_ignore', 'in', 'label' => '忽略字段', 'range' => array_keys(TLabelYesNo::isLabels())],
            ['is_last_level', 'in', 'label' => '最后级别', 'range' => array_keys(TLabelYesNo::isLabels())],
            ['rules', JsonValidator::class, 'label' => '验证规则'],
        ];
        if (!empty($parentField)) {
            $rules[] = [
                'parent_field',
                'exist',
                'targetClass'     => InterfaceFields::class,
                'targetAttribute' => 'field',
                'filter'          => [
                    'and',
                    ['=', 'interface_alias', $interfaceAlias],
                    ['=', 'type', $type],
                    ['=', 'data_area', $dataArea],
                ]
            ];
        }
        $params = $this->validateParams($rules);
        // 业务处理
        $res = $this->service->add($params);
        // 渲染结果
        return $this->success($res, '添加接口字段');
    }

    /**
     * 编辑接口字段
     *
     * @return array
     * @throws Exception
     */
    public function actionEdit()
    {
        // 参数验证和获取
        $params = $this->validateParams([
            [['id'], 'required'],
            ['id', 'exist', 'label' => '接口字段', 'targetClass' => InterfaceFields::class, 'targetAttribute' => 'id'],
            ['name', 'string', 'label' => '字段意义'],
            ['default', 'string', 'label' => '默认值'],
            ['data_type', 'in', 'label' => '数据类型', 'range' => array_keys(InterfaceFields::dataTypes())],
            ['description', 'string', 'label' => '字段描述'],
            ['is_required', 'in', 'label' => '是否必填', 'range' => array_keys(TLabelYesNo::isLabels())],
            ['is_ignore', 'in', 'label' => '忽略字段', 'range' => array_keys(TLabelYesNo::isLabels())],
            ['is_last_level', 'in', 'label' => '最后级别', 'range' => array_keys(TLabelYesNo::isLabels())],
            ['rules', JsonValidator::class, 'label' => '验证规则'],
        ]);
        // 业务处理
        $res = $this->service->edit($params);
        // 渲染结果
        return $this->success($res, '编辑接口字段');
    }

    /**
     * 删除接口字段
     *
     * @return array
     * @throws Exception
     */
    public function actionDel()
    {
        // 参数验证和获取
        $params = $this->validateParams([
            [['id'], 'required'],
            ['id', 'exist', 'label' => '接口字段', 'targetClass' => InterfaceFields::class, 'targetAttribute' => 'id'],
        ]);
        // 业务处理
        $res = $this->service->del($params);
        // 渲染结果
        return $this->success($res, '删除接口字段');
    }

    /**
     * 查看接口字段详情
     *
     * @return array
     * @throws Exception
     */
    public function actionView()
    {
        // 参数验证和获取
        $params = $this->validateParams([
            [['id'], 'required'],
            ['id', 'exist', 'label' => '接口字段', 'targetClass' => InterfaceFields::class, 'targetAttribute' => 'id'],
        ]);
        // 业务处理
        $res = $this->service->view($params);
        // 渲染结果
        return $this->success($res, '查看接口字段详情');
    }
}