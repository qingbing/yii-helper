<?php
/**
 * @link        http://www.phpcorner.net
 * @author      qingbing<780042175@qq.com>
 * @copyright   Chengdu Qb Technology Co., Ltd.
 */

namespace YiiHelper\services;


use Exception;
use YiiHelper\abstracts\Service;
use YiiHelper\helpers\Pager;
use YiiHelper\models\abstracts\OperateLog;
use Zf\Helper\Exceptions\BusinessException;

/**
 * 操作日志服务
 *
 * Class OperateLogService
 * @package YiiHelper\services
 *
 * @property-write OperateLog $newModel
 */
class OperateLogService extends Service
{
    /**
     * @var OperateLog
     */
    protected $newModel;

    /**
     * 设置日志模型
     *
     * @param OperateLog $newModel
     * @return $this
     */
    public function setNewModel(OperateLog $newModel)
    {
        $this->newModel = $newModel;
        return $this;
    }

    /**
     * 获取日志类型列表
     *
     * @return array
     */
    public function types()
    {
        return $this->newModel::types();
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
        $query = $this->newModel::find()
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
        if (!empty($params['nickname'])) {
            $query->andWhere(['=', 'nickname', $params['nickname']]);
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
     * @return OperateLog|null
     * @throws Exception
     */
    public function view($id)
    {
        return $this->getModel($id);
    }

    /**
     * 获取替换配置
     *
     * @param int $id
     * @return OperateLog|null
     * @throws Exception
     */
    protected function getModel($id)
    {
        $model = $this->newModel::findOne([
            'id' => $id,
        ]);
        if (null === $model) {
            throw new BusinessException('不存在的日志信息');
        }
        return $model;
    }
}