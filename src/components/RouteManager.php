<?php
/**
 * @link        http://www.phpcorner.net
 * @author      qingbing<780042175@qq.com>
 * @copyright   Chengdu Qb Technology Co., Ltd.
 */

namespace YiiHelper\components;

use Yii;
use yii\base\BaseObject;
use yii\base\Component;
use yii\base\Event;
use yii\base\InvalidConfigException;
use yii\db\Exception;
use yii\web\Application;
use yii\web\Response;
use YiiHelper\helpers\AppHelper;
use YiiHelper\models\routeLog\RouteAccessLog;
use YiiHelper\models\routeLog\RouteRecord;
use YiiHelper\models\routeLog\RouteRecordConfig;
use YiiHelper\traits\TResponse;
use Zf\Helper\Exceptions\CustomException;

/**
 * 接口 ： 路由日志自定义
 *
 * Interface IRouteLog
 * @package YiiHelper\components
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

/**
 * 抽象类 ： 路由日志自定义
 * Class RouteLogBase
 * @package YiiHelper\components
 */
abstract class RouteLogBase extends BaseObject implements IRouteLog
{
    /**
     * @var RouteManager 路由日志组件
     */
    protected $routeManger;

    /**
     * 路由日志构造函数
     *
     * @param RouteManager $routeManager
     * @param array $config
     */
    public function __construct(RouteManager $routeManager, $config = [])
    {
        parent::__construct($config);
        $this->routeManger = $routeManager;
    }
}

/**
 * 路由记录组件
 *
 * Class RouteRecord
 * @package YiiHelper\components
 */
class RouteManager extends Component
{
    use TResponse;
    /**
     * @var bool 是否开启路由记录
     */
    public $openRouteRecord = false;
    /**
     * @var bool 是否开启路由日志
     */
    public $openLog = false;
    /**
     * @var bool 是否开mock日志
     */
    public $openMock = false;
    /**
     * @var string mock的消息标识
     */
    public $mockMsg = "__ROUTE__MOCK__";
    /**
     * @var string 系统别名
     */
    public $systemAlias;
    /**
     * @var IRouteLog[] 日志自由类
     */
    public $routeLogClasses = [];
    /**
     * @var string 当前路由
     */
    protected $route;
    /**
     * @var RouteLogBase 当前路由日志处理类
     */
    protected $routeLogInstance;
    /**
     * @var RouteRecord|null 当前路由模型
     */
    private $_routeRecord;

    /**
     * 获取当前路由模型
     *
     * @return RouteRecord|null
     */
    protected function getRouteRecord()
    {
        if (null === $this->_routeRecord) {
            $record             = RouteRecord::findOne([
                'system_alias' => $this->systemAlias,
                'route'        => $this->route,
            ]);
            $this->_routeRecord = $record;
        }
        return $this->_routeRecord;
    }

    /**
     * 获取当前路由日志配置模型
     *
     * @return RouteRecordConfig|null
     */
    protected function getRouteRecordConfig()
    {
        return RouteRecordConfig::findOne([
            'system_alias' => $this->systemAlias,
            'route'        => $this->route,
        ]);
    }

    /**
     * 组件初始化
     *
     * @throws CustomException
     * @throws InvalidConfigException
     * @throws \Exception
     */
    public function init()
    {
        if (AppHelper::getIsConsole()) {
            return;
        }
        if (null === $this->systemAlias) {
            $this->systemAlias = AppHelper::app()->getSystemAlias();
        }
        $this->route = AppHelper::app()->getRequest()->getPathInfo();
        if ($this->openMock) {
            // 开启mock
            $config = $this->getRouteRecordConfig();
            if ($config && $config->is_mocking) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                Yii::$app->response->data   = $this->success($config->mocking_response, $this->mockMsg);
                Yii::$app->response->send();
                Yii::$app->end();
            }
        }
        if ($this->openRouteRecord) {
            // 开启路由记录
            Yii::$app->on(Application::EVENT_BEFORE_REQUEST, [$this, 'routeRecordHandle']);
        }
        if ($this->openLog) {
            // 开启路由日志
            $routeLogClass = $this->routeLogClasses[$this->route] ?? null;
            if (null !== $routeLogClass) {
                // 对于自定义日志路由，在请求前执行一次
                $this->routeLogInstance = Yii::createObject($routeLogClass, [$this]);
                if (!$this->routeLogInstance instanceof RouteLogBase) {
                    throw new CustomException(replace('日志路由"{routeClass}"必须继承路由抽象类"{routeLogBase}"', [
                        '{routeClass}'   => $routeLogClass,
                        '{routeLogBase}' => '\YiiHelper\components\RouteLogBase',
                    ]));
                }
                Yii::$app->on(Application::EVENT_BEFORE_REQUEST, [$this->routeLogInstance, 'beforeRequest']);
            }
            // 开启请求后日志记录
            Yii::$app->getResponse()->on(Response::EVENT_BEFORE_SEND, [$this, 'routeLogHandle']);
        }
    }

    /**
     * 路由记录入库
     *
     * @param Event $event
     * @throws Exception
     */
    public function routeRecordHandle(Event $event)
    {
        if (null === ($record = $this->getRouteRecord())) {
            $record = new RouteRecord();
            $record->setAttributes([
                'system_alias' => $this->systemAlias,
                'route'        => $this->route,
                'access_times' => 1,
            ]);
        } else {
            $record->updateCounters([
                'access_times' => 1,
            ]);
        }
        $record->saveOrException();
    }

    /**
     * 日志入库
     *
     * @param Event $event
     * @throws InvalidConfigException
     */
    public function routeLogHandle(Event $event)
    {
        $route = $this->getRouteRecord();
        if (null === $route) {
            return;
        }
        $config = $this->getRouteRecordConfig();
        if (null === $config) {
            return;
        }
        if (0 == $config->is_logging) {
            return;
        }
        $response = $event->sender;
        $request  = AppHelper::app()->getRequest();
        $input    = array_merge($request->getQueryParams(), $request->getBodyParams());
        $output   = $response->data;
        /* @var Response $response */
        $attributes = [
            'route_log_config_id' => $config->id,
            'message'             => $config->message,
            'is_success'          => 0 == $output['code'] ? 1 : 0,
            'input'               => $input,
            'output'              => $output,
        ];
        if ($this->routeLogInstance) {
            $data = call_user_func([$this->routeLogInstance, 'afterResponse'], $response);
            // 消息覆盖
            if (isset($data['message']) && !empty($data['message'])) {
                $attributes['message'] = $data['message'];
            }
            // 扩展信息必须来自自定义日志路由
            if (isset($data['exts'])) {
                $attributes['exts'] = $data['exts'];
            }
            // 关键字以自定义日志路由优先
            if (isset($data['keyword']) && !empty($data['keyword'])) {
                $attributes['keyword'] = $data['keyword'];
            }
        }
        if (!isset($attributes['keyword'])) {
            // 设定关键字（包括自定义路由无设定）
            if (!empty($config->key_fields)) {
                $attributes['keyword'] = $this->getKeyword($input, $config->key_fields);
            }
        }
        $log = new RouteAccessLog();
        $log->setAttributes($attributes);
        $log->save();
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