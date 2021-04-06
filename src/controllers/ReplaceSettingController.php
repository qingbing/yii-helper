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
        // 参数校验
        $this->validateParams([
            [['code', 'name'], 'string'],
            ['is_open', 'in', 'range' => array_keys(TLabelEnable::enableLabels())],
        ], [
            'code'    => '标识码',
            'name'    => '配置名称',
            'is_open' => '开放状态',
        ]);
        // 参数获取
        $params = array_merge($this->pageParams(), [
            'code'    => $this->getParam('code', null),
            'name'    => $this->getParam('name', null),
            'is_open' => $this->getParam('is_open', null),
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
        // 参数校验
        $this->validateParams([
            [['code', 'name', 'description', 'sort_order', 'is_open'], 'required'],
            [['code', 'name', 'description', 'content'], 'string'],
            ['code', 'exist', 'targetClass' => $modelClass, 'targetAttribute' => ['code', 'name']],
            [
                'name', 'unique', 'targetClass' => $modelClass, 'filter' => ['!=', 'code', $this->getParam('code')]
            ],
            [['is_open', 'sort_order'], 'integer'],
            ['is_open', 'in', 'range' => array_keys(TLabelYesNo::yesNoLabels())],
            ['replace_fields', JsonValidator::class],
        ], [
            'code'           => '标识码',
            'name'           => '配置名称',
            'description'    => '描述',
            'content'        => '模板',
            'sort_order'     => '排序',
            'is_open'        => '开放状态',
            'replace_fields' => '字段集',
        ]);

        // 参数获取
        $params = [
            'code'           => $this->getParam('code'),
            'name'           => $this->getParam('name'),
            'description'    => $this->getParam('description'),
            'content'        => $this->getParam('content'),
            'sort_order'     => $this->getParam('sort_order'),
            'is_open'        => $this->getParam('is_open'),
            'replace_fields' => $this->getParam('replace_fields'),
        ];

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
        // 参数校验
        $this->validateParams([
            ['code', 'required'],
            ['code', 'string'],
        ], [
            'code' => '标识码',
        ]);
        return $this->getParam('code');
    }
}