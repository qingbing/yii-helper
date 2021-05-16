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
use yii\base\Event;
use yii\base\InvalidConfigException;
use yii\db\ActiveRecord;
use yii\web\Application;
use yii\web\HeaderCollection;
use yii\web\Response;
use YiiHelper\helpers\AppHelper;
use YiiHelper\helpers\Req;
use YiiHelper\models\interfaceLogs\InterfaceFields;
use YiiHelper\models\interfaceLogs\InterfaceAccessLogs;
use YiiHelper\models\interfaceLogs\Interfaces;
use YiiHelper\models\interfaceLogs\InterfaceSystem;
use Zf\Helper\DataStore;
use Zf\Helper\ReqHelper;
use Zf\Helper\Timer;

/**
 * 接口参数信息管理
 *
 * Class BusinessInterface
 * @package YiiHelper\business
 */
class BusinessInterface
{
    const CACHE_KEY_SYSTEM           = __CLASS__ . ":system:";
    const CACHE_KEY_SYSTEM_INTERFACE = __CLASS__ . ":systemInterface:";

    /**
     * 返回接口系统信息的缓存键
     *
     * @param string $systemAlias
     * @return string
     */
    public static function getCacheKeyForSystem(string $systemAlias)
    {
        return self::CACHE_KEY_SYSTEM . $systemAlias;
    }

    /**
     * 返回系统接口信息的缓存键
     *
     * @param string $systemAlias
     * @param string $path
     * @return string
     */
    public static function getCacheKeyForSystemInterface(string $systemAlias, string $path)
    {
        return self::CACHE_KEY_SYSTEM . $systemAlias . ":{$path}";
    }

    /**
     * 获取系统信息
     *
     * @param string $systemAlias
     * @return mixed
     */
    public static function getSystem(string $systemAlias)
    {
        return AppHelper::app()->cacheHelper->get(self::getCacheKeyForSystem($systemAlias), function () use ($systemAlias) {
            return InterfaceSystem::find()
                ->andWhere(['=', 'alias', $systemAlias])
                ->asArray()
                ->one();
        }, 3600);
    }

    /**
     * 获取具体接口的信息
     *
     * @param string $systemAlias
     * @param string $path
     * @return mixed
     */
    public static function getSystemInterface(string $systemAlias, string $path)
    {
        return AppHelper::app()->cacheHelper->get(self::getCacheKeyForSystemInterface($systemAlias, $path), function () use ($systemAlias, $path) {
            $interface = Interfaces::find()
                ->andWhere(['=', 'system_alias', $systemAlias])
                ->andWhere(['=', 'uri_path', $path])
                ->asArray()
                ->one();
            if (!$interface) {
                return [
                    'info'   => null,
                    'fields' => [],
                ];
            }
            $fields = InterfaceFields::find()
                ->andWhere(['=', 'interface_alias', $interface['alias']])
                ->asArray()
                ->all();
            return [
                'info'   => $interface,
                'fields' => $fields,
            ];
        }, 3600);
    }

    /**
     * 添加一个接口及参数信息
     *
     * @param string $systemAlias
     * @param string $pathInfo
     * @param array|null $input
     * @param array|null $output
     * @throws Throwable
     */
    public static function addInterface(string $systemAlias, string $pathInfo, ?array $input = [], ?array $output = [])
    {
        // 利用事务的形式，写入接口数据
        AppHelper::app()->getDb()->transaction(function () use ($systemAlias, $pathInfo, $input, $output) {
            // 写入接口主体信息
            $data = [
                'system_alias' => $systemAlias,
                'uri_path'     => $pathInfo,
                'alias'        => $systemAlias . ':' . $pathInfo,
            ];
            // 写入接口信息
            $interfaceModel = self::addInterfaceInfo($data);
            // 写入请求信息
            self::addHeaderFields($interfaceModel, 'input', $input['header'] ?? null);
            self::addFileFields($interfaceModel, 'input', $input['file'] ?? null);
            self::addParamFields($interfaceModel, 'input', 'get', self::releaseParams($input['get'] ?? null)['sub']);
            self::addParamFields($interfaceModel, 'input', 'post', self::releaseParams($input['post'] ?? null)['sub']);
            // 写入响应信息
            self::addHeaderFields($interfaceModel, 'output', $output['header'] ?? null);
            self::addParamFields($interfaceModel, 'output', 'response', self::releaseParams($output['response'] ?? null)['sub']);
        });
    }

    /**
     * 添加 get post 接口字段信息
     *
     * @param Interfaces $interfaceInfo
     * @param string $type
     * @param string $dataArea
     * @param array|null $params
     * @param string $parentField
     */
    protected static function addParamFields(Interfaces $interfaceInfo, string $type, string $dataArea, ?array $params, $parentField = '')
    {
        foreach ($params as $val) {
            $alias = $parentField ? "{$parentField}.{$val['field']}" : $val['field'];
            self::addInterfaceField([
                'interface_alias' => $interfaceInfo->alias,
                'parent_alias'    => $parentField,
                'field'           => $val['field'],
                'alias'           => "{$interfaceInfo->alias}|{$alias}",
                'type'            => $type,
                'data_area'       => $dataArea,
                'data_type'       => $val['type'],
            ]);
            if (!empty($val['sub'])) {
                self::addParamFields($interfaceInfo, $type, $dataArea, $val['sub'], $alias);
            }
        }
    }

