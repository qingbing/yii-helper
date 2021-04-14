<?php
/**
 * @link        http://www.phpcorner.net
 * @author      qingbing<780042175@qq.com>
 * @copyright   Chengdu Qb Technology Co., Ltd.
 */

namespace YiiHelper\components;


use Yii;
use yii\web\HttpException;
use yii\web\Response;
use Zf\Helper\Exceptions\CustomException;

/**
 * 重构错误控制句柄
 *
 * Class ErrorHandler
 * @package YiiHelper\components
 */
class ErrorHandler extends \yii\web\ErrorHandler
{
    protected function renderException($exception)
    {
        // 获取 response 组件
        if (Yii::$app->has('response')) {
            $response = Yii::$app->getResponse();
            // 重置 response
            $response->isSent  = false;
            $response->stream  = null;
            $response->data    = null;
            $response->content = null;
        } else {
            $response = new Response();
        }
        // 异常信息处理
        $isCustomException = $exception instanceof CustomException;
        if ($isCustomException && null !== $this->errorAction) {
            $res = Yii::$app->runAction($this->errorAction);
            if ($res instanceof Response) {
                $response = $res;
            } else {
                $response->data = $res;
            }
        } elseif (Response::FORMAT_RAW === $response->format) {
            $response->data = static::convertExceptionToString($exception);
        } else {
            $response->data = $this->convertExceptionToArray($exception);
        }
        // 设置 http 状态返回码
        if ($exception instanceof HttpException) {
            $response->setStatusCode($exception->statusCode);
        } else if ($isCustomException) {
            $response->setStatusCode(200);
        } else {
            $response->setStatusCode(500);
        }

        // 构造响应数据
        $data = [];
        if (YII_DEBUG) {
            $data['type']    = get_class($exception);
            $data['file']    = $exception->getFile();
            $data['Line']    = $exception->getLine();
            $data['Code']    = $exception->getCode();
            $data['message'] = $exception->getMessage();
            $data['Trace']   = $exception->getTraceAsString();
        }
        $response->data = \YiiHelper\helpers\Response::getInstance()
            ->setMsg($exception->getMessage())
            ->setCode($exception->getCode())
            ->output($data);

        // 发送响应
        $response->send();

        // 记录错误或异常日志
        Yii::error([
            'type'    => get_class($exception),
            'file'    => $exception->getFile(),
            'Code'    => $exception->getCode(),
            'Line'    => $exception->getLine(),
            'message' => $exception->getMessage(),
            'Trace'   => $exception->getTraceAsString(),
        ], ($exception instanceof \Error || $exception instanceof \ErrorException)
            ? 'custom.error' : 'custom.exception');
    }
}