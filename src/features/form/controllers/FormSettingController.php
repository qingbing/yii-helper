<?php
/**
 * @link        http://www.phpcorner.net
 * @author      qingbing<780042175@qq.com>
 * @copyright   Chengdu Qb Technology Co., Ltd.
 */

namespace YiiHelper\features\form\controllers;


use Exception;
use YiiHelper\abstracts\RestController;
use YiiHelper\features\form\services\FormSettingService;
use YiiHelper\features\form\services\interfaces\IFormSettingService;
use YiiHelper\models\form\FormCategory;

/**
 * 控制器 ： 配置表单管理
 *
 * Class FormSettingController
 * @package YiiHelper\features\form\controllers
 *
 * @property-read IFormSettingService $service
 */
class FormSettingController extends RestController
{
    protected $serviceInterface = IFormSettingService::class;
    protected $serviceClass     = FormSettingService::class;

    /**
     * 获取配置表单选项
     *
     * @return array
     * @throws Exception
     */
    public function actionGet()
    {
        // 参数验证和获取
        $params = $this->validateParams([
            [['key'], 'required'],
            ['key', 'exist', 'label' => '表单标记', 'targetClass' => FormCategory::class, 'targetAttribute' => 'key'],
        ]);
        // 业务处理
        $res = $this->service->get($params);
        // 渲染结果
        return $this->success($res, '表单选项列表');
    }

    /**
     * 保存配置表单数据
     *
     * @return array
     * @throws Exception
     */
    public function actionSave()
    {
        // 参数验证和获取
        $this->validateParams([
            [['key'], 'required'],
            ['key', 'exist', 'label' => '表单标记', 'targetClass' => FormCategory::class, 'targetAttribute' => 'key'],
        ]);
        // 业务处理
        $res = $this->service->save(array_merge($this->request->getQueryParams(), $this->request->getBodyParams()));
        // 渲染结果
        return $this->success($res, '表单选项列表');
    }
}