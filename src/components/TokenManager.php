<?php
/**
 * @link        http://www.phpcorner.net
 * @author      qingbing<780042175@qq.com>
 * @copyright   Chengdu Qb Technology Co., Ltd.
 */

namespace YiiHelper\components;


use Yii;
use yii\base\Application;
use yii\base\BootstrapInterface;
use yii\base\Component;
use yii\di\Instance;
use yii\web\Request;
use YiiHelper\helpers\Req;
use YiiHelper\models\access\AccessUser;
use YiiHelper\tools\lifeCollection\drivers\RedisStore;
use YiiHelper\tools\lifeCollection\LifeCollection;
use YiiHelper\traits\TValidator;
use Zf\Helper\Business\IpHelper;
use Zf\Helper\Crypt\Openssl;
use Zf\Helper\Exceptions\CustomException;
use Zf\Helper\Exceptions\ForbiddenHttpException;
use Zf\Helper\Format;
use Zf\Helper\ReqHelper;

/**
 * 组件 : token管理组件
 *
 * Class TokenManager
 * @package YiiHelper\components
 */
class TokenManager extends Component implements BootstrapInterface
{
    use TValidator;
    /**
     * @var bool 接口访问是否开启token验证
     */
    public $enableToken = false;
    /**
     * @var string token验证的uri，该接口不参加token验证
     */
    public $tokenUrl = 'token/index';
    /**
     * @var string 存款key前缀
     */
    public $prefix = 'accessToken';
    /**
     * @var int 一个时间周期内最大访问次数
     */
    public $maxPushTimes = 60;
    /**
     * @var bool 周期内次数超限是否抛出异常
     */
    public $maxThrowException = true;
    /**
     * @var bool 周期内次数超限是否抛出异常
     */
    public $tokenHeaderName = 'x-access-token';
    /**
     * @var LifeCollection | array 存储数据组件
     */
    public $store = [
        'class'     => LifeCollection::class,
        'store'     => [
            'class' => RedisStore::class,
        ],
        'expireTtl' => 7200,
    ];
    /**
     * @var Request
     */
    public $request;

    /**
     * 必要数据准备
     *
     * @throws \yii\base\InvalidConfigException
     */
    public function init()
    {
        $this->request = \Yii::$app->getRequest();
        $this->store   = Instance::ensure($this->store, LifeCollection::class);
    }

    /**
     * Bootstrap method to be called during application bootstrap stage.
     * @param Application $app the application currently running
     *
     * @throws ForbiddenHttpException
     * @throws \yii\base\InvalidConfigException
     */
    public function bootstrap($app)
    {
        if (!$this->enableToken) {
            return;
        }
        if ($this->tokenUrl == Yii::$app->getRequest()->getPathInfo()) {
            return;
        }
        if ($this->getUuid() && $this->store->isExpireData($this->getAccessToken())) {
            return;
        }
        throw new ForbiddenHttpException("未设置token，无权访问");
    }

    private $_uuid;

    /**
     * 系统访问UUID，通过UUID可以查询到来访系统等信息
     *
     * @param string $uuid
     * @return $this
     */
    public function setUuid(string $uuid)
    {
        $this->_uuid = $uuid;
        $this->store->setColName("{$this->prefix}:" . \Yii::$app->id . ":{$uuid}");
        return $this;
    }

    /**
     * 获取来访系统UUID
     *
     * @return array|string
     */
    public function getUuid()
    {
        if (null === $this->_uuid) {
            $this->setUuid($this->request->getHeaders()->get('x-access-uuid'));
        }
        return $this->_uuid;
    }

    /**
     * 获取三方访问传递的 access-token
     *
     * @return array|string
     */
    public function getAccessToken()
    {
        return Yii::$app->getRequest()->getHeaders()->get($this->tokenHeaderName);
    }

    /**
     * @return array
     * @throws CustomException
     * @throws \Zf\Helper\Exceptions\CustomException
     * @throws \yii\base\InvalidConfigException
     */
    public function generateToken()
    {
        $accessUser = AccessUser::findOne([
            'uuid' => $this->getUuid(),
        ]);
        if (null === $accessUser) {
            throw new CustomException("不存在的系统访问key");
        }
        $today = Format::date();
        // 生效日期验证
        if ($accessUser->expire_begin_at > "1900-01-01" && $accessUser->expire_begin_at > $today) {
            throw new CustomException("该用户未到访问期");
        }
        // 失效日期验证
        if ($accessUser->expire_end_at > "1900-01-01" && $accessUser->expire_end_at < $today) {
            throw new CustomException("该用户已过期");
        }
        // ip验证
        if (!empty($accessUser->expire_ip) && !$this->ipInRange(explode_data($accessUser->expire_ip, '|'))) {
            throw new CustomException('不允许ip请求该系统');
        }
        // 检验最大次数
        if (($count = $this->store->getCount()) > $this->maxPushTimes) {
            // 超过最大次数
            if ($this->maxThrowException) {
                throw new CustomException(replace('周期内获取token次数已经超过{times}次', [
                    '{times}' => $this->maxPushTimes,
                ]));
            } else {
                // 移除访问 token, $popCount 表示有效的 token
                $this->store->pop($count - $this->maxPushTimes);
            }
        }

        $sign   = $this->request->getBodyParam('sign');
        $params = Openssl::decrypt($accessUser->private_key, $sign, $accessUser->private_password);
        if (!is_array($params) || !isset($params['timestamp']) || !isset($params['urlExpireTtl'])) {
            throw new CustomException("请求参数不正确");
        }
        $params = $this->validateParams([
            [['timestamp', 'urlExpireTtl'], 'required'],
            ['timestamp', 'integer', 'min' => 1, 'label' => '请求时间戳'],
            ['urlExpireTtl', 'integer', 'min' => 1, 'label' => '请求有效时长', 'default' => 120],
        ], $params);
        if ($params['urlExpireTtl'] > 0 && abs(time() - $params['timestamp']) > $params['urlExpireTtl']) {
            throw new CustomException("请求已过期");
        }
        // 生成token
        $token = md5("{$this->getUuid()}:{$params['timestamp']}:{$params['urlExpireTtl']}:" . ReqHelper::getTraceId());
        // 存储 token
        $this->store->pushData($token);
        // 返回token
        return [
            'token'     => $token,
            'expireTtl' => $this->store->expireTtl,
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
}