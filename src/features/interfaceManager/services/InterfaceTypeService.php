<?php
/**
 * @link        http://www.phpcorner.net
 * @author      qingbing<780042175@qq.com>
 * @copyright   Chengdu Qb Technology Co., Ltd.
 */

namespace YiiHelper\features\interfaceManager\services;


use YiiHelper\abstracts\Service;
use YiiHelper\features\interfaceManager\services\interfaces\IInterfaceTypeService;
use YiiHelper\helpers\Pager;
use YiiHelper\models\interfaceManager\InterfaceType;
use Zf\Helper\Exceptions\BusinessException;

/**
 * 服务 ： 接口系统类型
 *
 * Class SystemTypeService
 * @package YiiHelper\features\interfaceManager\services
 */
class InterfaceTypeService extends Service implements IInterfaceTypeService
{
    /**
     * 接口类型列表
     *
     * @param array|null $params
     * @return array
     */
    public function list(array $params = []): array
    {
        $query = InterfaceType::find()
            ->orderBy('sort_order ASC');
        // 等于查询
        $this->attributeWhere($query, $params, 'system_alias');
        // like 查询
        $this->likeWhere($query, $params, ['type', 'type_name']);
        return Pager::getInstance()->pagination($query, $params['pageNo'], $params['pageSize']);
    }

    /**
     * 添加接口类型
     *
     * @param array $params
     * @return bool
     * @throws \yii\db\Exception
     */
    public function add(array $params): bool
    {
        $model          = new InterfaceType();
        $params['type'] = "{$params['system_alias']}-{$params['type']}";
        $model->setFilterAttributes($params);
        return $model->saveOrException();
    }

    /**
     * 编辑接口类型
     *
     * @param array $params
     * @return bool
     * @throws BusinessException
     * @throws \yii\db\Exception
     */
    public function edit(array $params): bool
    {
        $model = $this->getModel($params);
        unset($params['id'], $params['system_alias']);
        $model->setFilterAttributes($params);
        return $model->saveOrException();
    }

    /**
     * 删除接口类型
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
     * 查看接口类型详情
     *
     * @param array $params
     * @return mixed|InterfaceType|null
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
     * @return InterfaceType|null
     * @throws BusinessException
     */
    protected function getModel(array $params)
    {
        $model = InterfaceType::findOne([
            'id' => $params['id'] ?? null,
        ]);
        if (null === $model) {
            throw new BusinessException("接口类型不存在");
        }
        return $model;
    }
}