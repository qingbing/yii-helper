<?php

namespace YiiHelper\models\abstracts;

use YiiHelper\abstracts\Model;
use YiiHelper\features\login\services\loginType\LoginByEmail;
use YiiHelper\features\login\services\loginType\LoginByMobile;
use YiiHelper\features\login\services\loginType\LoginByName;
use YiiHelper\features\login\services\loginType\LoginByUsername;
use Zf\Helper\Exceptions\CustomException;

/**
 * This is the model class for table "portal_user_account".
 *
 * @property int $id 自增ID
 * @property int $uid 用户ID
 * @property string $type 账户类型:username,email,phone,name,weixin,qq等
 * @property string $account 登录账户
 * @property int $is_enable 启用状态
 * @property int $login_times 登录次数
 * @property string $last_login_ip 最后登录IP
 * @property string $last_login_at 最后登录时间
 * @property string $register_at 注册或添加时间
 * @property string $updated_at 最后数据更新时间
 */
abstract class UserAccount extends Model
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%user_account}}';
    }

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
            'is_enable'     => '启用状态',
            'login_times'   => '登录次数',
            'last_login_ip' => '登录IP',
            'last_login_at' => '登录时间',
            'register_at'   => '注册时间',
            'updated_at'    => '更新时间',
        ];
    }

    const TYPE_USERNAME = 'username';
    const TYPE_EMAIL    = 'email';
    const TYPE_MOBILE   = 'mobile';
    const TYPE_NAME     = 'name';

    /**
     * @var array 程序支持额登录类型，要增减可以重载该变量
     */
    protected static $_types = [
        self::TYPE_USERNAME => '用户名',
        self::TYPE_EMAIL    => '邮箱',
        self::TYPE_MOBILE   => '手机号',
        self::TYPE_NAME     => '姓名',
    ];
    /**
     * @var array 程序支持类型的登录服务，要增减可以重载该变量
     */
    protected static $_serviceMaps = [
        self::TYPE_EMAIL    => LoginByEmail::class,
        self::TYPE_USERNAME => LoginByUsername::class,
        self::TYPE_MOBILE   => LoginByMobile::class,
        self::TYPE_NAME     => LoginByName::class,
    ];

    /**
     * 系统支持的登录类型
     *
     * @return array
     *  return [
     *      self::TYPE_USERNAME => '用户名',
     *      self::TYPE_EMAIL    => '邮箱',
     *      self::TYPE_MOBILE   => '手机号',
     *      self::TYPE_NAME     => '姓名',
     *  ];
     *
     * @throws CustomException
     */
    public static function types(): array
    {
        $supportTypes = [];
        foreach (\Yii::$app->user->loginTypes as $type) {
            if (!isset(static::$_types[$type])) {
                throw new CustomException(replace('不支持的登录类型"{type}"', [
                    '{type}' => $type
                ]));
            }
            $supportTypes[$type] = static::$_types[$type];
        }
        if (empty($supportTypes)) {
            throw new CustomException("未配置系统支持的登录类型");
        }
        return $supportTypes;
    }

    /**
     * 系统支持的登录类型对应服务
     *
     * @return array
     * @throws CustomException
     */
    public static function serviceMaps()
    {
        $supportServiceMaps = [];
        foreach (\Yii::$app->user->loginTypes as $type) {
            if (!isset(static::$_serviceMaps[$type])) {
                throw new CustomException(replace('不支持的登录类型"{type}"', [
                    '{type}' => $type
                ]));
            }
            $supportServiceMaps[$type] = static::$_serviceMaps[$type];
        }
        if (empty($supportServiceMaps)) {
            throw new CustomException('未配置系统支持的登录类型');
        }
        return $supportServiceMaps;
    }
}
