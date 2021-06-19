<?php
/**
 * @link        http://www.phpcorner.net
 * @author      qingbing<780042175@qq.com>
 * @copyright   Chengdu Qb Technology Co., Ltd.
 */

namespace YiiHelper\components\interfaceManager;


use Yii;
use yii\base\Component;
use yii\base\Event;
use yii\base\InvalidConfigException;
use yii\web\Application;
use yii\web\HeaderCollection;
use yii\web\Response;
use YiiHelper\components\interfaceManager\assist\BusinessInterface;
use YiiHelper\components\interfaceManager\assist\RouteLogBase;
use YiiHelper\helpers\AppHelper;
use YiiHelper\helpers\Req;
use YiiHelper\models\interfaceManager\InterfaceAccessLogs;
use YiiHelper\models\interfaceManager\InterfaceRouteLogs;
use YiiHelper\traits\TResponse;
use Zf\Helper\DataStore;
use Zf\Helper\Exceptions\CustomException;
use Zf\Helper\ReqHelper;
use Zf\Helper\Timer;

class InterfaceManager extends Component
{
    use TResponse;
    const TIMER_KEY_BEFORE_REQUEST = __CLASS__ . ':beforeRequest';
    const TIMER_KEY_BEFORE_API     = __CLASS__ . ':beforeApi';
    /**
     * @var string mock的消息标识
     */
    public $mockMsg = "__ROUTE__MOCK__";
    /**
     * @var string 默认系统，针对 path 中不带有 "/" 的路由
     */
    public $defaultSystem = 'portal';
    /**
     * @var array 不被记入参数的 header 头
     */
    public $ignoreHeaders = [
        'x-forwarded-for',
        'x-trace-id',
        'x-system',
    ];
    /**
     * @var array 路由日志类集合
     */
    public $routeLogClasses = [];
    /**
     * @var yii\web\Request
     */
    protected $request;
    /**
     * @var RouteLogBase
     */
    protected $routeLogInstance;
    // 系统别名
    protected $systemAlias;
    // 请求路由
    protected $realPathInfo;
    // 系统信息
    protected $systemInfo;
    // 接口信息，包括字段信息
    protected $interfaceInfo;
    // 接口是否严格验证
    protected $strictValidate = false;
    // 接口返回是否mock
    protected $isMock = false;
    // 记录接口中断信息
    private $_interceptData = [];

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
     * @inheritDoc
     *
     * @throws CustomException
     * @throws InvalidConfigException
     */
    public function init()
    {
        if (Yii::$app->getRequest()->getIsConsoleRequest()) {
            // 过滤控制台应用
            return;
        }
        // 请求开始时间
        Timer::begin(self::TIMER_KEY_BEFORE_REQUEST);

        // 接口记录只在 web 应用上有效
        $this->request      = Yii::$app->getRequest();
        $this->systemAlias  = AppHelper::app()->getSystemAlias();
        $this->realPathInfo = $this->request->getPathInfo();
        // 系统检查和获取
        $systemInfo = $this->systemInfo = BusinessInterface::getSystem($this->systemAlias);
        if (empty($systemInfo)) {
            throw new CustomException("系统'{$this->systemAlias}'不存在");
        }
        // 接口检查和获取
        $interfaceInfo = $this->interfaceInfo = BusinessInterface::getSystemInterface($this->systemAlias, $this->realPathInfo);
        if (empty($interfaceInfo['info']) && 0 == $systemInfo['is_allow_new_interface']) {
            throw new CustomException("系统接口'{$this->systemAlias}.{$this->realPathInfo}'不存在");
        }

        // 参数记录
        DataStore::set($this->getStoreKey(), [
            'header' => $this->getCustomHeaders(),
            'get'    => $this->request->get(),
            'post'   => $this->request->post(),
            'file'   => $_FILES,
        ]);

        // 开启强制校验
        if ($this->getResultType('is_strict_validate', 'strict_validate_type')) {
            $this->strictValidate = true;
        }

        // 开启校验
        if ($this->getResultType('is_open_validate', 'validate_type')) {
            \Yii::$app->on(Application::EVENT_BEFORE_REQUEST, [$this, "handleValidate"]);
        }

        // 记录访问日志
        if (!empty($interfaceInfo['info']) && $this->getResultType('is_open_access_log', 'access_log_type')) {
            \Yii::$app->getResponse()->on(Response::EVENT_AFTER_SEND, [$this, "handleAccessLog"]);
        }

        // 记录路由日志
        if (!empty($interfaceInfo['info']) && $interfaceInfo['info']['is_open_route_log']) {
            \Yii::$app->getResponse()->on(Response::EVENT_AFTER_SEND, [$this, "handleRouteLog"]);
            // 检查是否有指定路由操作类
            $routeLogClass = $this->routeLogClasses[$this->realPathInfo] ?? null;
            if (null !== $routeLogClass) {
                // 对于自定义日志路由，在请求前执行一次
                $this->routeLogInstance = Yii::createObject($routeLogClass, [$this]);
                if (!$this->routeLogInstance instanceof RouteLogBase) {
                    throw new CustomException(replace('日志路由"{routeClass}"必须继承路由抽象类"{routeLogBase}"', [
                        '{routeClass}'   => $routeLogClass,
                        '{routeLogBase}' => '\YiiHelper\components\interfaceManager\assist\RouteLogBase',
                    ]));
                }
                Yii::$app->on(Application::EVENT_BEFORE_REQUEST, [$this->routeLogInstance, 'beforeRequest']);
            }
        }

        // 接口mock
        if (!empty($interfaceInfo['info']) && $interfaceInfo['info']['is_open_mocking']) {
            $this->isMock = true;
            \Yii::$app->on(Application::EVENT_BEFORE_REQUEST, [$this, "handleMockData"]);
        }

        // 记录接口信息
        if ($this->getResultType('is_record_field', 'record_field_type')) {
            \Yii::$app->getResponse()->on(Response::EVENT_AFTER_SEND, [$this, "handleRecordInterface"]);
        }

        // 开启请求
        $this->beginRequest();
    }

