<?php
/**
 * @link        http://www.phpcorner.net
 * @author      qingbing<780042175@qq.com>
 * @copyright   Chengdu Qb Technology Co., Ltd.
 */

namespace YiiHelper\features\accessLog;


use Exception;
use Yii;
use yii\base\Application;
use yii\base\BootstrapInterface;
use yii\base\Event;
use yii\base\InvalidConfigException;
use yii\web\HeaderCollection;
use yii\web\Response;
use YiiHelper\helpers\AppHelper;
use YiiHelper\helpers\Req;
use YiiHelper\models\accessLog\AccessLogs;
use Zf\Helper\DataStore;
use Zf\Helper\Exceptions\CustomException;
use Zf\Helper\ReqHelper;
use Zf\Helper\Timer;

/**
 * bootstrap组件 : 系统接口访问日志
 *
 * Class BootstrapAccessLog
 * @package YiiHelper\features\accessLog\bootstrap
 */
class Bootstrap implements BootstrapInterface
{
    const TIMER_KEY_BEFORE_REQUEST = __CLASS__ . ':beforeRequest';
    /**
     * @var bool 开启访问日志
     */
    public $open = false;
    /**
     * @var array 不被记入参数的 header 头
     */
    public $ignoreHeaders = [
        'x-forwarded-for',
        'x-trace-id',
        'x-system',
    ];
    /**
     * @var string 日志模型类
     */
    public $accessLogModel = AccessLogs::class;
    /**
     * @var yii\web\Request
     */
    protected $request;
    // 系统别名
    protected $systemCode;
    // 请求路由
    protected $realPathInfo;

    /**
     * 获取参数保存的 dataStore 的key
     * @return string
     */
    protected function getStoreKey()
    {
        return __CLASS__ . ":store";
    }

    /**
     * 获取以"x-"透传的header参数
     * @param HeaderCollection|null $headers
     * @return array
     */
    public function getCustomHeaders(?HeaderCollection $headers = null)
    {
        $res     = [];
        $headers = $headers ?? $this->request->getHeaders();
        foreach ($headers as $key => $val) {
            if (in_array($key, $this->ignoreHeaders)) {
                continue;
            }
            if (0 !== strpos($key, 'x-')) {
                continue;
            }
            $res[$key] = $val;
        }
        return $res;
    }


    /**
     * Bootstrap method to be called during application bootstrap stage.
     * @param Application $app the application currently running
     * @throws Exception
     */
    public function bootstrap($app)
    {
        $this->request = $app->getRequest();
        // 接口记录只在 web 应用上有效
        if ($this->request->getIsConsoleRequest()) {
            return;
        }
        // 访问日志关闭
        if (false === $this->open) {
            return;
        }
        // 请求开始时间
        Timer::begin(self::TIMER_KEY_BEFORE_REQUEST);

        $this->systemCode = AppHelper::app()->getSystemAlias();
        if (empty($this->systemCode)) {
            $this->realPathInfo = $this->request->getPathInfo();
        } else {
            $this->realPathInfo = $this->systemCode . '/' . $this->request->getPathInfo();
        }

        // 参数记录
        DataStore::set($this->getStoreKey(), [
            'header' => $this->getCustomHeaders(),
            'get'    => $this->request->get(),
            'post'   => $this->request->post(),
            'file'   => $_FILES,
        ]);
        $app->getResponse()->on(Response::EVENT_AFTER_SEND, [$this, "afterSendHandle"]);
    }

    /**
     * 响应后事件，对日志进行入库操作
     *
     * @param Event $event
     * @throws CustomException
     * @throws InvalidConfigException
     */
    public function afterSendHandle(Event $event)
    {
        $response = $event->sender;
        /* @var Response $response */
        $accessLogData = $this->getAccessLogData($response);
        $log           = Yii::createObject($this->accessLogModel);
        if (!$log instanceof AccessLogs) {
            throw new CustomException("accessLogModel必须继承\YiiHelper\models\accessLog\AccessLogs");
        }
        $log->setAttributes($accessLogData);
        $log->save();
    }

    /**
     * 获取访问日志数据
     *
     * @param Response $response
     * @return array
     */
    protected function getAccessLogData(Response $response)
    {
        static $inData;
        if (null === $inData) {
            $inData = [
                // 'id'            => '', // 自增ID
                'system_code'  => $this->systemCode, // 系统别名
                'trace_id'     => ReqHelper::getTraceId(), // 日志ID
                'url_path'     => $this->realPathInfo, // URL路径
                'method'       => $this->request->getMethod(), // 请求方法
                'request_data' => DataStore::get($this->getStoreKey()), // 接口参数
                // 'is_success'    => '', // 是否成功
                // 'message'       => '', // 返回消息
                // 'response_code' => '', // http状态
                // 'response_data' => '', // 接口响应
                // 'exts'          => '', // 扩展信息
                'use_time'     => Timer::end(self::TIMER_KEY_BEFORE_REQUEST), // 接口耗时
                'ip'           => Req::getUserIp(), // 登录IP
                'uid'          => AppHelper::app()->getUser()->getIsGuest() ? 0 : AppHelper::app()->getUser()->getId(), // 用户ID
                // 'created_at'    => '', // 创建时间
            ];
            // 正常响应
            $inData['exts']          = null;
            $inData['response_code'] = $response->statusCode;
            $inData['response_data'] = $response->data;
            if (is_array($response->data)) {
                if (isset($response->data['code'])) {
                    $inData['is_success'] = 0 == $response->data['code'] ? 1 : 0;
                } else {
                    $inData['is_success'] = 1;
                }
                $inData['message'] = isset($response->data['msg']) ? $response->data['msg'] : '';
            } else {
                $inData['is_success'] = 1;
                $inData['message']    = '';
            }
        }
        return $inData;
    }
}