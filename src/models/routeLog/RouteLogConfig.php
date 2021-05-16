<?php

namespace YiiHelper\models\routeLog;


use YiiHelper\abstracts\Model;

/**
 * This is the model class for table "pub_route_log_config".
 *
 * @property int $id 自增ID
 * @property string $system_alias 系统别名
 * @property string $route URL路由
 * @property int $is_logging 是否记录日志[0:否; 1:是]
 * @property string $message 路由操作提示
 * @property string $key_fields 路由关键字
 * @property string $created_at 创建时间
 * @property string $updated_at 更新时间
 */
class RouteLogConfig extends Model
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'pub_route_log_config';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['is_logging'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['system_alias'], 'string', 'max' => 50],
            [['route'], 'string', 'max' => 160],
            [['message'], 'string', 'max' => 255],
            [['key_fields'], 'string', 'max' => 200],
            [['system_alias', 'route'], 'unique', 'targetAttribute' => ['system_alias', 'route']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id'           => '自增ID',
            'system_alias' => '系统别名',
            'route'        => 'URL路由',
            'is_logging'   => '记录日志',
            'message'      => '路由操作提示',
            'key_fields'   => '路由关键字',
            'created_at'   => '创建时间',
            'updated_at'   => '更新时间',
        ];
    }
}
