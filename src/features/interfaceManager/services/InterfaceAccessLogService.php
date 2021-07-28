<?php
/**
 * @link        http://www.phpcorner.net
 * @author      qingbing<780042175@qq.com>
 * @copyright   Chengdu Qb Technology Co., Ltd.
 */

namespace YiiHelper\features\interfaceManager\services;


use yii\db\Query;
use YiiHelper\abstracts\Service;
use YiiHelper\features\interfaceManager\services\interfaces\IInterfaceAccessLogService;
use YiiHelper\helpers\Pager;
use YiiHelper\models\interfaceManager\InterfaceAccessLogs;
use YiiHelper\models\interfaceManager\Interfaces;

/**
 * 服务 ： 接口访问日志
 *
 * Class InterfaceAccessLogService
 * @package YiiHelper\features\interfaceManager\services
 */
class InterfaceAccessLogService extends Service implements IInterfaceAccessLogService
{
    /**
     * 接口访问日志列表
     *
     * @param array $params
     * @return array
     * @throws \yii\base\InvalidConfigException
     */
    public function list(array $params = []): array
    {
        $query = (\Yii::createObject(Query::class))
            ->from(InterfaceAccessLogs::tableName() . ' AS ial')
            ->leftJoin(Interfaces::tableName() . ' AS i', 'i.id=ial.interface_id')
            ->orderBy('ial.id DESC')
            ->select([
                'ial.*',
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
            ->andFilterWhere(['=', 'ial.trace_id', $params['trace_id']])
            ->andFilterWhere(['=', 'ial.method', $params['method']])
            ->andFilterWhere(['=', 'ial.is_intercept', $params['is_intercept']])
            ->andFilterWhere(['=', 'ial.is_success', $params['is_success']])
            ->andFilterWhere(['=', 'ial.ip', $params['ip']])
            ->andFilterWhere(['=', 'ial.uid', $params['uid']])
            ->andFilterWhere(['like', 'ial.message', $params['message']]);
        // 开始时间
        if (!empty($params['start_at'])) {
            $query->andWhere(['>=', 'ial.created_at', $params['start_at']]);
        }
        // 结束时间
        if (!empty($params['end_at'])) {
            $query->andWhere(['<=', 'ial.created_at', $params['end_at']]);
        }
        return Pager::getInstance()->pagination($query, $params['pageNo'], $params['pageSize']);
    }

    /**
     * 查看接口访问日志详情
     *
     * @param array $params
     * @return array|bool|mixed
     * @throws \yii\base\InvalidConfigException
     */
    public function view(array $params)
    {
        $query = (\Yii::createObject(Query::class))
            ->from(InterfaceAccessLogs::tableName() . ' AS ial')
            ->leftJoin(Interfaces::tableName() . ' AS i', 'i.id=ial.interface_id')
            ->orderBy('ial.id DESC')
            ->select([
                'ial.*',
                'i.system_alias',
                'i.uri_path',
                'i.alias',
                'i.name',
                'i.type',
                'i.is_operate',
            ])
            ->andWhere(['=', 'ial.id', $params['id']]);
        return $query->one();
    }
}