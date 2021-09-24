<?php

namespace YiiHelper\models\oauth;


use YiiHelper\abstracts\Model;

/**
 * This is the model class for table "configure_oauth_user".
 *
 * @property int $id 自增ID
 * @property string $system_code 系统别名
 * @property string $uuid 用户/系统标识
 * @property string|null $app_key 公钥
 * @property string|null $app_secret 私钥
 * @property string $expire_ip 有效IP地址
 * @property string $expire_begin_at 生效日期
 * @property string $expire_end_at 失效日期
 * @property string $created_at 创建时间
 * @property string $updated_at 更新时间
 */
class OauthUser extends Model
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%oauth_user}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['app_key', 'app_secret'], 'string'],
            [['expire_begin_at', 'expire_end_at', 'created_at', 'updated_at'], 'safe'],
            [['system_code', 'uuid'], 'string', 'max' => 50],
            [['expire_ip'], 'string', 'max' => 255],
            [['system_code', 'uuid'], 'unique', 'targetAttribute' => ['system_code', 'uuid']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id'              => '自增ID',
            'system_code'     => '系统别名',
            'uuid'            => '用户/系统标识',
            'app_key'         => '公钥',
            'app_secret'      => '私钥',
            'expire_ip'       => '有效IP地址',
            'expire_begin_at' => '生效日期',
            'expire_end_at'   => '失效日期',
            'created_at'      => '创建时间',
            'updated_at'      => '更新时间',
        ];
    }
}
