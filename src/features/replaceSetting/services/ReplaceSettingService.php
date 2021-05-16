<?php
/**
 * @link        http://www.phpcorner.net
 * @author      qingbing<780042175@qq.com>
 * @copyright   Chengdu Qb Technology Co., Ltd.
 */

namespace YiiHelper\features\replaceSetting\services;


use YiiHelper\abstracts\Service;
use YiiHelper\features\replaceSetting\services\interfaces\IReplaceSettingService;
use YiiHelper\helpers\Pager;
use YiiHelper\models\replaceSetting\ReplaceSetting;
use Zf\Helper\Exceptions\BusinessException;

/**
 * 服务类 ： 替换配置
 *
 * Class ReplaceSettingService
 * @package YiiHelper\features\replaceSetting\services
 */
class ReplaceSettingService extends Service implements IReplaceSettingService
{
    /**
     * 替换配置列表
     *
     * @param array|null $params
     * @return array
     */
    public function list(array $params = []): array
    {
        $query = ReplaceSetting::find()
            ->orderBy('sort_order ASC');
        $this->attributeWhere($query, $params, 'is_open');
        $this->likeWhere($query, $params, ['code', 'name']);
        return Pager::getInstance()->pagination($query, $params['pageNo'], $params['pageSize']);
    }

    /**
     * 添加替换配置
     *
     * @param array $params
     * @return bool
     * @throws BusinessException
     */
    public function add(array $params): bool
    {
        throw new BusinessException("不支持的操作");
    }

    /**
     * 编辑替换配置
     *
     * @param array $params
     * @return bool
     * @throws BusinessException
     * @throws \yii\db\Exception
     */
    public function edit(array $params): bool
    {
        $model = $this->getModel($params);
        unset($params['code']);
        $model->setAttributes($params);
        return $model->saveOrException();
    }

    /**
     * 删除替换配置
     *
     * @param array $params
     * @return bool
     * @throws BusinessException
     */
    public function del(array $params): bool
    {
        throw new BusinessException("不支持的操作");
    }

    /**
     * 查看详情
     *
     * @param array $params
     * @return mixed|ReplaceSetting
     * @throws BusinessException
     */
    public function view(array $params)
    {
        return $this->getModel($params);
    }

    /**
     * 获取当前操作替换配置
     *
     * @param $params
     * @return ReplaceSetting
     * @throws BusinessException
     */
    protected function getModel(array $params): ReplaceSetting
    {
        $model = ReplaceSetting::findOne([
            'code' => $params['code'] ?? null
        ]);
        if (null === $model) {
            throw new BusinessException("表头不存在");
        }
        return $model;
    }
}