    /**
     * 获取接口最终是否执行某个处理的结果
     *
     * @param string $systemField
     * @param string $interfaceField
     * @return bool
     */
    protected function getResultType(string $systemField, string $interfaceField)
    {
        $resultType = false;
        if (empty($this->interfaceInfo['info'])) {
            // 接口未定义，使用系统的处理方式
            if ($this->systemInfo[$systemField]) {
                $resultType = true;
            }
        } else if (1 == $this->interfaceInfo['info'][$interfaceField]) {
            // 接口中字段为 1 表示强制开启
            $resultType = true;
        } else if ($this->systemInfo[$systemField] && 0 == $this->interfaceInfo['info'][$interfaceField]) {
            // 系统配置为开启，接口为0 随系统时，表示开启处理
            $resultType = true;
        }
        return $resultType;
    }

    /**
     * 记录真实请求开始时间，如果为转发，该函数可被再次调用
     */
    public function beginRequest()
    {
        Timer::begin(self::TIMER_KEY_BEFORE_API, true);
    }

    /**
     * 中断请求，主要用于 validate,transmit
     *
     * @param string $message
     * @param null $data
     * @param null $exts
     * @throws \yii\base\ExitException
     */
    public function intercept(string $message = '请求中断', $data = null, $exts = null)
    {
        $this->_interceptData       = [
            'message'       => $message,
            'response_data' => $data,
            'exts'          => $exts,
        ];
        Yii::$app->response->format = Response::FORMAT_JSON;
        Yii::$app->response->data   = $this->success([], $message);
        Yii::$app->response->send();
        Yii::$app->end();
    }

