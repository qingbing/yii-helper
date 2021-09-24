<?php
/**
 * @link        http://www.phpcorner.net
 * @author      qingbing<780042175@qq.com>
 * @copyright   Chengdu Qb Technology Co., Ltd.
 */

namespace YiiHelper\components\oauth\drivers\redis;


use yii\di\Instance;
use yii\redis\Connection;
use YiiHelper\models\oauth\OauthUser;
use Zf\Helper\Exceptions\ProgramException;

/**
 * 驱动 : oauth-redis 存储驱动
 *
 * Class Server
 * @package YiiHelper\components\oauth\drivers\redis
 */
class Server extends \YiiHelper\components\oauth\drivers\Server
{
    /**
     * @var Connection, 可以为redis组件ID或redis配置
     */
    public $redis = 'redis';
    /**
     * @var string 有序集合记录
     */
    public $zsetName = "oauth:server:zset";

    /**
     * @inheritDoc
     * @throws ProgramException
     * @throws \yii\base\InvalidConfigException
     */
    public function init()
    {
        parent::init();
        $this->redis = Instance::ensure($this->redis, Connection::class);
    }

    /**
     * 获取有序结合的存储key
     *
     * @param string $uuid
     * @return string
     */
    protected function getZsetKeys($uuid): string
    {
        return $this->getSystemCode() . ":{$this->zsetName}:{$uuid}";
    }

    /**
     * 获取有效的有序集合
     *
     * @param string $zsetKey
     * @return array
     */
    protected function getExpireToken(string $zsetKey): array
    {
        return $this->redis->zrangebyscore($zsetKey, $this->nowTimestamp, $this->nowTimestamp + $this->accessTokenTtl);
    }

    /**
     * 保存访问 token
     *
     * @param OauthUser $oauthUser
     * @param string $accessToken
     * @return bool
     */
    protected function pushAccessToken(OauthUser $oauthUser, string $accessToken)
    {
        $zsetKey = $this->getZsetKeys($oauthUser->uuid);
        return $this->redis->zadd($zsetKey, $this->nowTimestamp + $this->accessTokenTtl, $accessToken) > 0;
    }

    /**
     * 清理 $count 个访问 token
     *
     * @param OauthUser $oauthUser
     * @param int $count
     * @return mixed
     */
    protected function popAccessToken(OauthUser $oauthUser, $count = 0)
    {
        if ($count < 1) {
            return true;
        }
        $zsetKey = $this->getZsetKeys($oauthUser->uuid);
        $members = $this->getExpireToken($zsetKey);
        for ($i = 0; $i < $count; $i++) {
            $this->redis->zrem($zsetKey, $members[$i]);
        }
        return true;
    }

    /**
     * 获取当前拥有的访问 token 数量
     *
     * @param OauthUser $oauthUser
     * @return int
     */
    public function getCounts(OauthUser $oauthUser)
    {
        $zsetKey = $this->getZsetKeys($oauthUser->uuid);
        // 清理过期数据
        $this->redis->zremrangebyscore($zsetKey, 0, $this->nowTimestamp);
        // 有效数据个数
        return intval($this->redis->zcount($zsetKey, $this->nowTimestamp, $this->nowTimestamp + $this->accessTokenTtl));
    }

    /**
     * 判断是否是有效token
     *
     * @param string $uuid
     * @param string $accessToken
     * @return bool
     */
    public function isExpireAccessToken($uuid, string $accessToken)
    {
        $zsetKey = $this->getZsetKeys($uuid);
        return in_array($accessToken, $this->getExpireToken($zsetKey));
    }
}