    /**
     * 添加 header 接口字段信息
     *
     * @param Interfaces $interfaceInfo
     * @param string $type
     * @param array|null $params
     */
    protected static function addHeaderFields(Interfaces $interfaceInfo, string $type = 'input', ?array $params = null)
    {
        if (empty($params)) {
            return;
        }
        foreach ($params as $key => $val) {
            self::addInterfaceField([
                'interface_alias' => $interfaceInfo->alias,
                'parent_alias'    => "",
                'field'           => $key,
                'alias'           => "{$interfaceInfo->alias}:{$key}",
                'type'            => $type,
                'data_area'       => 'header',
                'data_type'       => 'string',
            ]);
        }
    }

    /**
     * 添加 file 接口字段信息
     *
     * @param Interfaces $interfaceInfo
     * @param string $type
     * @param array|null $params
     */
    protected static function addFileFields(Interfaces $interfaceInfo, string $type = 'input', ?array $params = null)
    {
        if (empty($params)) {
            return;
        }
        foreach ($params as $key => $val) {
            self::addInterfaceField([
                'interface_alias' => $interfaceInfo->alias,
                'parent_alias'    => "",
                'field'           => $key,
                'alias'           => "{$interfaceInfo->alias}:{$key}",
                'type'            => $type,
                'data_area'       => 'header',
                'data_type'       => is_array($val['name']) ? 'string' : 'items',
            ]);
        }
    }

    /**
     * 保存接口信息
     *
     * @param array $data
     * @return array|ActiveRecord|Interfaces|null|void
     */
    protected static function addInterfaceInfo(array $data)
    {
        $model = Interfaces::find()
            ->andWhere(['=', 'alias', $data['alias']])
            ->one();
        if (null !== $model) {
            return $model;
        }
        $model = new Interfaces();
        $model->setAttributes($data);
        if ($model->save()) {
            return $model;
        }
        Yii::warning([
            'message' => '接口信息写入失败',
            'file'    => __FILE__,
            'line'    => __LINE__,
            'model'   => 'Interfaces',
            'data'    => $data,
        ], 'interface');
    }

    /**
     * 保存接口字段信息
     *
     * @param array $data
     * @return array|ActiveRecord|InterfaceFields|null|void
     */
    protected static function addInterfaceField(array $data)
    {
        $model = InterfaceFields::find()
            ->andWhere(['=', 'alias', $data['alias']])
            ->one();
        if (null !== $model) {
            return $model;
        }
        $model = new InterfaceFields();
        $model->setAttributes($data);
        if ($model->save()) {
            return $model;
        }
        Yii::warning([
            'message' => '接口信息写入失败',
            'file'    => __FILE__,
            'line'    => __LINE__,
            'model'   => 'InterfaceFields',
            'data'    => $data,
        ], 'interface');
    }

    /**
     * 解析参数的各级数据类型
     *
     * @param mixed $data
     * @return array
     */
    public static function releaseParams($data)
    {
        $data = is_string($data) ? json_decode($data) : json_decode(json_encode($data));
        return self::_releaseParams($data);
    }

    private static function _releaseParams($data, $field = "root")
    {
        $type = gettype($data);
        $item = [
            "field" => $field,
            "type"  => $type,
            'sub'   => [],
        ];
        if (is_object($data)) {
            // 子字段分析
            foreach ($data as $field => $datum) {
                $item['sub'][$field] = self::_releaseParams($datum, $field);
            }
        } elseif (is_array($data) && count($data) > 0) {
            if (is_object($data[0])) {
                $item['type'] = 'items';
                // 子数组合并field，值以最后次出现的为准
                $fields = [];
                foreach ($data as $datum) {
                    $fields = array_merge($fields, (array)$datum);
                }
                // 子字段分析
                foreach ($fields as $field => $datum) {
                    $item['sub'][$field] = self::_releaseParams($datum, $field);
                }
            }
        }
        return $item;
    }
}

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
     * @var InterfaceAccessLogs
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
        if (!Yii::$app->getRequest()->getIsConsoleRequest()) {
            // 接口记录只在 web 应用上有效
            $this->request = Yii::$app->getRequest();
            $this->releasePath();
            \Yii::$app->on(Application::EVENT_BEFORE_REQUEST, [$this, "beforeRequest"]);
            \Yii::$app->getResponse()->on(Response::EVENT_AFTER_SEND, [$this, "afterResponseSend"]);
        }
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
     */
    public function beforeRequest()
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
        } elseif (0 == $interfaceInfo['log_type'] && !$this->openLog) {
            return;
        }

        $this->_logging = true;
        // 系统和接口信息都有，记录日志
        $this->logModel = new InterfaceAccessLogs();
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
     * @param Event $event
     * @throws Throwable
     */
    public function afterResponseSend(Event $event)
    {
        $response = $event->sender;
        /* @var $response Response */
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