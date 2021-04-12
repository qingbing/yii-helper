<?php
/**
 * @link        http://www.phpcorner.net
 * @author      qingbing<780042175@qq.com>
 * @copyright   Chengdu Qb Technology Co., Ltd.
 */

namespace YiiHelper\extend;


use yii\base\Event;
use yii\web\Response;

/**
 * Class EventHandler
 * @package YiiHelper\components
 *
 * 执行顺序
 *      beforeRequest
 *      afterRequest
 *      beforeResponseSend
 *      afterResponseSend
 */
class EventHandler
{
    /**
     * 应用请求前调用，配置在 yii/web/application->"on beforeRequest"
     *
     * @param Event $event
     */
    public static function beforeRequest(Event $event)
    {
        $app = $event->sender;
        /* @var Application $app */
        $app->interfaceLog->beforeRequest($app);
    }

    /**
     * 应用请求后调用，配置在 yii/web/application->"on afterRequest"
     *
     * @param Event $event
     */
    public static function afterRequest(Event $event)
    {
        $app = $event->sender;
        /* @var Application $app */
        $request = $app->getRequest();
    }

    /**
     * 应用响应发送前调用，配置在 yii\web\Response->"on beforeSend"
     *
     * @param Event $event
     * @throws \Throwable
     */
    public static function beforeResponseSend(Event $event)
    {
        $response = $event->sender;
        /* @var Response $response */
        if (!in_array($response->getStatusCode(), [200, 302])) {
            // error
            $response->format = Response::FORMAT_JSON;
            if (YII_DEBUG) {
                $response->data = \YiiHelper\helpers\Response::getInstance()
                    ->setMsg($response->statusText)
                    ->setCode($response->statusCode)
                    ->output(null);
            }
        } else if (is_array($response->data)) {
            $response->format = Response::FORMAT_JSON;
        } else if (is_string($response->data)) {
            $response->format = Response::FORMAT_RAW;
        } else if ($response->format !== Response::FORMAT_HTML) {
            $response->format = Response::FORMAT_JSON;
            $response->data   = \YiiHelper\helpers\Response::getInstance()
                ->setMsg($response->statusText)
                ->setCode(0)
                ->output($response->data);
        }
    }

    /**
     * 应用响应发送后调用，配置在 yii\web\Response->"on afterSend"
     *
     * @param Event $event
     * @throws \Throwable
     */
    public static function afterResponseSend(Event $event)
    {
        \Yii::$app->interfaceLog->afterResponseSend($event->sender);
    }
}