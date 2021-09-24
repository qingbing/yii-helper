<?php
/**
 * @link        http://www.phpcorner.net
 * @author      qingbing<780042175@qq.com>
 * @copyright   Chengdu Qb Technology Co., Ltd.
 */

namespace YiiHelper\components\oauth\drivers;


use Exception;
use Yii;

/**
 * oauth 客户端
 *
 * Class Client
 * @package YiiHelper\components\oauth\drivers
 */
class Client extends BaseOauth
{
    /**
     * @var string 缓存组件ID
     */
    public $cacheId = 'cache';

    /**
     * 获取cache组件
     *
     * @return \yii\caching\CacheInterface|null
     * @throws Exception
     */
    public function getCache()
    {
        return Yii::$app->get($this->cacheId);
    }

    /**
     * 获取缓存的 key
     *
     * @param string $uuid
     * @return string
     */
    protected function getCacheKey(string $uuid)
    {
        return __CLASS__ . ":{$this->getSystemCode()}:{$uuid}";
    }

    /**
     * 获取缓存后的访问 token
     *
     * @param string $uuid
     * @param callable $tokenGenerator
     * @return mixed
     * @throws Exception
     */
    public function getAccessToken(string $uuid, callable $tokenGenerator)
    {
        $cacheKey = $this->getCacheKey($uuid);
        if (false === ($output = $this->getCache()->get($cacheKey))) {
            $data   = call_user_func($tokenGenerator);
            $output = $data['accessToken'];
            $this->getCache()->set($cacheKey, $output, $data['ttl'] - 120);
        }
        return $output;
    }
}