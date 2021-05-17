<?php
/**
 * @link        http://www.phpcorner.net
 * @author      qingbing<780042175@qq.com>
 * @copyright   Chengdu Qb Technology Co., Ltd.
 */

namespace YiiHelper\features\login\services;


use Yii;
use YiiHelper\abstracts\Service;
use YiiHelper\features\login\services\interfaces\ILoginService;
use YiiHelper\features\login\services\loginType\abstracts\LoginBase;
use YiiHelper\features\login\services\loginType\LoginByEmail;
use YiiHelper\features\login\services\loginType\LoginByMobile;
use YiiHelper\features\login\services\loginType\LoginByName;
use YiiHelper\features\login\services\loginType\LoginByUsername;
use YiiHelper\models\abstracts\User;
use YiiHelper\models\abstracts\UserAccount;
use Zf\Helper\Exceptions\BusinessException;

/**
 * 服务类 ： 用户登录
 *
 * Class LoginService
 * @package YiiHelper\features\login\services
 */
abstract class LoginService extends Service implements ILoginService
{
    /**
     * @var array 登录类型服务配置
     */
    public $serviceMap = [
        UserAccount::TYPE_EMAIL    => LoginByEmail::class,
        UserAccount::TYPE_USERNAME => LoginByUsername::class,
        UserAccount::TYPE_MOBILE   => LoginByMobile::class,
        UserAccount::TYPE_NAME     => LoginByName::class,
    ];

    /**
     * 获取登录账户信息
     *
     * @param string $type
     * @param string $account
     * @return UserAccount
     */
    abstract public function getUserAccount(string $type, string $account): UserAccount;

    /**
     * 获取登录账户信息
     *
     * @param int $uid
     * @return User
     */
    abstract public function getUser($uid): User;

    /**
     * 获取支持的登录类型
     *
     * @return array
     */
    abstract public function getSupportTypes(): array;

    /**
     * 账户登录
     *
     * @param array $params
     * @return bool
     * @throws BusinessException
     */
    public function signIn(array $params): bool
    {
        $params['service'] = $this;
        if (!isset($this->serviceMap[$params['type']])) {
            throw new BusinessException(replace('不支持的登录类型"{type}"', [
                '{type}' => $params['type'],
            ]));
        }
        $service = new $this->serviceMap[$params['type']]($params);
        if (!$service instanceof LoginBase) {
            throw new BusinessException('登录服务必须继承自"\YiiHelper\services\login\LoginBase"');
        }
        return $service->signIn();
    }

    /**
     * 用户退出登录
     *
     * @return bool
     */
    public function signOut(): bool
    {
        if (!Yii::$app->getUser()->getIsGuest()) {
            Yii::$app->getUser()->logout();
        }
        return true;
    }

    /**
     * 判断是否用户登录
     *
     * @return bool
     */
    public function isLogin(): bool
    {
        return !Yii::$app->getUser()->getIsGuest();
    }
}