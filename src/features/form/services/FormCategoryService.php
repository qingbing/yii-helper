<?php
/**
 * @link        http://www.phpcorner.net
 * @author      qingbing<780042175@qq.com>
 * @copyright   Chengdu Qb Technology Co., Ltd.
 */

namespace YiiHelper\features\form\services;


use YiiHelper\abstracts\Service;
use YiiHelper\features\form\services\interfaces\IFormCategoryService;
use YiiHelper\helpers\Pager;
use YiiHelper\models\form\FormCategory;
use Zf\Helper\Exceptions\BusinessException;

/**
 * 服务 ： 表单选项管理
 *
 * Class FormCategoryService
 * @package YiiHelper\features\from\services
 */
class FormCategoryService extends Service implements IFormCategoryService
{
    /**
     * 表单列表
     *
     * @param array|null $params
     * @return array
     */
    public function list(array $params = []): array
    {
        $query = FormCategory::find()
            ->orderBy('sort_order ASC');
        // 等于查询
        $this->attributeWhere($query, $params, ['is_setting', 'is_open', 'is_enable']);
        // like 查询
        $this->likeWhere($query, $params, ['key', 'name']);
        return Pager::getInstance()->pagination($query, $params['pageNo'], $params['pageSize']);
    }

    /**
     * 添加表单
     *
     * @param array $params
     * @return bool
     * @throws \yii\db\Exception
     */
    public function add(array $params): bool
    {
        $model = new FormCategory();
        $model->setFilterAttributes($params);
        return $model->saveOrException();
    }

    /**
     * 编辑表单
     *
     * @param array $params
     * @return bool
     * @throws BusinessException
     * @throws \yii\db\Exception
     */
    public function edit(array $params): bool
    {
        $model = $this->getModel($params);
        unset($params['key']);
        $model->setFilterAttributes($params);
        return $model->saveOrException();
    }

    /**
     * 删除表单
     *
     * @param array $params
     * @return bool
     * @throws BusinessException
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function del(array $params): bool
    {
        $model = $this->getModel($params);
        return $model->delete();
    }

    /**
     * 查看表单详情
     *
     * @param array $params
     * @return mixed|FormCategory
     * @throws BusinessException
     */
    public function view(array $params)
    {
        return $this->getModel($params);
    }

    /**
     * 获取当前操作模型
     *
     * @param array $params
     * @return FormCategory
     * @throws BusinessException
     */
    protected function getModel(array $params): FormCategory
    {
        $model = FormCategory::findOne([
            'key' => $params['key'] ?? null
        ]);
        if (null === $model) {
            throw new BusinessException("表单不存在");
        }
        return $model;
    }
}