<?php
/**
 * @link        http://www.phpcorner.net
 * @author      qingbing<780042175@qq.com>
 * @copyright   Chengdu Qb Technology Co., Ltd.
 */

namespace YiiHelper\components;

use Yii;
use YiiHelper\helpers\Req;
use YiiHelper\models\abstracts\UserAccount;
use Zf\Helper\DataStore;
use Zf\Helper\Format;

/**
 * 扩展用户登录组件
 *
 * Class User
 * @package YiiHelper\components
 *
 * @property string $username
 * @property \YiiHelper\models\abstracts\User $identity
 */
class User extends \yii\web\User
{
    const LOGIN_TYPE_KEY    = 'user:loginType';
    const LOGIN_ACCOUNT_KEY = 'user:loginAccount';

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
     */
    protected function beforeLogin($identity, $cookieBased, $duration)
    {
        /* @var \YiiHelper\models\abstracts\User $identity */
        if (!$this->multiLogin) {
            // 不允许多机登录时，创建新登录的 auth_key，这样会挤出其它地方登录的账户
            $identity->getLoginAccount()->generateAuthKey();
        }
        // 设置必要的登录 session
        Yii::$app->getSession()->set(self::LOGIN_TYPE_KEY, $identity->getLoginAccount()->type); // 登录账号类型
        Yii::$app->getSession()->set(self::LOGIN_ACCOUNT_KEY, $identity->getLoginAccount()->account); // 登录账号
        return parent::beforeLogin($identity, $cookieBased, $duration);
    }

    /**
     * @inheritDoc
     */
    protected function afterLogin($identity, $cookieBased, $duration)
    {
        parent::afterLogin($identity, $cookieBased, $duration);
        /* @var \YiiHelper\models\abstracts\User $identity */
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
}