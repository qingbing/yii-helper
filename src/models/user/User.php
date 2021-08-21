<?php

namespace YiiHelper\models\user;

use Yii;
use yii\base\InvalidConfigException;
use yii\base\NotSupportedException;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use YiiHelper\abstracts\Model;
use YiiHelper\behaviors\DefaultBehavior;
use YiiHelper\helpers\Instance;
use YiiHelper\models\permission\PermissionRole;
use YiiHelper\models\permission\PermissionUserRole;
use Zf\Helper\Util;

/**
 * This is the model class for table "portal_user".
 *
 * @property int $uid 自增ID
 * @property string $nickname 用户昵称
 * @property string $real_name 姓名
 * @property string $password 密码
 * @property string $auth_key 登录的auth_key
 * @property int $sex 性别[0:保密,1:男士,2:女士]
 * @property string $avatar 头像
 * @property string $email 邮箱账户
 * @property string $mobile 手机号码
 * @property string $phone 固定电话
 * @property string $qq QQ
 * @property string $id_card 身份证号
 * @property string $birthday 生日
 * @property string $address 联系地址
 * @property string $zip_code 邮政编码
 * @property int $is_enable 用户启用状态
 * @property int $is_super 是否超级用户
 * @property int $refer_uid 引荐人或添加人UID
 * @property string $expire_ip 有效IP地址
 * @property string $expire_begin_at 生效日期
 * @property string $expire_end_at 失效日期
 * @property int $login_times 登录次数
 * @property string $last_login_ip 最后登录IP
 * @property string $last_login_at 最后登录时间
 * @property string $register_ip 注册或添加IP
 * @property string $register_at 注册或添加时间
 * @property string $updated_at 最后数据更新时间
 *
 * 用户
 */
