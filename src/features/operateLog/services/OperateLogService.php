<?php
/**
 * @link        http://www.phpcorner.net
 * @author      qingbing<780042175@qq.com>
 * @copyright   Chengdu Qb Technology Co., Ltd.
 */

namespace YiiHelper\features\operateLog\services;


use YiiHelper\abstracts\Service;
use YiiHelper\features\operateLog\services\interfaces\IOperateLogService;
use YiiHelper\helpers\Pager;
use YiiHelper\models\operateLog\OperateLog;
use Zf\Helper\Exceptions\BusinessException;

/**
 * 服务 ： 操作日志
 *
 * Class OperateLogService
 * @package YiiHelper\features\operateLog\services
 */
class OperateLogService extends Service implements IOperateLogService
{
    /**
     * 操作日志列表
     *
     * @param array|null $params
     * @return array
     */
    public function list(array $params = []): array
    {
        $query = OperateLog::find()
            ->orderBy('id DESC');
        $this->attributeWhere($query, $params, ['id', 'trace_id', 'type', 'system_alias', 'uid', 'keyword', 'nickname']);
        $this->likeWhere($query, $params, 'message');
        return Pager::getInstance()->pagination($query, $params['pageNo'], $params['pageSize']);
    }

    /**
     * 操作日志详情
     *
     * @param array $params
     * @return mixed
     */
    public function view(array $params)
    {
        return $this->getModel($params);
    }

    /**
     * 获取当前操作模型
     *
     * @param array $params
     * @return OperateLog
     * @throws BusinessException
     */
    protected function getModel(array $params): OperateLog
    {
        $model = OperateLog::findOne([
            'id' => $params['id'] ?? null
        ]);
        if (null === $model) {
            throw new BusinessException("操作日志不存在");
        }
        return $model;
    }
}