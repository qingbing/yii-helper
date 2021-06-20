<?php
/**
 * @link        http://www.phpcorner.net
 * @author      qingbing<780042175@qq.com>
 * @copyright   Chengdu Qb Technology Co., Ltd.
 */

namespace YiiHelper\features\interfaceManager\services\interfaces;


use YiiHelper\services\interfaces\IService;

/**
 * 接口 ： 接口管理
 *
 * Interface IInterfaceService
 * @package YiiHelper\features\interfaceManager\services\interfaces
 */
interface IInterfaceService extends IService
{
    /**
     * 接口列表
     *
     * @param array|null $params
     * @return array
     */
    public function list(array $params = []): array;

    /**
     * 编辑接口
     *
     * @param array $params
     * @return bool
     */
    public function edit(array $params): bool;

    /**
     * 删除接口
     *
     * @param array $params
     * @return bool
     */
    public function del(array $params): bool;

    /**
     * 查看接口详情
     *
     * @param array $params
     * @return mixed
     */
    public function view(array $params);
}