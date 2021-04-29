<?php

namespace YiiHelper\models\abstracts;

use Yii;
use YiiHelper\abstracts\Model;
use Zf\Helper\Util;

/**
 * This is the model class for table "pro_account".
 *
 * @property int $id 自增ID
 * @property int $uid 用户ID
 * @property string $type 账户类型:username,email,phone,name,weixin,qq等
 * @property string $account 登录账户
 * @property string $password 密码
 * @property string $auth_key 登录的auth_key
 * @property int $is_enable 启用状态
 * @property int $login_times 登录次数
 * @property string $last_login_ip 最后登录IP
 * @property string $last_login_at 最后登录时间
 * @property string $register_at 注册或添加时间
 * @property string $updated_at 最后数据更新时间
 */
abstract class UserAccount extends Model
{
    const TYPE_USERNAME = 'username';
    const TYPE_EMAIL    = 'email';
    const TYPE_MOBILE   = 'mobile';
    const TYPE_NAME     = 'name';

    /**
     * 支持的登录类型
     *
     * @return array
     *  return [
     *      self::TYPE_USERNAME => '用户名',
     *      self::TYPE_EMAIL    => '邮箱',
     *      self::TYPE_MOBILE   => '手机号',
     *      self::TYPE_NAME     => '姓名',
     *  ];
     */
    abstract public static function types(): array;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['uid', 'type', 'account'], 'required'],
            [['uid', 'is_enable', 'login_times'], 'integer'],
            [['last_login_at', 'register_at', 'updated_at'], 'safe'],
            [['type'], 'string', 'max' => 20],
            [['account'], 'string', 'max' => 100],
            [['password'], 'string', 'max' => 60],
            [['auth_key'], 'string', 'max' => 32],
            [['last_login_ip'], 'string', 'max' => 15],
            [['type', 'account'], 'unique', 'targetAttribute' => ['type', 'account']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id'            => '自增ID',
            'uid'           => '用户ID',
            'type'          => '账户类型',
            'account'       => '登录账户',
            'password'      => '密码',
            'auth_key'      => '登录的auth_key',
            'is_enable'     => '启用状态',
            'login_times'   => '登录次数',
            'last_login_ip' => '登录IP',
            'last_login_at' => '登录时间',
            'register_at'   => '注册时间',
            'updated_at'    => '更新时间',
        ];
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
     * 获取登录auth_key
     * @return string
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * 验证auth_key
     * @param string $authKey
     * @return bool
     */
    public function validateAuthKey(string $authKey)
    {
        return $this->auth_key == $authKey;
    }
}
