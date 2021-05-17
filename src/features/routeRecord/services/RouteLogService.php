<?php
/**
 * @link        http://www.phpcorner.net
 * @author      qingbing<780042175@qq.com>
 * @copyright   Chengdu Qb Technology Co., Ltd.
 */

namespace YiiHelper\features\routeRecord\services;


use yii\db\Query;
use YiiHelper\abstracts\Service;
use YiiHelper\features\routeRecord\services\interfaces\IRouteLogService;
use YiiHelper\helpers\Pager;
use YiiHelper\models\routeLog\RouteAccessLog;
use YiiHelper\models\routeLog\RouteLogConfig;
use YiiHelper\models\routeLog\RouteRecord;
use Zf\Helper\Exceptions\BusinessException;

/**
 * 服务 ： 路由日志
 *
 * Class RouteLogService
 * @package YiiHelper\features\routeRecord\services
 */
class RouteLogService extends Service implements IRouteLogService
{
    /**
     * 路由日志列表
     *
     * @param array|null $params
     * @return array
     */
    public function list(array $params = []): array
    {
        $query = (new Query())
            ->select([
                'al.*',
                'r.system_alias', 'r.route', 'r.route_type', 'r.is_operate', 'r.access_times'
            ])
            ->from(RouteAccessLog::tableName() . ' AS al')
            ->leftJoin(RouteLogConfig::tableName() . ' AS lc', 'al.route_log_config_id=lc.id')
            ->leftJoin(RouteRecord::tableName() . ' AS r', 'lc.system_alias=r.system_alias AND lc.route=r.route')
            // 条件组装
            ->andFilterWhere(['=', 'lc.system_alias', $params['system_alias']])
            ->andFilterWhere(['=', 'r.route_type', $params['route_type']])
            ->andFilterWhere(['=', 'al.trace_id', $params['trace_id']])
            ->andFilterWhere(['=', 'r.is_operate', $params['is_operate']])
            ->andFilterWhere(['=', 'al.is_success', $params['is_success']])
            ->andFilterWhere(['=', 'al.ip', $params['ip']])
            ->andFilterWhere(['=', 'al.uid', $params['uid']])
            ->andFilterWhere(['=', 'al.keyword', $params['keyword']])
            ->andFilterWhere(['like', 'al.message', $params['message']])
            ->andFilterWhere(['like', 'lc.route', $params['route']]);
        // 分页查询
        return Pager::getInstance()->pagination($query, $params['pageNo'], $params['pageSize']);
    }

    /**
     * 查看路由日志详情
     *
     * @param array $params
     * @return mixed|RouteAccessLog|null
     * @throws BusinessException
     */
    public function view(array $params)
    {
        return $this->getModel($params);
    }

    /**
     * 获取路由日志模型
     *
     * @param array $params
     * @return RouteAccessLog|null
     * @throws BusinessException
     */
    protected function getModel(array $params)
    {
        $model = RouteAccessLog::findOne([
            'id' => $params['id'] ?? null,
        ]);
        if (null === $model) {
            throw new BusinessException("路由日志不存在");
        }
        return $model;
    }
}