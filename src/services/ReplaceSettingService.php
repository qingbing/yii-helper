<?php
/**
 * @link        http://www.phpcorner.net
 * @author      qingbing<780042175@qq.com>
 * @copyright   Chengdu Qb Technology Co., Ltd.
 */

namespace YiiHelper\services;


use Exception;
use YiiHelper\helpers\Pager;
use YiiHelper\models\ReplaceSetting;
use Zf\Helper\Exceptions\BusinessException;

/**
 * 服务：替换模板
 *
 * Class ReplaceSettingService
 * @package YiiHelper\services
 */
class ReplaceSettingService
{
    /**
     * @var ReplaceSetting
     */
    protected $newModel;

    /**
     * 设置配置模型
     *
     * @param ReplaceSetting $newModel
     * @return $this
     */
    public function setModel(ReplaceSetting $newModel)
    {
        $this->newModel = $newModel;
        return $this;
    }

    /**
     * 操作日志列表查询
     *
     * @param array $params
     * @return array
     */
    public function search(array $params = []): array
    {
        // 创建查询器
        $query = $this->newModel::find()
            ->orderBy('sort_order ASC, code ASC');
        // 标识码
        if (!empty($params['code'])) {
            $query->andWhere(['like', 'code', $params['code']]);
        }
        // 标识名称
        if (!empty($params['name'])) {
            $query->andWhere(['like', 'name', $params['name']]);
        }
        // 标识开放状态
        if (!empty($params['is_open'])) {
            $query->andWhere(['=', 'is_open', $params['is_open']]);
        }
        // 查询并分页
        return Pager::getInstance()->pagination($query, $params['pageNo'], $params['pageSize']);
    }

    /**
     * 编辑替换配置
     *
     * @param array $params
     * @return bool|void
     * @throws Exception
     */
    public function edit(array $params)
    {
        // 获取模型
        $model = $this->getModel($params['code']);
        // 模型赋值
        $model->setAttributes($params);
        // 数据保存
        if ($model->saveOrException()) {
            return true;
        }
    }

    /**
     * 查询替换配置详情
     *
     * @param string $code
     * @return ReplaceSetting|null
     * @throws Exception
     */
    public function view(string $code)
    {
        return $this->getModel($code);
    }

    /**
     * 获取替换配置
     *
     * @param string $code
     * @return ReplaceSetting|null
     * @throws Exception
     */
    protected function getModel(string $code)
    {
        $model = $this->newModel::findOne([
            'code' => $code,
        ]);
        if (null === $model) {
            throw new BusinessException('不存在的替换配置');
        }
        return $model;
    }
}