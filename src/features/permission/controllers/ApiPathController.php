<?php
/**
 * @link        http://www.phpcorner.net
 * @author      qingbing<780042175@qq.com>
 * @copyright   Chengdu Qb Technology Co., Ltd.
 */

namespace YiiHelper\features\permission\controllers;


use Exception;
use YiiHelper\abstracts\RestController;
use YiiHelper\features\permission\services\ApiPathService;
use YiiHelper\features\permission\services\interfaces\IApiPathService;
use YiiHelper\models\permission\PermissionApi;
use YiiHelper\validators\JsonValidator;
use Zf\Helper\Traits\Models\TLabelEnable;
use Zf\Helper\Traits\Models\TLabelYesNo;

/**
 * 控制器 : api后端路径管理
 *
 * Class ApiPathController
 * @package YiiHelper\features\permission\controllers
 *
 * @property-read IApiPathService $service
 */
class ApiPathController extends RestController
{
    public $serviceInterface = IApiPathService::class;
    public $serviceClass     = ApiPathService::class;

    /**
     * api后端路径列表
     *
     * @return array
     * @throws Exception
     */
    public function actionList()
    {
        // 参数验证和获取
        $params = $this->validateParams([
            ['path', 'string', 'label' => 'API路径'],
            ['remark', 'string', 'label' => '路径描述'],
            ['is_public', 'in', 'label' => '公共路径', 'range' => array_keys(TLabelYesNo::isLabels())],
            ['is_enable', 'in', 'label' => '启用状态', 'range' => array_keys(TLabelEnable::enableLabels())],
        ], null, true);

        // 业务处理
        $res = $this->service->list($params);
        // 渲染结果
        return $this->success($res, 'api后端路径列表');
    }

    /**
     * 添加api后端路径
     *
     * @return array
     * @throws Exception
     */
    public function actionAdd()
    {
        // 参数验证和获取
        $params = $this->validateParams([
            [['path', 'remark'], 'required'],
            ['path', 'unique', 'label' => 'API路径', 'targetClass' => PermissionApi::class, 'targetAttribute' => 'path'],
            ['remark', 'unique', 'label' => '路径描述', 'targetClass' => PermissionApi::class, 'targetAttribute' => 'remark'],
            ['exts', JsonValidator::class, 'label' => '扩展信息'],
            ['is_public', 'in', 'label' => '公共路径', 'range' => array_keys(TLabelYesNo::isLabels())],
            ['is_enable', 'in', 'label' => '启用状态', 'range' => array_keys(TLabelEnable::enableLabels())],
        ], null);

        // 业务处理
        $res = $this->service->add($params);
        // 渲染结果
        return $this->success($res, '添加api后端路径');
    }

    /**
     * 编辑api后端路径
     *
     * @return array
     * @throws Exception
     */
    public function actionEdit()
    {
        // 参数验证和获取
        $id     = $this->getParam('id');
        $params = $this->validateParams([
            [['id', 'remark'], 'required'],
            ['id', 'exist', 'label' => '路径ID', 'targetClass' => PermissionApi::class, 'targetAttribute' => 'id'],
            ['remark', 'unique', 'label' => '路径描述', 'targetClass' => PermissionApi::class, 'targetAttribute' => 'remark', 'filter' => ['!=', 'id', $id]],
            ['exts', JsonValidator::class, 'label' => '扩展信息'],
            ['is_public', 'in', 'label' => '公共路径', 'range' => array_keys(TLabelYesNo::isLabels())],
            ['is_enable', 'in', 'label' => '启用状态', 'range' => array_keys(TLabelEnable::enableLabels())],
        ], null);

        // 业务处理
        $res = $this->service->edit($params);
        // 渲染结果
        return $this->success($res, '编辑api后端路径');
    }

    /**
     * 删除api后端路径
     *
     * @return array
     * @throws Exception
     */
    public function actionDel()
    {
        // 参数验证和获取
        $params = $this->validateParams([
            ['id', 'required'],
            ['id', 'exist', 'label' => '路径ID', 'targetClass' => PermissionApi::class, 'targetAttribute' => 'id'],
        ], null);

        // 业务处理
        $res = $this->service->del($params);
        // 渲染结果
        return $this->success($res, '删除api后端路径');
    }

    /**
     * 查看api后端路径详情
     *
     * @return array
     * @throws Exception
     */
    public function actionView()
    {
        // 参数验证和获取
        $params = $this->validateParams([
            ['id', 'required'],
            ['id', 'exist', 'label' => '路径ID', 'targetClass' => PermissionApi::class, 'targetAttribute' => 'id'],
        ], null);

        // 业务处理
        $res = $this->service->view($params);
        // 渲染结果
        return $this->success($res, '查看api后端路径详情');
    }
}