<?php
/**
 * @link        http://www.phpcorner.net
 * @author      qingbing<780042175@qq.com>
 * @copyright   Chengdu Qb Technology Co., Ltd.
 */

namespace YiiHelper\components;


use Throwable;
use Yii;
use yii\base\Component;
use yii\base\InvalidConfigException;
use yii\web\Application;
use yii\web\HeaderCollection;
use yii\web\Response;
use YiiHelper\business\BusinessInterface;
use YiiHelper\helpers\Req;
use YiiHelper\models\InterfaceLogs;
use Zf\Helper\DataStore;
use Zf\Helper\ReqHelper;
use Zf\Helper\Timer;

/**
 * 接口及接口日志组件
 *
 * Class InterfaceLog
 * @package YiiHelper\components
 *
 * @property-read array $systemInfo
 * @property-read array $interfaceInfo
 */
class InterfaceLog extends Component
{
    const TIMER_KEY_INTERFACE = __CLASS__ . ':interfaceLog';
    const TIMER_KEY_API       = __CLASS__ . ':interfaceApi';

    /**
     * @var bool 是否开启接口记录
     */
    public $acceptNewInterface = false;
    /**
     * @var bool 是否开启接口日志
     */
    public $openLog = false;
    /**
     * @var string 默认系统，针对 path 中不带有 "/" 的路由
     */
    public $defaultSystem = 'portal';
    /**
     * @var array 不被记入参数的 header 头
     */
    public $ignoreHeaders = [
        'x-forwarded-for',
    ];

    /**
     * @var yii\web\Request
     */
    protected $request;
    /**
     * @var InterfaceLogs
     */
    protected $logModel;

    /**
     * @var string 系统别名
     */
    private $_systemAlias;
    /**
     * @var string 接口path
     */
    private $_realPathInfo;
    /**
     * @var array 系统信息
     */
    private $_systemInfo;
    /**
     * @var array 接口信息
     */
    private $_interfaceInfo;
    /**
     * @var bool 是否记录了接口日志
     */
    private $_logging = false;
    /**
     * @var array 记录接口中断信息
     */
    private $_interceptData = [];

    /**
     * @inheritDoc
     * @throws InvalidConfigException
     */
    public function init()
    {
        $this->request = Yii::$app->getRequest();
        $this->releasePath();
    }

    /**
     * 系统信息
     * @return array
     */
    public function getSystemInfo()
    {
        return $this->_systemInfo;
    }

    /**
     * 接口信息
     *
     * @return array
     */
    public function getInterfaceInfo()
    {
        return $this->_interfaceInfo;
    }

    /**
     * 解析真实的系统和 pathInfo
     *
     * @throws InvalidConfigException
     */
    protected function releasePath()
    {
        $this->_systemAlias   = Yii::$app->getSystemAlias();
        $this->_realPathInfo  = $this->request->getPathInfo();
        $this->_systemInfo    = BusinessInterface::getSystem($this->_systemAlias);
        $this->_interfaceInfo = BusinessInterface::getSystemInterface($this->_systemAlias, $this->_realPathInfo);
    }

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
     * 请求开始前调用
     *
     * @param Application $application
     */
    public function beforeRequest(Application $application)
    {
        Timer::begin(self::TIMER_KEY_INTERFACE);
        // 参数记录，有两个用途
        // 1. 开启 acceptNewInterface 时，是作为接口参数的依据
        // 2. 开启 openLog 时，是作为接口入参的数据
        DataStore::set($this->getStoreKey(), [
            'header' => $this->getCustomHeaders(),
            'get'    => $this->request->get(),
            'post'   => $this->request->post(),
            'file'   => $_FILES,
        ]);

        // 记录日志进参
        $this->insertLog();
    }

