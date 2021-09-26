<?php
/**
 * @link        http://www.phpcorner.net
 * @author      qingbing<780042175@qq.com>
 * @copyright   Chengdu Qb Technology Co., Ltd.
 */

namespace YiiHelper\components\oauth;


use yii\base\Component;
use YiiHelper\components\oauth\drivers\Client;
use YiiHelper\components\oauth\drivers\Server;
use Zf\Helper\Exceptions\ProgramException;

/**
 * Class Oauth
 * @package YiiHelper\components\oauth
 *
 * @property-read Server $server
 * @property-read Client $client
 */
class Oauth extends Component
{
    /**
     * @var array 客户端配置
     */
    public $serverConf = [
        'class' => \YiiHelper\components\oauth\drivers\redis\Server::class,
    ];
    /**
     * @var array 服务端排脂
     */
    public $clientConf = [
        'class' => Client::class,
    ];

    /**
     * @var Server
     */
    private $_server;
    /**
     * @var Client
     */
    private $_client;

    /**
     * 获取 oauth 服务端
     *
     * @return array|object|Server
     * @throws ProgramException
     * @throws \yii\base\InvalidConfigException
     */
    public function getServer()
    {
        if (null === $this->_server) {
            if (is_string($this->serverConf)) {
                $this->serverConf = [
                    'class' => $this->serverConf,
                ];
            }
            if (!isset($this->serverConf['class'])) {
                $this->serverConf['class'] = \YiiHelper\components\oauth\drivers\redis\Server::class;
            }
            $this->serverConf['oauth'] = $this;
            // 实例化
            $server = \Yii::createObject($this->serverConf);
            if (!$server instanceof Server) {
                throw new ProgramException("oauth-server 必须继承 " . __NAMESPACE__ . "\drivers\Server");
            }
            $this->_server = $server;
        }
        return $this->_server;
    }

    /**
     * 获取 oauth 客户端
     *
     * @return array|object|Client
     * @throws ProgramException
     * @throws \yii\base\InvalidConfigException
     */
    public function getClient()
    {
        if (null === $this->_client) {
            if (is_string($this->clientConf)) {
                $this->clientConf = [
                    'class' => $this->clientConf,
                ];
            }
            if (!isset($this->clientConf['class'])) {
                $this->clientConf['class'] = Client::class;
            }
            $this->clientConf['oauth'] = $this;
            // 实例化
            $client = \Yii::createObject($this->clientConf);
            if (!$client instanceof Client) {
                throw new ProgramException("oauth-client 必须继承 " . __NAMESPACE__ . "\drivers\Client");
            }
            $this->_client = $client;
        }
        return $this->_client;
    }

    /**
     * 生成token
     *
     * @param string $systemCode
     * @param string $uuid
     * @param string $appKey
     * @param string $appSecret
     * @param mixed $timestamp
     * @return string
     */
    public function generateToken(string $systemCode, string $uuid, string $appKey, string $appSecret, $timestamp)
    {
        return md5("{$systemCode}:{$uuid}:{$appKey}:{$appSecret}:{$timestamp}");
    }
}