class User extends Model implements IdentityInterface
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%user}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nickname'], 'required'],
            [['sex', 'is_enable', 'is_super', 'refer_uid', 'login_times'], 'integer'],
            [['birthday', 'expire_begin_at', 'expire_end_at', 'last_login_at', 'register_at', 'updated_at'], 'safe'],
            [['nickname'], 'string', 'max' => 50],
            [['real_name'], 'string', 'max' => 30],
            [['password'], 'string', 'max' => 60],
            [['auth_key'], 'string', 'max' => 32],
            [['avatar'], 'string', 'max' => 200],
            [['email'], 'string', 'max' => 100],
            [['mobile', 'phone', 'qq', 'last_login_ip', 'register_ip'], 'string', 'max' => 15],
            [['id_card'], 'string', 'max' => 18],
            [['address', 'expire_ip'], 'string', 'max' => 255],
            [['zip_code'], 'string', 'max' => 6],
            [['nickname'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'uid'             => '自增ID',
            'nickname'        => '昵称',
            'real_name'       => '姓名',
            'password'        => '密码',
            'auth_key'        => '登录的auth_key',
            'sex'             => '性别',
            'avatar'          => '头像',
            'email'           => '邮箱账户',
            'mobile'          => '手机号码',
            'phone'           => '固定电话',
            'qq'              => 'QQ',
            'id_card'         => '身份证号',
            'birthday'        => '生日',
            'address'         => '联系地址',
            'zip_code'        => '邮政编码',
            'is_enable'       => '启用状态',
            'is_super'        => '超级用户',
            'refer_uid'       => '引荐人',
            'expire_ip'       => '有效IP地址',
            'expire_begin_at' => '生效日期',
            'expire_end_at'   => '失效日期',
            'login_times'     => '登录次数',
            'last_login_ip'   => '登录IP',
            'last_login_at'   => '登录时间',
            'register_ip'     => '注册IP',
            'register_at'     => '注册时间',
            'updated_at'      => '更新时间',
        ];
    }

    /**
     * 绑定 behavior
     *
     * @return array
     */
    public function behaviors()
    {
        return [
            [
                'class'      => DefaultBehavior::class,
                'type'       => DefaultBehavior::TYPE_DATE,
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['birthday'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['birthday'],
                ],
            ],
            [
                'class'      => DefaultBehavior::class,
                'type'       => DefaultBehavior::TYPE_DATETIME,
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['expire_begin_at', 'expire_end_at', 'last_login_at',],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['expire_begin_at', 'expire_end_at', 'last_login_at',],
                ],
            ],
        ];
    }

    /**
     * Finds an identity by the given ID.
     * @param string|int $id the ID to be looked for
     * @return IdentityInterface|null the identity object that matches the given ID.
     * Null should be returned if such an identity cannot be found
     * or the identity is not in an active state (disabled, deleted, etc.)
     */
    public static function findIdentity($id)
    {
        return self::findOne([
            'uid'       => $id,
            'is_enable' => IS_ENABLE_YES,
        ]);
    }

    /**
     * @inheritDoc
     */
    public function getId()
    {
        return $this->uid;
    }

    /**
     * @inheritDoc
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @inheritDoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->auth_key == $authKey;
    }

    /**
     * @inheritDoc
     * @throws NotSupportedException
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not support');
    }

    /**
     * 生成 db 密码
     *
     * @param string $pass
     * @return string
     * @throws \yii\base\Exception
     */
    public function generatePassword(string $pass)
    {
        return Yii::$app->getSecurity()->generatePasswordHash($pass);
    }

    /**
     * 验证 db 密码是否正确
     *
     * @param string $pass
     * @return bool
     */
    public function validatePassword(string $pass)
    {
        return Yii::$app->getSecurity()->validatePassword($pass, $this->password);
    }

    /**
     * 创建登录auth_key
     * @return $this
     */
    public function generateAuthKey()
    {
        $this->auth_key = Util::uniqid();
        return $this;
    }

    /**
     * @var UserAccount
     */
    private $_loginAccount;

    /**
     * 登录时设置登录的账户
     *
     * @param UserAccount $account
     * @return $this
     */
    public function setLoginAccount(UserAccount $account)
    {
        $this->_loginAccount = $account;
        return $this;
    }

    /**
     * 获取登录的的账户信息
     *
     * @return UserAccount
     * @throws InvalidConfigException
     */
    public function getLoginAccount()
    {
        if (null === $this->_loginAccount) {
            // 主要用于登录后期获取登录账户，由于在 getIsGuest 中会验证 auth_key，所以该函数不是能是 getIsGuest 判断是否登录后再获取消息
            $uid     = $this->uid;
            $type    = Yii::$app->getSession()->get(\YiiHelper\components\User::LOGIN_TYPE_KEY);
            $account = Yii::$app->getSession()->get(\YiiHelper\components\User::LOGIN_ACCOUNT_KEY);
            if (empty($uid) || empty($type) || empty($account)) {
                return null;
            }
            $this->_loginAccount = $this->getUserAccount($uid, $type, $account);
        }
        return $this->_loginAccount;
    }

    /**
     * 查询用户账户信息
     *
     * @param $uid
     * @param string $type
     * @param string $account
     * @return UserAccount
     * @throws InvalidConfigException
     */
    protected function getUserAccount($uid, string $type, string $account): UserAccount
    {
        return Instance::modelUserAccount()::findOne([
            'uid'     => $uid,
            'type'    => $type,
            'account' => $account,
        ]);
    }

    /**
     * 关联 : 获取用户已经分配的角色
     *
     * @param bool $onlyValid
     * @return \yii\db\ActiveQuery
     * @throws InvalidConfigException
     */
    public function getRoles($onlyValid = true)
    {
        $query = $this->hasMany(PermissionRole::class, [
            'code' => 'role_code',
        ]);
        if ($onlyValid) {
            return $query->andWhere(['=', 'is_enable', 1])
                ->viaTable(PermissionUserRole::tableName(), [
                    'uid' => 'uid'
                ])->andWhere(['=', 'is_valid', 1]);
        }
        return $query->viaTable(PermissionUserRole::tableName(), [
            'uid' => 'uid'
        ]);


        if ($this->is_super) {
            $query = PermissionRole::find();
            if ($onlyValid) {
                $query->andWhere(['=', 'is_enable', 1]);
            }
            return $query;
        }
        $query = $this->hasMany(PermissionRole::class, [
            'code' => 'role_code',
        ]);
        if ($onlyValid) {
            $query->andWhere(['=', 'is_enable', 1]);
        }
        return $query->viaTable(PermissionUserRole::tableName(), [
            'uid' => 'uid'
        ]);
    }
}
