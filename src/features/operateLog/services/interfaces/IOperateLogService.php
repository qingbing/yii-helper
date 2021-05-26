<?php
/**
 * @link        http://www.phpcorner.net
 * @author      qingbing<780042175@qq.com>
 * @copyright   Chengdu Qb Technology Co., Ltd.
 */

namespace YiiHelper\features\operateLog\services\interfaces;


use YiiHelper\services\interfaces\IService;

/**
 * 接口 ： 操作日志
 *
 * Interface IOperateLogService
 * @package YiiHelper\features\operateLog\services\interfaces
 */
interface IOperateLogService extends IService
{
    /**
     * 操作日志列表
     *
     * @param array|null $params
     * @return array
     */
    public function list(array $params = []): array;

    /**
     * 查看操作日志详情
     *
     * @param array $params
     * @return mixed
     */
    public function view(array $params);
}