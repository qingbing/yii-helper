<?php
/**
 * @link        http://www.phpcorner.net
 * @author      qingbing<780042175@qq.com>
 * @copyright   Chengdu Qb Technology Co., Ltd.
 */

namespace YiiHelper\features\login\services;


use Yii;
use yii\base\InvalidConfigException;
use YiiHelper\abstracts\Service;
use YiiHelper\features\login\services\interfaces\ILoginService;
use YiiHelper\features\login\services\loginType\abstracts\LoginBase;
use YiiHelper\helpers\Instance;
use YiiHelper\models\user\User;
use YiiHelper\models\user\UserAccount;
use Zf\Helper\Exceptions\BusinessException;
use Zf\Helper\Exceptions\CustomException;

/**
 * 服务类 ： 用户登录
 *
 * Class LoginService
 * @package YiiHelper\features\login\services
 */
class LoginService extends Service implements ILoginService
{
    /**
     * 获取登录账户信息
     *
     * @param string $type
     * @param string $account
     * @return UserAccount
     * @throws InvalidConfigException
     */
    public function getUserAccount(string $type, string $account): UserAccount
    {
        return Instance::modelUserAccount()::findOne([
            'type'    => $type,
            'account' => $account,
        ]);
    }

    /**
     * 获取登录账户信息
     *
     * @param int $uid
     * @return User
     * @throws InvalidConfigException
     */
    public function getUser($uid): User
    {
        return Instance::modelUser()::findOne([
            'uid' => $uid,
        ]);
    }

    /**
     * 获取支持的登录类型
     *
     * @return array
     * @throws CustomException
     * @throws InvalidConfigException
     */
    public function getSupportTypes(): array
    {
        return Instance::modelUserAccount()::types();
    }

    /**
     * 获取支持的登录类型服务
     *
     * @param string|null $loginType
     * @return array
     * @throws CustomException
     * @throws InvalidConfigException
     */
    public function getSupportServiceMaps(?string $loginType = null): array
    {
        $serviceMaps = Instance::modelUserAccount()::serviceMaps();
        if (empty($loginType)) {
            return $serviceMaps;
        }
        if (!isset($serviceMaps[$loginType])) {
            throw new CustomException(replace('不支持的登录类型"{type}"', [
                '{type}' => $loginType
            ]));
        }
        return $serviceMaps[$loginType];
    }

    /**
     * 账户登录
     *
     * @param array $params
     * @return bool
     * @throws BusinessException
     * @throws CustomException
     * @throws InvalidConfigException
     */
    public function signIn(array $params): bool
    {
        $supportServiceMap = $this->getSupportServiceMaps();
        if (!isset($supportServiceMap[$params['type']])) {
            throw new BusinessException(replace('不支持的登录类型"{type}"', [
                '{type}' => $params['type'],
            ]));
        }
        $params['class']   = $supportServiceMap[$params['type']];
        $params['service'] = $this;
        unset($params['type']);
        $service = Yii::createObject($params);
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