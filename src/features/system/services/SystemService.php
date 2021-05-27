<?php
/**
 * @link        http://www.phpcorner.net
 * @author      qingbing<780042175@qq.com>
 * @copyright   Chengdu Qb Technology Co., Ltd.
 */

namespace YiiHelper\features\system\services;


use YiiHelper\abstracts\Service;
use YiiHelper\features\system\services\interfaces\ISystemService;
use YiiHelper\helpers\Pager;
use YiiHelper\models\System;
use Zf\Helper\Exceptions\BusinessException;
use Zf\Helper\Traits\Models\TLabelEnable;
use Zf\Helper\Traits\Models\TLabelYesNo;

/**
 * 服务 ： 系统管理
 *
 * Class SystemService
 * @package YiiHelper\features\system\services
 */
class SystemService extends Service implements ISystemService
{
    /**
     * 系统列表
     *
     * @param array|null $params
     * @return array
     */
    public function list(array $params = []): array
    {
        $query = System::find()
            ->orderBy('sort_order ASC');
        // 等于查询
        $this->attributeWhere($query, $params, ['alias', 'is_enable', 'is_continue', 'is_record_field', 'is_open_log']);
        // like 查询
        $this->likeWhere($query, $params, 'name');
        return Pager::getInstance()->pagination($query, $params['pageNo'], $params['pageSize']);
    }

    /**
     * 添加系统
     *
     * @param array $params
     * @return bool
     * @throws \yii\db\Exception
     */
    public function add(array $params): bool
    {
        $model = new System();
        $model->setFilterAttributes($params);
        return $model->saveOrException();
    }

    /**
     * 编辑系统
     *
     * @param array $params
     * @return bool
     * @throws BusinessException
     * @throws \yii\db\Exception
     */
    public function edit(array $params): bool
    {
        $model = $this->getModel($params);
        unset($params['id']);
        $model->setFilterAttributes($params);
        return $model->saveOrException();
    }

    /**
     * 删除系统
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
     * 查看系统详情
     *
     * @param array $params
     * @return mixed|System
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
     * @return System
     * @throws BusinessException
     */
    protected function getModel(array $params): System
    {
        $model = System::findOne([
            'id' => $params['id'] ?? null
        ]);
        if (null === $model) {
            throw new BusinessException("系统不存在");
        }
        return $model;
    }
}