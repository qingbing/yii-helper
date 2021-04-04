<?php
/**
 * @link        http://www.phpcorner.net
 * @author      qingbing<780042175@qq.com>
 * @copyright   Chengdu Qb Technology Co., Ltd.
 */

namespace YiiHelper\services;


use YiiHelper\helpers\Pager;
use YiiHelper\models\abstracts\AOperateLog;
use Zf\Helper\Exceptions\BusinessException;

/**
 * 操作日志服务
 *
 * Class OperateLogService
 * @package YiiHelper\services
 *
 * @property-write AOperateLog $model
 */
class OperateLogService
{
    /**
     * @var AOperateLog
     */
    protected $model;

    /**
     * 设置日志模型
     *
     * @param AOperateLog $model
     * @return $this
     */
    public function setModel(AOperateLog $model)
    {
        $this->model = $model;
        return $this;
    }

    /**
     * 获取日志类型列表
     *
     * @return array
     */
    public function types()
    {
        return $this->model::types();
    }

    /**
     * 操作日志列表查询
     *
     * @param array $params
     * @return array
     */
    public function search(array $params = []): array
    {
        // 创建查询器
        $query = $this->model::find()
            ->orderBy('id DESC');
        // 日志ID
        if (!empty($params['id'])) {
            $query->andWhere(['=', 'id', $params['id']]);
        }
        // 链路ID
        if (!empty($params['trace_id'])) {
            $query->andWhere(['=', 'trace_id', $params['trace_id']]);
        }
        // 日志类型
        if (!empty($params['type'])) {
            $query->andWhere(['=', 'type', $params['type']]);
        }
        // 用户ID
        if (!empty($params['uid'])) {
            $query->andWhere(['=', 'uid', $params['uid']]);
        }
        // 用户名
        if (!empty($params['username'])) {
            $query->andWhere(['=', 'username', $params['username']]);
        }
        // 关键字
        if (!empty($params['keyword'])) {
            $query->andWhere(['like', 'keyword', "{$params['keyword']}"]);
        }
        // 关键字
        if (!empty($params['message'])) {
            $query->andWhere(['like', 'message', "{$params['message']}"]);
        }

        // 查询并分页
        return Pager::getInstance()->pagination($query, $params['pageNo'], $params['pageSize']);
    }

    /**
     * 获取日志详情
     *
     * @param mixed $id
     * @return AOperateLog|null
     * @throws BusinessException
     */
    public function view($id)
    {
        $res = $this->model::findOne([
            'id' => $id,
        ]);
        if (null === $res) {
            throw new BusinessException('不存在的日志信息');
        }
        return $res;
    }
}