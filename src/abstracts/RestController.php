<?php
/**
 * @link        http://www.phpcorner.net
 * @author      qingbing<780042175@qq.com>
 * @copyright   Chengdu Qb Technology Co., Ltd.
 */

namespace YiiHelper\abstracts;

use Exception;
use yii\base\Event;
use yii\helpers\ArrayHelper;
use yii\rest\Controller;
use yii\web\Response;
use YiiHelper\traits\TResponse;
use YiiHelper\traits\TValidator;
use Zf\Helper\ReqHelper;

/**
 * web 基类
 * Class WebController
 * @package YiiHelper\abstracts
 */
abstract class RestController extends Controller
{
    // 使用响应片段
    use TResponse;
    // 使用参数验证片段
    use TValidator;
    /**
     * @var bool 采用默认的响应格式
     */
    protected $useDefaultResponse = true;

    /**
     * 控制器初始化后执行
     */
    public function init()
    {
        parent::init();
        if ($this->useDefaultResponse) {
            // 在发送后端数据响应前规范响应格式
            \Yii::$app->getResponse()->on(Response::EVENT_BEFORE_SEND, function (Event $event) {
                $response = $event->sender;
                /* @var Response $response */
                if (!in_array($response->getStatusCode(), [200, 302])) {
                    // error
                    $response->format = Response::FORMAT_JSON;
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
                // 在响应中添加 trace-id
                $response->getHeaders()
                    ->add('x-trace-id', ReqHelper::getTraceId());
            });
        }
    }

    /**
     * 获取参数
     *
     * @param string $key
     * @param null|mixed $default
     * @return array|mixed|string|null
     * @throws Exception
     */
    protected function getParam(string $key, $default = null)
    {
        $subKey = '';
        if (false !== strpos($key, '.')) {
            list($key, $subKey) = explode('.', $key, 2);
        }
        $val = $this->request->post($key);
        if (null === $val) {
            $val = $this->request->get($key, $default);
        }
        if (is_string($val)) {
            $val = trim($val);
        }
        if (!empty($subKey)) {
            if (!is_array($val)) {
                return $default;
            }
            return ArrayHelper::getValue($val, $subKey, $default);
        }
        return $val;
    }

    /**
     * 分页参数校验
     *
     * @return array
     * @throws Exception
     */
    protected function pageParams()
    {
        // 参数校验
        $this->validateParams([
            ['pageNo', 'integer', 'min' => 1],
            ['pageSize', 'integer', 'min' => 1],
        ], [
            'pageNo'   => '页码',
            'pageSize' => '分页条数',
        ]);
        // 参数获取并返回
        return [
            'pageNo'   => $this->getParam('pageNo', 1),
            'pageSize' => $this->getParam('pageSize', 10),
        ];
    }
}