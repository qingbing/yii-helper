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
use YiiHelper\models\routeLog\RouteLogConfig;
use YiiHelper\models\routeLog\RouteRecord;
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
     * 获取路由类型列表
     *
     * @param array $params
     * @return array
     */
    public function getRouteTypes(array $params): array
    {
        $res = RouteType::find()
            ->select(['route_type', 'type_name'])
            ->andWhere(['system_alias' => $params['system_alias']])
            ->orderBy('sort_order DESC')
            ->asArray()
            ->all();
        $R   = [];
        foreach ($res as $re) {
            $R[$re['route_type']] = $re['type_name'];
        }
        return $R;
    }

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
                new Expression("IFNULL(lc.is_logging,0) AS is_logging")
            ])
            ->leftJoin(RouteLogConfig::tableName() . ' AS lc', 'lc.system_alias=r.system_alias AND lc.route=r.route')
            ->andFilterWhere(['=', 'r.system_alias', $params['system_alias']])
            ->andFilterWhere(['=', 'r.route_type', $params['route_type']])
            ->andFilterWhere(['=', 'r.is_operate', $params['is_operate']])
            ->andFilterWhere(['like', 'r.route', $params['route']])
            ->orderBy('r.sort_order ASC');
        if ('' !== $params['is_logging']) {
            if (1 == $params['is_logging']) {
                $query->andWhere(['=', 'lc.is_logging', $params['is_logging']]);
            } else {
                $query->andWhere([
                    'or',
                    ['=', 'lc.is_logging', 0],
                    'lc.is_logging IS NULL',
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
        $model->setAttributes($params);
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
     * 获取当前操作路由
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
     * 编辑路由日志配置
     *
     * @param array $params
     * @return bool
     * @throws BusinessException
     * @throws \yii\db\Exception
     */
    public function editLogConfig(array $params): bool
    {
        $model  = $this->getModel($params);
        $config = $model->logConfig;
        if (null === $config) {
            $config               = new RouteLogConfig();
            $config->system_alias = $model->system_alias;
            $config->route        = $model->route;
        }
        $config->setAttributes([
            'is_logging' => $params['is_logging'],
            'message'    => $params['message'],
            'key_fields' => $params['key_fields'],
        ]);
        return $config->saveOrException();
    }
}