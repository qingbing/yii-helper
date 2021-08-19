<?php
/**
 * @link        http://www.phpcorner.net
 * @author      qingbing<780042175@qq.com>
 * @copyright   Chengdu Qb Technology Co., Ltd.
 */

namespace YiiHelper\components\routeManager;


use yii\base\Event;
use yii\web\Response;

/**
 * 接口 ： 路由日志自定义
 *
 * Interface IRouteLog
 * @package YiiHelper\components\interfaceManager\assist
 */
interface IRouteLog
{
    /**
     * yii.web.EVENT_BEFORE_REQUEST 时执行
     *
     * @param Event $event
     */
    public function beforeRequest(Event $event);

    /**
     * yii.web.response.EVENT_AFTER_SEND 时执行
     *
     * @param Response $response
     * @return array
     */
    public function afterResponse(Response $response): array;
}