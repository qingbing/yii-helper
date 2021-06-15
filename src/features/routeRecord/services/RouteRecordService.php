<?php
/**
 * @link        http://www.phpcorner.net
 * @author      qingbing<780042175@qq.com>
 * @copyright   Chengdu Qb Technology Co., Ltd.
 */

namespace YiiHelper\features\routeRecord\services;


use yii\db\Expression;
use yii\db\Query;
use YiiHelper\abstracts\Service;
use YiiHelper\features\routeRecord\services\interfaces\IRouteRecordService;
use YiiHelper\helpers\Pager;
use YiiHelper\models\routeLog\RouteRecord;
use YiiHelper\models\routeLog\RouteRecordConfig;
use YiiHelper\models\routeLog\RouteType;
use Zf\Helper\Exceptions\BusinessException;

/**
 * 服务 ： 路由记录
 *
 * Class RouteRecordService
 * @package YiiHelper\features\routeRecord\services
 */
class RouteRecordService extends Service implements IRouteRecordService
{
    /**
     * 路由记录列表
     *
     * @param array|null $params
     * @return array
     */
    public function list(array $params = []): array
    {
        $query = (new Query())
            ->from(RouteRecord::tableName() . ' AS r')
            ->select([
                'r.*',
                'rt.type_name',
                new Expression("IFNULL(rc.is_logging,0) AS is_logging"),
                new Expression("IFNULL(rc.logging_message,'') AS logging_message"),
                new Expression("IFNULL(rc.logging_key_fields,'') AS logging_key_fields"),
                new Expression("IFNULL(rc.is_mocking,0) AS is_mocking"),
                new Expression("IFNULL(rc.mocking_response,'') AS mocking_response"),
            ])
            ->leftJoin(RouteRecordConfig::tableName() . ' AS rc', 'rc.system_alias=r.system_alias AND rc.route=r.route')
            ->leftJoin(RouteType::tableName() . ' AS rt', 'rt.system_alias=r.system_alias AND rt.route_type=r.route_type')
            ->andFilterWhere(['=', 'r.system_alias', $params['system_alias']])
            ->andFilterWhere(['=', 'r.route_type', $params['route_type']])
            ->andFilterWhere(['=', 'r.is_operate', $params['is_operate']])
            ->andFilterWhere(['like', 'r.route', $params['route']])
            ->orderBy('r.id ASC');
        if ('' !== $params['is_logging']) {
            if (1 == $params['is_logging']) {
                $query->andWhere(['=', 'rc.is_logging', $params['is_logging']]);
            } else {
                $query->andWhere([
                    'or',
                    ['=', 'rc.is_logging', 0],
                    'rc.is_logging IS NULL',
                ]);
            }
        }
        if ('' !== $params['is_mocking']) {
            if (1 == $params['is_mocking']) {
                $query->andWhere(['=', 'rc.is_mocking', $params['is_mocking']]);
            } else {
                $query->andWhere([
                    'or',
                    ['=', 'rc.is_mocking', 0],
                    'rc.is_mocking IS NULL',
                ]);
            }
        }
        return Pager::getInstance()->pagination($query, $params['pageNo'], $params['pageSize']);
    }

    /**
     * 添加路由记录
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
     * 编辑路由记录
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
     * 删除路由记录
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
     * 查看路由记录详情
     *
     * @param array $params
     * @return mixed|RouteRecord
     * @throws BusinessException
     */
    public function view(array $params)
    {
        return $this->getModel($params);
    }

    /**
     * 获取当前操作模型
     *
     * @param $params
     * @return RouteRecord
     * @throws BusinessException
     */
    protected function getModel(array $params): RouteRecord
    {
        $model = RouteRecord::findOne([
            'id' => $params['id'] ?? null,
        ]);
        if (null === $model) {
            throw new BusinessException("路由不存在");
        }
        return $model;
    }

    /**
     * 编辑路由配置
     *
     * @param array $params
     * @return bool
     * @throws BusinessException
     * @throws \yii\db\Exception
     */
    public function editRecordConfig(array $params): bool
    {
        $model  = $this->getModel($params);
        $config = $model->recordConfig;
        if (null === $config) {
            $config               = new RouteRecordConfig();
            $config->system_alias = $model->system_alias;
            $config->route        = $model->route;
        }
        $config->setFilterAttributes([
            'is_logging'         => $params['is_logging'],
            'logging_message'    => $params['logging_message'],
            'logging_key_fields' => $params['logging_key_fields'],
            'is_mocking'         => $params['is_mocking'],
            'mocking_response'   => $params['mocking_response'],
        ]);
        return $config->saveOrException();
    }
}