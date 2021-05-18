<?php
/**
 * @link        http://www.phpcorner.net
 * @author      qingbing<780042175@qq.com>
 * @copyright   Chengdu Qb Technology Co., Ltd.
 */

namespace YiiHelper\features\replaceSetting\controllers;


use Exception;
use YiiHelper\abstracts\RestController;
use YiiHelper\features\replaceSetting\services\interfaces\IReplaceSettingService;
use YiiHelper\features\replaceSetting\services\ReplaceSettingService;
use YiiHelper\models\replaceSetting\ReplaceSetting;
use YiiHelper\validators\JsonValidator;
use Zf\Helper\Traits\Models\TLabelYesNo;

/**
 * 控制器 ： 替换配置
 *
 * Class ReplaceSettingController
 * @package YiiHelper\features\replaceSetting\controllers
 *
 * @property-read IReplaceSettingService $service
 */
class ReplaceSettingController extends RestController
{
    protected $serviceClass     = ReplaceSettingService::class;
    protected $serviceInterface = IReplaceSettingService::class;

    /**
     * 替换配置列表
     *
     * @return array
     * @throws Exception
     */
    public function actionList()
    {
        // 参数验证和获取
        $params = $this->validateParams([
            ['code', 'string', 'label' => '标识码'],
            ['name', 'string', 'label' => '配置名称'],
            ['is_open', 'in', 'label' => '公开状态', 'range' => array_keys(TLabelYesNo::yesNoLabels())],
        ], null, true);

        // 业务处理
        $res = $this->service->list($params);
        // 渲染结果
        return $this->success($res, '替换配置列表');
    }

    /**
     * 编辑替换配置
     *
     * @return array
     * @throws Exception
     */
    public function actionEdit()
    {
        // 参数验证和获取
        $params = $this->validateParams([
            [['code', 'name', 'description', 'sort_order', 'is_open'], 'required'],
            [['code', 'name', 'description', 'content'], 'string'],
            [['is_open', 'sort_order'], 'integer'],
            ['code', 'label' => '标识码', 'exist', 'targetClass' => ReplaceSetting::class, 'targetAttribute' => ['code', 'name']],
            ['name', 'unique', 'label' => '配置名称', 'targetClass' => ReplaceSetting::class, 'filter' => ['!=', 'code', $this->getParam('code')]],
            ['is_open', 'in', 'label' => '公开状态', 'range' => array_keys(TLabelYesNo::yesNoLabels())],
            ['replace_fields', JsonValidator::class, 'label' => '字段集'],
            ['sort_order', 'safe', 'label' => '排序'],
            ['content', 'safe', 'label' => '模板'],
            ['description', 'safe', 'label' => '描述'],
        ]);
        // 业务处理
        $res = $this->service->edit($params);
        // 渲染结果
        return $this->success($res, '编辑替换配置成功');
    }

    /**
     * 替换配置详情
     *
     * @return array
     * @throws Exception
     */
    public function actionView()
    {
        // 参数验证和获取
        $params = $this->validateParams([
            [['code'], 'required'],
            ['code', 'exist', 'label' => '标识码', 'targetClass' => ReplaceSetting::class, 'targetAttribute' => 'code'],
        ]);
        // 业务处理
        $res = $this->service->view($params);
        // 渲染结果
        return $this->success($res, '替换配置详情');
    }
}