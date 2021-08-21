<?php
/**
 * @link        http://www.phpcorner.net
 * @author      qingbing<780042175@qq.com>
 * @copyright   Chengdu Qb Technology Co., Ltd.
 */

namespace YiiHelper\features\interfaceManager\services;


use yii\db\Query;
use YiiHelper\abstracts\Service;
use YiiHelper\features\interfaceManager\services\interfaces\IInterfaceRouteLogService;
use YiiHelper\helpers\Pager;
use YiiHelper\models\interfaceManager\InterfaceRouteLogs;
use YiiHelper\models\interfaceManager\Interfaces;

/**
 * 服务 ： 路由日志查询
 *
 * Class InterfaceRouteLogService
 * @package YiiHelper\features\interfaceManager\services
 */
class InterfaceRouteLogService extends Service implements IInterfaceRouteLogService
{
    /**
     * 路由访问日志列表
     *
     * @param array $params
     * @return array
     * @throws \yii\base\InvalidConfigException
     */
    public function list(array $params = []): array
    {
        $query = (new Query())
            ->from(InterfaceRouteLogs::tableName() . ' AS ral')
            ->leftJoin(Interfaces::tableName() . ' AS i', 'i.id=ral.interface_id')
            ->orderBy('ral.id DESC')
            ->select([
                'ral.*',
                'i.system_alias',
                'i.uri_path',
                'i.alias',
                'i.name',
                'i.type',
                'i.is_operate',
            ])
            ->andFilterWhere(['=', 'i.system_alias', $params['system_alias']])
            ->andFilterWhere(['=', 'i.interface_type', $params['interface_type']])
            ->andFilterWhere(['=', 'i.is_operate', $params['is_operate']])
            ->andFilterWhere(['=', 'i.id', $params['interface_id']])
            ->andFilterWhere(['=', 'ral.trace_id', $params['trace_id']])
            ->andFilterWhere(['=', 'ral.method', $params['method']])
            ->andFilterWhere(['=', 'ral.is_success', $params['is_success']])
            ->andFilterWhere(['=', 'ral.ip', $params['ip']])
            ->andFilterWhere(['=', 'ral.uid', $params['uid']])
            ->andFilterWhere(['=', 'ral.keyword', $params['keyword']])
            ->andFilterWhere(['like', 'ral.message', $params['message']]);
        // 开始时间
        if (!empty($params['start_at'])) {
            $query->andWhere(['>=', 'ral.created_at', $params['start_at']]);
        }
        // 结束时间
        if (!empty($params['end_at'])) {
            $query->andWhere(['<=', 'ral.created_at', $params['end_at']]);
        }
        return Pager::getInstance()->pagination($query, $params['pageNo'], $params['pageSize']);
    }

    /**
     * 查看路由访问日志详情
     *
     * @param array $params
     * @return array|bool|mixed
     * @throws \yii\base\InvalidConfigException
     */
    public function view(array $params)
    {
        $query = (new Query())
            ->from(InterfaceRouteLogs::tableName() . ' AS ral')
            ->leftJoin(Interfaces::tableName() . ' AS i', 'i.id=ral.interface_id')
            ->orderBy('ral.id DESC')
            ->select([
                'ral.*',
                'i.system_alias',
                'i.uri_path',
                'i.alias',
                'i.name',
                'i.type',
                'i.is_operate',
            ])
            ->andWhere(['=', 'ral.id', $params['id']]);
        return $query->one();
    }
}