    /**
     * response.afterSend 事件
     *
     * @param Event $event
     * @throws \Throwable
     */
    public function handleRecordInterface($event)
    {
        $response = $event->sender;
        /* @var Response $response */
        if ($this->isMock) {
            // 返回数据为mock时，不记录接口信息
            return;
        }
        $accessLogData = $this->getAccessLogData($response);
        if (1 == $accessLogData['is_intercept'] || 0 == $accessLogData['is_success']) {
            // 当接口处于中断或失败时，不记录接口信息
            return;
        }
        BusinessInterface::addInterface(
            $this->systemAlias,
            $this->realPathInfo,
            DataStore::get($this->getStoreKey()),
            [
                'header'   => $this->getCustomHeaders($response->getHeaders()),
                'response' => (0 == $response->data['code'] && $response->data['data']) ? $response->data['data'] : null,
            ]
        );
    }

    /**
     * 请求前参数验证
     *
     * @param Event $event
     * @throws InvalidConfigException
     * @throws \yii\base\ExitException
     */
    public function handleValidate(Event $event)
    {
        $validInfo = BusinessInterface::validateData($this->systemAlias, $this->realPathInfo, DataStore::get($this->getStoreKey()));
        if (!$validInfo['isValidSuccess']) {
            $this->intercept($validInfo['errorMsg'], null, $validInfo['errors']);
        }
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
                'trace_id'      => ReqHelper::getTraceId(), // 客户端日志ID
                'interface_id'  => $this->interfaceInfo['info']['id'], // 接口ID
                'method'        => $this->request->getMethod(), // 请求方法[get post put...]
                'request_data'  => DataStore::get($this->getStoreKey()), // 接口发送信息
                // 'is_intercept'  => 0, // 是否参数拦截[0:否; 1:是]
                // 'is_success'    => 0, // 是否成功[0:失败; 1:成功]
                // 'message'       => '', // 返回消息
                // 'response_code' => '', // http状态返回码
                // 'response_data' => '', // 接口返回信息
                'response_time' => Timer::end(self::TIMER_KEY_BEFORE_API), // 接口真实耗时
                'use_time'      => Timer::end(self::TIMER_KEY_BEFORE_REQUEST), // 整体接口耗时
                'ip'            => Req::getUserIp(), // 登录IP
                'uid'           => AppHelper::app()->getUser()->getIsGuest() ? 0 : AppHelper::app()->getUser()->getId(), // 用户ID
            ];
            if (!empty($this->_interceptData)) {
                // 转发中断，开启接口验证时可能触发
                $inData['is_intercept']  = 1;
                $inData['is_success']    = 0;
                $inData['message']       = $this->_interceptData['message'];
                $inData['response_code'] = 0;
                $inData['response_data'] = $this->_interceptData['response_data'];
                $inData['exts']          = $this->_interceptData['exts'];
            } else {
                // 正常响应
                $inData['is_intercept']  = 0;
                $inData['exts']          = null;
                $inData['response_code'] = 0;
                $inData['response_data'] = [
                    'header'   => $this->getCustomHeaders($response->getHeaders()),
                    'response' => $response->data,
                ];
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
        }
        return $inData;
    }

    /**
     * 访问日志入库
     *
     * @param Event $event
     */
    public function handleAccessLog(Event $event)
    {
        $interfaceInfo = $this->interfaceInfo['info'];
        if (empty($interfaceInfo)) {
            return;
        }
        $response = $event->sender;
        /* @var Response $response */
        $accessLogData = $this->getAccessLogData($response);
        $log           = new InterfaceAccessLogs();
        $log->setAttributes($accessLogData);
        $log->save();
    }

    /**
     * 路由日志入库
     *
     * @param Event $event
     * @throws InvalidConfigException
     */
    public function handleRouteLog(Event $event)
    {
        $interfaceInfo = $this->interfaceInfo['info'];
        if (empty($interfaceInfo)) {
            return;
        }
        $response = $event->sender;
        $request  = $this->request;

        /* @var Response $response */
        $accessLogData = $this->getAccessLogData($response);
        $input         = array_merge($request->getQueryParams(), $request->getBodyParams());
        $routeLogData  = [
            'trace_id'     => $accessLogData['trace_id'], // 客户端日志ID
            'interface_id' => $accessLogData['interface_id'], // 接口ID
            'method'       => $accessLogData['method'], // 请求方法[get post put...]
            'is_success'   => $accessLogData['is_success'], // 是否成功[0:失败; 1:成功]
            'use_time'     => $accessLogData['use_time'], // 路由耗时
            'ip'           => $accessLogData['ip'], // 登录IP
            'uid'          => $accessLogData['uid'], // 用户ID
            'input'        => $input, // 请求内容
            'output'       => $response->data, // 响应内容
            'keyword'      => $this->getKeyword($input, $interfaceInfo['route_log_key_fields']), // 关键字，用于后期筛选
            'message'      => $interfaceInfo['route_log_message'], // 操作消息
            'exts'         => $accessLogData['exts'], // 扩展信息
        ];
        if ($this->routeLogInstance) {
            $data = call_user_func([$this->routeLogInstance, 'afterResponse'], $response);
            // 消息覆盖
            if (isset($data['message']) && !empty($data['message'])) {
                $routeLogData['message'] = $data['message'];
            }
            // 扩展信息必须来自自定义日志路由
            if (isset($data['exts'])) {
                $routeLogData['exts'] = $data['exts'];
            }
            // 关键字以自定义日志路由优先
            if (isset($data['keyword']) && !empty($data['keyword'])) {
                $routeLogData['keyword'] = $data['keyword'];
            }
        }
        $routeLogModel = new InterfaceRouteLogs();
        $routeLogModel->setAttributes($routeLogData);
        $routeLogModel->save();
    }

    /**
     * 接口定位为mock时返回mock数据
     *
     * @param Event $event
     * @throws \yii\base\ExitException
     */
    public function handleMockData(Event $event)
    {
        // mock 数据构建
        $interfaceInfo = $this->interfaceInfo['info'];
        if ($interfaceInfo['is_use_custom_mock']) {
            $data = json_decode($interfaceInfo['mock_response']);
        } else {
            $data = BusinessInterface::getMockData($this->systemAlias, $this->realPathInfo);
        }
        $response         = AppHelper::app()->getResponse();
        $response->format = Response::FORMAT_JSON;
        $response->data   = $this->success($data, $this->mockMsg);
        $response->send();
        Yii::$app->end();
    }

    /**
     * 获取关键字
     *
     * @param array $input
     * @param $fields
     * @return string
     */
    protected function getKeyword(array $input, $fields): string
    {
        if (empty($fields)) {
            return '';
        }
        if (false !== strpos($fields, '->')) {
            $delimiter = '->';
        } elseif (false !== strpos($fields, ':')) {
            $delimiter = ':';
        } elseif (false !== strpos($fields, '|')) {
            $delimiter = '|';
        } else {
            $delimiter = '';
        }
        $fields   = explode_data($fields, $delimiter);
        $keywords = [];
        foreach ($fields as $field) {
            if (false === strpos($field, '.')) {
                $keywords[$field] = $input[$field] ?? '';
            } else {
                $_fields = explode_data($field, '.');
                $_input  = $input;
                while (count($_fields) > 0) {
                    $_field = array_shift($_fields);
                    if (isset($_input[$_field])) {
                        $_input = $_input[$_field];
                    } else {
                        break;
                    }
                }
                $keywords[$field] = count($_fields) > 0 ? '' : $_input;
            }
        }
        foreach ($keywords as $field => $keyword) {
            if (is_array($keyword)) {
                $keywords[$field] = json_encode($keyword, JSON_UNESCAPED_UNICODE);
            }
        }

        // 构造字符串返回
        $_count = count($keywords);
        if (0 === $_count) {
            return '';
        } elseif (1 === $_count) {
            $keyword = array_pop($keywords);
            return null === $keyword ? '' : $keyword;
        }
        return implode($delimiter, $keywords);
    }
}