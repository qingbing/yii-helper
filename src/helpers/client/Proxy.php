<?php
/**
 * @link        http://www.phpcorner.net
 * @author      qingbing<780042175@qq.com>
 * @copyright   Chengdu Qb Technology Co., Ltd.
 */

namespace YiiHelper\helpers\client;


use Exception;
use Yii;
use yii\base\BaseObject;
use yii\httpclient\Request;
use yii\httpclient\RequestEvent;
use yii\httpclient\Response;

/**
 * 代理类 : 普通代理
 *
 * Class Proxy
 * @package YiiHelper\helpers\client
 */
class Proxy extends BaseObject
{
    /**
     * @var int client 访问超时时间
     */
    public $timeout = 30;
    /**
     * @var Client | array client的配置或实例
     */
    public $client = [
        'class'              => Client::class,
        'unTranslateHeaders' => [],
    ];
    /**
     * @var Request
     */
    protected $request;

    /**
     * Proxy constructor.
     * @param array $config
     */
    public function __construct($config = [])
    {
        if (isset($config['client']) && is_array($config['client']) && is_array($this->client)) {
            $config['client'] = array_merge($this->client, $config['client']);
        }
        parent::__construct($config);
    }

    /**
     * @throws \yii\base\InvalidConfigException
     */
    public function init()
    {
        if (!is_object($this->client)) {
            $this->client = Yii::createObject($this->client);
        }
        $this->request = $this->client->createRequest();
        // 获取并设置 client 超时时间
        $timeout = Yii::$app->getRequest()->getHeaders()->get("R-TIMEOUT", $this->timeout);
        if ($timeout > 5) {
            $this->addOptions(['timeout' => $timeout]);
        }
        // 发送请求前事件
        $this->client->on(Client::EVENT_BEFORE_SEND, [$this, 'handleBeforeSend']);
        // 发送请求后事件
        $this->client->on(Client::EVENT_AFTER_SEND, [$this, 'handleAfterSend']);
    }

    /**
     * client 发送前句柄
     *
     * @param RequestEvent $event
     */
    public function handleBeforeSend(RequestEvent $event)
    {
    }

    /**
     * client 发送后句柄
     *
     * @param RequestEvent $event
     */
    public function handleAfterSend(RequestEvent $event)
    {
    }

    /**
     * 添加请求的 options
     *
     * @param $options
     * @return $this
     */
    public function addOptions($options)
    {
        $this->request->addOptions($options);
        return $this;
    }

    /**
     * 添加 client 的 headers
     *
     * @param array $headers
     * @return $this
     */
    public function addHeaders(array $headers)
    {
        $this->client->addHeaders($headers);
        return $this;
    }

    /**
     * @param string $uri
     * @param mixed $data
     * @param string $method
     * @param array $files
     * @return Response
     * @throws Exception
     */
    public function send(string $uri, $data = null, $method = 'POST', array $files = [])
    {
        if (!empty($files)) {
            $method = 'POST';
            foreach ($files as $key => $file) {
                if (is_string($file)) {
                    $this->request->addFile($key, $file);
                } else {
                    foreach ($file as $i => $f) {
                        $this->request->addFile($key . '[]', $f);
                    }
                }
            }
        }
        // 设置请求方式和uri
        $this->request->setMethod($method)->setUrl($uri);
        // 设置请求数据
        if (is_array($data)) {
            $this->request->setData($data);
        } else {
            $this->request->setContent($data);
        }
        // 发送请求获取响应
        return $this->request->send();
    }
}