<?php
/**
 * @link        http://www.phpcorner.net
 * @author      qingbing<780042175@qq.com>
 * @copyright   Chengdu Qb Technology Co., Ltd.
 */

namespace YiiHelper\services\login\abstracts;


use Yii;
use yii\base\BaseObject;
use YiiHelper\helpers\Req;
use YiiHelper\services\abstracts\LoginService;
use Zf\Helper\Business\IpHelper;
use Zf\Helper\Exceptions\BusinessException;
use Zf\Helper\Format;

/**
 * 账户登录基类
 *
 * Class LoginBase
 * @package YiiHelper\services\login
 */
abstract class LoginBase extends BaseObject
{
    /**
     * @var string 登录账号
     */
    public $account;
    /**
     * @var string 登录密码
     */
    public $password;
    /**
     * @var string 是否记住登录
     */
    public $rememberMe;
    /**
     * @var LoginService
     */
    public $service;

    /**
     * @inheritDoc
     */
    public function __construct($config = [])
    {
        $config = [
            'account'    => $config['account'],
            'password'   => $config['password'],
            'rememberMe' => $config['rememberMe'],
            'service'    => $config['service'],
        ];
        parent::__construct($config);
    }

    /**
     * 获取登录类型
     *
     * @return string
     */
    abstract public function getType(): string;

    /**
     * 用户登录
     *
     * @return bool
     * @throws BusinessException
     */
    public function signIn()
    {
        $userAccount = $this->service->getUserAccount($this->getType(), $this->account);
        // 密码检查
        if (!$userAccount->validatePassword($this->password)) {
            throw new BusinessException('请确认账户密码正确');
        }
        // 账户检查
        if ($userAccount->is_enable == IS_ENABLE_NO) {
            throw new BusinessException('账户已停用');
        }
        $user = $this->service->getUser($userAccount->uid);
        // 用户检查
        if ($user->is_enable == IS_ENABLE_NO) {
            throw new BusinessException('用户已停用');
        }
        $today = Format::date();
        // 用户生效判断
        if ($user->expire_begin_at > '1900-01-01' && $user->expire_begin_at > $today) {
            throw new BusinessException('用户未生效');
        }
        // 用户失效判断
        if ($user->expire_end_at > '1900-01-01' && $user->expire_end_at < $today) {
            throw new BusinessException('用户已失效');
        }
        // 整体网站IP段配置
        if (!$this->ipInRange(Yii::$app->params['loginIps'])) {
            throw new BusinessException('网站禁止IP登录');
        }
        // 用户网站IP段配置
        if (!empty($user->expire_ip)) {
            if (!$this->ipInRange(explode_data($user->expire_ip, '|'))) {
                throw new BusinessException('用户禁止IP登录');
            }
        }
        $duration = 0;
        if ($this->rememberMe) {
            $duration = isset(Yii::$app->params['loginExpireSec']) ? Yii::$app->params['loginExpireSec'] : 3600;
        }
        $user->setLoginAccount($userAccount);
        return Yii::$app->getUser()->login($user, $duration);
    }

    /**
     * 判断ip是否在范围之类
     *
     * @param string $ip
     * @param string|null|array $range
     * @return bool
     */
    protected function ipInRange($range): bool
    {
        if (empty($range)) {
            return true;
        }
        $ip = Req::getUserIp();
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