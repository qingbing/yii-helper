<?php
/**
 * @link        http://www.phpcorner.net
 * @author      qingbing<780042175@qq.com>
 * @copyright   Chengdu Qb Technology Co., Ltd.
 */

namespace YiiHelper\components\oauth\drivers;


use YiiHelper\helpers\Req;
use YiiHelper\models\oauth\OauthUser;
use Zf\Helper\Business\IpHelper;
use Zf\Helper\Exceptions\BusinessException;
use Zf\Helper\Exceptions\ProgramException;
use Zf\Helper\Format;

/**
 * oauth 服务端
 *
 * Class Server
 * @package YiiHelper\components\oauth\drivers
 */
abstract class Server extends BaseOauth
{
    /**
     * @var int 请求token的请求的有效时间，默认2分钟
     */
    public $urlExpireTtl = 120;
    /**
     * @var int 访问token的有效时间
     */
    public $accessTokenTtl = 7200;
    /**
     * @var int 一个时间周期内最大访问次数
     */
    public $maxPushTimes = 60;
    /**
     * @var bool 周期内次数超限是否抛出异常
     */
    public $maxThrowException = true;
    /**
     * @var int 当前时间戳
     */
    protected $nowTimestamp;
    /**
     * @var string 当前时间
     */
    protected $nowDatetime;

    /**
     * @inheritDoc
     */
    public function init()
    {
        $this->nowTimestamp = time();
        $this->nowDatetime  = Format::datetime($this->nowTimestamp);
    }

    /**
     * 生成、保存并返回一个访问 token
     *
     * @param string $uuid
     * @param string $appKey
     * @param $timestamp
     * @param string $token
     * @return array
     * @throws BusinessException
     * @throws ProgramException
     */
    public function generateAccessToken($uuid, string $appKey, $timestamp, string $token)
    {
        // 检查 uuid 和 app_key 的存在性和匹配性
        $oauthUser = OauthUser::findOne([
            'system_code' => $this->getSystemCode(),
            'uuid'        => $uuid,
            'app_key'     => $appKey,
        ]);
        if (!$oauthUser) {
            throw new ProgramException("不存在或不匹配的访问钥匙");
        }
        if (time() - $timestamp > $this->urlExpireTtl) {
            throw new ProgramException("token请求已过期");
        }

        $realToken = $this->oauth->generateToken($this->getSystemCode(), $uuid, $appKey, $oauthUser->app_secret, $timestamp);
        if ($token !== $realToken) {
            throw new ProgramException("传递的token不匹配");
        }
        $today = Format::date();
        // 生效日期验证
        if ($oauthUser->expire_begin_at > "1900-01-01" && $oauthUser->expire_begin_at > $today) {
            throw new BusinessException("该用户未到访问期");
        }
        // 失效日期验证
        if ($oauthUser->expire_end_at > "1900-01-01" && $oauthUser->expire_end_at < $today) {
            throw new BusinessException("该用户已过期");
        }
        // ip验证
        if (!empty($oauthUser->expire_ip) && !$this->ipInRange(explode_data($oauthUser->expire_ip, '|'))) {
            throw new BusinessException('不允许ip请求该系统');
        }

        if (($count = $this->getCounts($oauthUser)) >= $this->maxPushTimes) {
            // 超过最大次数
            if ($this->maxThrowException) {
                throw new BusinessException(replace('周期内获取token次数已经超过{times}次', [
                    '{times}' => $this->maxPushTimes,
                ]));
            } else {
                // 移除访问 token, $popCount 表示有效的 token
                $this->popAccessToken($oauthUser, $count - $this->maxPushTimes);
            }
        }
        // 生成 token
        $accessToken = $this->createAccessToken($oauthUser, $token);
        // 保存 token
        $this->pushAccessToken($oauthUser, $accessToken);
        return [
            'accessToken' => $accessToken,
            'ttl'         => $this->accessTokenTtl,
        ];
    }

    /**
     * 判断ip是否在范围之类
     *
     * @param string|null|array $range
     * @return bool
     */
    protected function ipInRange($range): bool
    {
        if (empty($range)) {
            return true;
        }
        $ip = Req::getAccessIp();
        if (is_array($range)) {
            foreach ($range as $val) {
                if (IpHelper::inRange($ip, $val)) {
                    return true;
                }
            }
            return false;
        }
        return IpHelper::inRange($ip, $range);
    }

    /**
     * 生成一个访问 token
     *
     * @param OauthUser $oauthUser
     * @param string $token
     * @return string
     */
    protected function createAccessToken(OauthUser $oauthUser, string $token)
    {
        return md5($oauthUser->app_key . $oauthUser->app_secret . $token);
    }

    /**
     * 保存访问 token
     *
     * @param OauthUser $oauthUser
     * @param string $accessToken
     * @return bool
     */
    abstract protected function pushAccessToken(OauthUser $oauthUser, string $accessToken);

    /**
     * 清理 $count 个访问 token
     *
     * @param OauthUser $oauthUser
     * @param int $count
     * @return mixed
     */
    abstract protected function popAccessToken(OauthUser $oauthUser, $count = 0);

    /**
     * 获取当前拥有的访问 token 数量
     *
     * @param OauthUser $oauthUser
     * @return int
     */
    abstract public function getCounts(OauthUser $oauthUser);

    /**
     * 判断是否是有效token
     *
     * @param string $uuid
     * @param string $accessToken
     * @return bool
     */
    abstract public function isExpireAccessToken($uuid, string $accessToken);
}