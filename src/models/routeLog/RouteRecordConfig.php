<?php

namespace YiiHelper\models\routeLog;


use YiiHelper\abstracts\Model;

/**
 * This is the model class for table "pub_route_record_config".
 *
 * @property int $id 自增ID
 * @property string $system_alias 系统别名
 * @property string $route URL路由
 * @property int $is_logging 是否记录日志[0:否; 1:是]
 * @property string $logging_message 路由操作提示
 * @property string $logging_key_fields 路由关键字
 * @property int $is_mocking 路由响应是否mock[0:否; 1:是]
 * @property string|null $mocking_response 开启mock时的响应json
 * @property string $created_at 创建时间
 * @property string $updated_at 更新时间
 */
class RouteRecordConfig extends Model
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'pub_route_record_config';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['is_logging', 'is_mocking'], 'integer'],
            [['mocking_response', 'created_at', 'updated_at'], 'safe'],
            [['system_alias'], 'string', 'max' => 50],
            [['route'], 'string', 'max' => 160],
            [['logging_message'], 'string', 'max' => 255],
            [['logging_key_fields'], 'string', 'max' => 200],
            [['system_alias', 'route'], 'unique', 'targetAttribute' => ['system_alias', 'route']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id'                 => '自增ID',
            'system_alias'       => '系统别名',
            'route'              => 'URL路由',
            'is_logging'         => '记录日志',
            'logging_message'    => '路由操作提示',
            'logging_key_fields' => '路由关键字',
            'is_mocking'         => '开启mock响应',
            'mocking_response'   => 'mock响应',
            'created_at'         => '创建时间',
            'updated_at'         => '更新时间',
        ];
    }
}
