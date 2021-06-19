<?php
/**
 * @link        http://www.phpcorner.net
 * @author      qingbing<780042175@qq.com>
 * @copyright   Chengdu Qb Technology Co., Ltd.
 */

namespace YiiHelper\features\interfaceManager\services;


use YiiHelper\abstracts\Service;
use YiiHelper\features\interfaceManager\services\interfaces\IInterfaceSystemService;
use YiiHelper\helpers\Pager;
use YiiHelper\models\interfaceManager\InterfaceSystems;
use Zf\Helper\Exceptions\BusinessException;

/**
 * 服务 ： 系统管理
 *
 * Class SystemService
 * @package YiiHelper\features\interfaceManager\services
 */
class InterfaceSystemService extends Service implements IInterfaceSystemService
{
    /**
     * 系统列表
     *
     * @param array|null $params
     * @return array
     */
    public function list(array $params = []): array
    {
        $query = InterfaceSystems::find()
            ->orderBy('sort_order ASC');
        // 等于查询
        $this->attributeWhere($query, $params, [
            'system_alias',
            'is_enable',
            'is_allow_new_interface',
            'is_record_field',
            'is_open_access_log',
            'is_open_validate',
            'is_strict_validate'
        ]);
        // like 查询
        $this->likeWhere($query, $params, 'system_name');
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
        $model = new InterfaceSystems();
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
     * @return mixed|InterfaceSystems
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
     * @return InterfaceSystems
     * @throws BusinessException
     */
    protected function getModel(array $params): InterfaceSystems
    {
        $model = InterfaceSystems::findOne([
            'id' => $params['id'] ?? null
        ]);
        if (null === $model) {
            throw new BusinessException("系统不存在");
        }
        return $model;
    }
}