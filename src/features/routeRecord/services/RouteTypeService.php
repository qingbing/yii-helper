<?php
/**
 * @link        http://www.phpcorner.net
 * @author      qingbing<780042175@qq.com>
 * @copyright   Chengdu Qb Technology Co., Ltd.
 */

namespace YiiHelper\features\routeRecord\services;


use YiiHelper\abstracts\Service;
use YiiHelper\features\routeRecord\services\interfaces\IRouteTypeService;
use YiiHelper\helpers\Pager;
use YiiHelper\models\interfaceLogs\InterfaceSystem;
use YiiHelper\models\routeLog\RouteType;
use Zf\Helper\Exceptions\BusinessException;

/**
 * 服务 ： 路由类型
 *
 * Class RouteTypeService
 * @package YiiHelper\features\routeRecord\services
 */
class RouteTypeService extends Service implements IRouteTypeService
{
    /**
     * 获取系统
     *
     * @param array $params
     * @return array
     */
    public function getSystemType(array $params): array
    {
        $query = InterfaceSystem::find()
            ->select(['alias', 'name'])
            ->orderBy('sort_order DESC');
        if (!empty($params['keyword'])) {
            $query->andWhere(['like', 'name', $params['keyword']]);
        }
        $res = $query->asArray()
            ->all();
        $R   = [];
        foreach ($res as $re) {
            array_push($R, [
                'name'  => $re['name'],
                'value' => $re['alias'],
            ]);
        }
        return $R;
    }

    /**
     * 路由类型列表
     *
     * @param array|null $params
     * @return array
     */
    public function list(array $params = []): array
    {
        $query = RouteType::find()
            ->orderBy('sort_order ASC');
        $this->attributeWhere($query, $params, 'system_alias');
        $this->likeWhere($query, $params, ['route_type', 'type_name']);
        return Pager::getInstance()->pagination($query, $params['pageNo'], $params['pageSize']);
    }

    /**
     * 添加路由类型
     *
     * @param array $params
     * @return bool
     * @throws \yii\db\Exception
     */
    public function add(array $params): bool
    {
        $model = new RouteType();
        $model->setAttributes($params);
        return $model->saveOrException();
    }

    /**
     * 编辑路由类型
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
        $model->setAttributes($params);
        return $model->saveOrException();
    }

    /**
     * 删除路由类型
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
     * 查看路由类型详情
     *
     * @param array $params
     * @return mixed|RouteType|null
     * @throws BusinessException
     */
    public function view(array $params)
    {
        return $this->getModel($params);
    }

    /**
     * 获取路由类型模型
     *
     * @param array $params
     * @return RouteType|null
     * @throws BusinessException
     */
    protected function getModel(array $params)
    {
        $model = RouteType::findOne([
            'id' => $params['id'] ?? null,
        ]);
        if (null === $model) {
            throw new BusinessException("路由类型不存在");
        }
        return $model;
    }
}