    /**
     * 插入请求日志
     */
    protected function insertLog()
    {
        $systemInfo    = $this->getSystemInfo();
        $interfaceInfo = $this->getInterfaceInfo()['info'];
        // 系统或接口不存在，不记录日志
        if (empty($systemInfo) || empty($interfaceInfo) || 2 == $interfaceInfo['log_type']) {
            return;
        } else if (0 == $interfaceInfo['log_type'] && !$this->openLog) {
            return;
        }

        $this->_logging = true;
        // 系统和接口信息都有，记录日志
        $this->logModel = new InterfaceLogs();
        $this->logModel->setAttributes([
            'trace_id'     => ReqHelper::getTraceId(),
            'interface_id' => $interfaceInfo['id'],
            'method'       => $this->request->getMethod(),
            'client_ip'    => Req::getUserIp(),
            'request_data' => DataStore::get($this->getStoreKey()),
            'is_intercept' => 0,
            'is_success'   => 0,
        ]);
        if (!$this->logModel->save()) {
            $this->_logging = false;
            Yii::error([
                __METHOD__,
                "errors" => $this->logModel->getErrors(),
            ], "custom.error");
        }
    }

    /**
     * 记录真实请求开始时间
     */
    public function beginRequest()
    {
        Timer::begin(self::TIMER_KEY_API);
    }

    /**
     * 请求结束后调用
     *
     * @param Response $response
     * @throws Throwable
     */
    public function afterResponseSend(Response $response)
    {
        // 记录接口新消息
        $this->recordInterface($response);
        // 更新接口结果集
        $this->updateLog($response);
    }

    /**
     * 添加接口及参数信息入库
     *
     * @param Response $response
     * @throws Throwable
     */
    protected function recordInterface(Response $response)
    {
        $systemInfo = $this->getSystemInfo();
        if (empty($systemInfo) || !$systemInfo['is_record_field'] || !$this->acceptNewInterface) {
            return;
        }
        BusinessInterface::addInterface(
            $this->_systemAlias,
            $this->_realPathInfo,
            DataStore::get($this->getStoreKey()),
            [
                'header'   => $this->getCustomHeaders($response->getHeaders()),
                'response' => $response->data
            ]
        );
    }


    /**
     * 中断请求，主要用于 transmit
     *
     * @param string $message
     * @param mixed $data
     */
    public function intercept(string $message = '请求中断', $data = null)
    {
        $this->_interceptData = [
            'message'       => $message,
            'response_data' => $data,
        ];
    }

    /**
     * 更新接口响应信息
     *
     * @param Response $response
     */
    protected function updateLog(Response $response)
    {
        if (!$this->_logging) {
            return;
        }
        // 入参时记录了接口日志，这也就需要更新接口结果集
        $upData = [
            // 整体请求时间
            'use_time' => Timer::end(self::TIMER_KEY_INTERFACE),
        ];
        if (-1 !== ($_ = Timer::end(self::TIMER_KEY_API))) {
            // 具体接口响应时间
            $upData['response_time'] = $_;
        }
        if (!empty($this->_interceptData)) {
            // 转发中断，开启接口验证时可能触发
            $upData['is_intercept']  = 1;
            $upData['is_success']    = 0;
            $upData['message']       = $this->_interceptData['message'];
            $upData['response_code'] = 0;
            $upData['response_data'] = $this->_interceptData['response_data'];
        } else {
            // 正常响应
            $responseData            = $response->data;
            $upData['is_intercept']  = 0;
            $upData['response_code'] = 0;
            $upData['response_data'] = [
                'header'   => $this->getCustomHeaders($response->getHeaders()),
                'response' => $responseData,
            ];
            if (is_array($responseData)) {
                if (isset($responseData['code'])) {
                    $upData['is_success'] = 0 == $responseData['code'] ? 1 : 0;
                } else {
                    $upData['is_success'] = 1;
                }
                $upData['message'] = isset($responseData['message']) ? $responseData['message'] : '';
            } else {
                $upData['is_success'] = 1;
                $upData['message']    = '';
            }
        }
        $this->logModel->setAttributes($upData);
        $this->logModel->save();
    }
}