<?php
/**
 * @link        http://www.phpcorner.net
 * @author      qingbing<780042175@qq.com>
 * @copyright   Chengdu Qb Technology Co., Ltd.
 */

namespace YiiHelper\controllers;

use Exception;
use yii\helpers\ArrayHelper;
use YiiHelper\abstracts\RestController;
use YiiHelper\filters\ActionFilter;
use YiiHelper\models\ReplaceSetting;
use YiiHelper\services\ReplaceSettingService;
use YiiHelper\validators\JsonValidator;
use Zf\Helper\Traits\Models\TLabelEnable;
use Zf\Helper\Traits\Models\TLabelYesNo;

/**
 * 控制器：替换模版
 *
 * Class ReplaceSettingController
 * @package YiiHelper\controllers
 */
class ReplaceSettingController extends RestController
{
    /**
     * 定义行为
     *
     * @return array
     */
    public function behaviors()
    {
        return ArrayHelper::merge(parent::behaviors(), [
            'actionCallback' => [
                'class'                => ActionFilter::class,
                'beforeActionCallback' => [$this, 'initData'],
            ]
        ]);
    }

    /**
     * @var ReplaceSetting
     */
    protected $newModel;

    /**
     * @var ReplaceSettingService
     */
    protected $service;

    /**
     * 初始化相关服务和db模型
     *
     * @return bool
     */
    public function initData()
    {
        $this->newModel = new ReplaceSetting();
        $this->service  = new ReplaceSettingService();
        $this->service->setModel($this->newModel);
        return true;
    }

    /**
     * 替换配置列表
     *
     * @return array
     * @throws Exception
     */
    public function actionSearch()
    {
        // 参数校验并获取校验字段值
        $params = $this->validateParams([
            ['code', 'string', 'label' => '标识码'],
            ['name', 'string', 'label' => '配置名称'],
            ['is_open', 'in', 'label' => '开放状态', 'range' => array_keys(TLabelEnable::enableLabels())],
        ]);
        // 获取数据
        $res = $this->service->search($params);
        //结果返回渲染
        return $this->success($res);
    }

    /**
     * 编辑替换配置
     *
     * @return array
     * @throws Exception
     */
    public function actionEdit()
    {
        $modelClass = get_class($this->newModel);
        // 参数校验并获取校验字段值
        $params = $this->validateParams([
            [['code', 'name', 'description', 'sort_order', 'is_open'], 'required'],
            [['code', 'name', 'description', 'content'], 'string'],
            ['code', 'label' => '标识码', 'exist', 'targetClass' => $modelClass, 'targetAttribute' => ['code', 'name']],
            [
                'name', 'unique', 'label' => '配置名称', 'targetClass' => $modelClass, 'filter' => ['!=', 'code', $this->getParam('code')]
            ],
            [['is_open', 'sort_order'], 'integer'],
            ['is_open', 'in', 'label' => '开放状态', 'range' => array_keys(TLabelYesNo::yesNoLabels())],
            ['replace_fields', JsonValidator::class, 'label' => '字段集'],
            ['sort_order', 'safe', 'label' => '排序'],
            ['content', 'safe', 'label' => '模板'],
            ['description', 'safe', 'label' => '描述'],
        ]);
        // 处理数据
        $res = $this->service->edit($params);
        //结果返回渲染
        return $this->success($res);
    }

    /**
     * 替换配置详情
     *
     * @return array
     * @throws Exception
     */
    public function actionView()
    {
        // 参数校验并获取
        $code = $this->getModelKey();
        // 获取数据
        $res = $this->service->view($code);
        //结果返回渲染
        return $this->success($res);
    }

    /**
     * 获取模型关键字
     * @return array|mixed|string|null
     * @throws Exception
     */
    protected function getModelKey()
    {
        // 参数校验并获取校验字段值
        return $this->validateParams([
            ['code', 'required'],
            ['code', 'string', 'label' => '标识码'],
        ])['code'];
    }
}