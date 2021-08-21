<?php
/**
 * @link        http://www.phpcorner.net
 * @author      qingbing<780042175@qq.com>
 * @copyright   Chengdu Qb Technology Co., Ltd.
 */

namespace YiiHelper\components;

use Yii;
use YiiHelper\helpers\Instance;
use YiiHelper\helpers\Req;
use YiiHelper\models\user\UserAccount;
use Zf\Helper\DataStore;
use Zf\Helper\Format;

/**
 * 扩展用户登录组件
 *
 * Class User
 * @package YiiHelper\components
 *
 * @property string $username
 * @property \YiiHelper\models\user\User $identity
 * @property-read array $permissions
 */
class User extends \yii\web\User
{
    const LOGIN_TYPE_KEY       = 'user:loginType';
    const LOGIN_ACCOUNT_KEY    = 'user:loginAccount';
    const LOGIN_PERMISSION_KEY = 'user:permission';

    /**
     * @var string 操作日志类名
     */
    public $operateClass;
    /**
     * @var boolean 统一账号是否允许多处登录
     */
    public $multiLogin = false;
    /**
     * @var array 支持的用户登录类型
     */
    public $loginTypes = [
        UserAccount::TYPE_EMAIL,
        UserAccount::TYPE_USERNAME,
        UserAccount::TYPE_MOBILE,
        UserAccount::TYPE_NAME,
    ];

    /**
     * 返回登录用户名
     *
     * @return string
     */
    public function getNickname()
    {
        if ($this->getIsGuest()) {
            return null;
        }
        return $this->identity->nickname;
    }

    /**
     * 获取登录账号信息
     *
     * @return UserAccount|null
     */
    public function getUserAccount()
    {
        if (null === $this->identity) {
            return null;
        }
        return DataStore::get(__CLASS__ . ':userAccount', function () {
            return $identity = $this->identity->getLoginAccount();
        });
    }

    /**
     * @inheritDoc
     *
     * @param \yii\web\IdentityInterface $identity
     * @param bool $cookieBased
     * @param int $duration
     * @return bool
     * @throws \yii\base\InvalidConfigException
     */
    protected function beforeLogin($identity, $cookieBased, $duration)
    {
        /* @var \YiiHelper\models\user\User $identity */
        if (!$this->multiLogin) {
            // 不允许多机登录时，创建新登录的 auth_key，这样会挤出其它地方登录的账户
            $identity->generateAuthKey();
        }
        if (parent::beforeLogin($identity, $cookieBased, $duration)) {
            // 设置必要的登录 session
            Yii::$app->getSession()->set(self::LOGIN_TYPE_KEY, $identity->getLoginAccount()->type); // 登录账号类型
            Yii::$app->getSession()->set(self::LOGIN_ACCOUNT_KEY, $identity->getLoginAccount()->account); // 登录账号
            Yii::$app->getSession()->set(self::LOGIN_PERMISSION_KEY, $this->getPermissions($identity)); // 用户权限
            return true;
        }
        return false;
    }

    /**
     * @inheritDoc
     */
    protected function afterLogin($identity, $cookieBased, $duration)
    {
        parent::afterLogin($identity, $cookieBased, $duration);
        /* @var \YiiHelper\models\user\User $identity */
        $nowDatetime = Format::datetime();
        // 更新相应登录数据信息
        $identity->last_login_at = $nowDatetime;
        $identity->last_login_ip = Req::getUserIp();
        $identity->login_times   = $identity->login_times + 1;
        $identity->save();

        $userAccount                = $identity->getLoginAccount();
        $userAccount->last_login_at = $nowDatetime;
        $userAccount->last_login_ip = Req::getUserIp();
        $userAccount->login_times   = $userAccount->login_times + 1;
        $userAccount->save();
    }

    /**
     * 获取登录用户的所有权限，包括角色、菜单、路径
     *
     * @param \YiiHelper\models\user\User|null $identity
     * @return mixed
     * @throws \yii\base\InvalidConfigException
     */
    public function getPermissions(?\YiiHelper\models\user\User $identity = null)
    {
        $data = Yii::$app->getSession()->get(self::LOGIN_PERMISSION_KEY);
        if (!$data) {
            $user = $identity ?: $this->identity;
            $data = Instance::modelUser()::getPermissions($user);
            Yii::$app->getSession()->set(self::LOGIN_PERMISSION_KEY, $data); // 用户权限
        }
        return $data;
    }
}