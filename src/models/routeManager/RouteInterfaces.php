<?php

namespace YiiHelper\models\routeManager;

use YiiHelper\abstracts\Model;
use YiiHelper\helpers\AppHelper;

/**
 * This is the model class for table "{{%route_interfaces}}".
 *
 * @property int $id 自增ID
 * @property string $system_code 系统别名
 * @property string $url_path 接口的path
 * @property string $name 接口名称
 * @property int $is_operate 是否操作类[0:否; 1:是]
 * @property string $description 描述
 * @property int $record_field_type 接口是否记录新字段[0:随系统; 1:强制开启；2:强制关闭]
 * @property int $validate_type 接口校验方式[0:随系统; 1:强制开启；2:强制关闭]
 * @property int $strict_validate_type 开启严格校验[0:随系统; 1:强制开启；2:强制关闭]
 * @property int $is_open_route_log 是否开启路由日志[0:否; 1:是]
 * @property string $route_log_message 路由操作提示
 * @property string $route_log_key_fields 路由关键字
 * @property int $is_open_mocking 路由响应是否mock[0:否; 1:是]
 * @property int $is_use_custom_mock 是否使用自定义mock
 * @property string|null $mock_response 开启mock时的响应json
 * @property string $created_at 创建时间
 * @property string $updated_at 更新时间
 */
class RouteInterfaces extends Model
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%route_interfaces}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['system_code'], 'required'],
            [['is_operate', 'record_field_type', 'validate_type', 'strict_validate_type', 'is_open_route_log', 'is_open_mocking', 'is_use_custom_mock'], 'integer'],
            [['mock_response', 'created_at', 'updated_at'], 'safe'],
            [['system_code'], 'string', 'max' => 50],
            [['url_path', 'route_log_key_fields'], 'string', 'max' => 200],
            [['name'], 'string', 'max' => 100],
            [['description', 'route_log_message'], 'string', 'max' => 255],
            [['url_path'], 'unique'],
            [['system_code', 'name'], 'unique', 'targetAttribute' => ['system_code', 'name']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id'                   => '自增ID',
            'system_code'          => '系统别名',
            'url_path'             => '接口的path',
            'name'                 => '接口名称',
            'is_operate'           => '是否操作类[0:否; 1:是]',
            'description'          => '描述',
            'record_field_type'    => '接口是否记录新字段[0:随系统; 1:强制开启；2:强制关闭]',
            'validate_type'        => '接口校验方式[0:随系统; 1:强制开启；2:强制关闭]',
            'strict_validate_type' => '开启严格校验[0:随系统; 1:强制开启；2:强制关闭]',
            'is_open_route_log'    => '是否开启路由日志[0:否; 1:是]',
            'route_log_message'    => '路由操作提示',
            'route_log_key_fields' => '路由关键字',
            'is_open_mocking'      => '路由响应是否mock[0:否; 1:是]',
            'is_use_custom_mock'   => '是否使用自定义mock',
            'mock_response'        => '开启mock时的响应json',
            'created_at'           => '创建时间',
            'updated_at'           => '更新时间',
        ];
    }
}
