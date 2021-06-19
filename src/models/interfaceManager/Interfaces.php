<?php

namespace YiiHelper\models\interfaceManager;


use YiiHelper\abstracts\Model;
use Zf\Helper\Exceptions\BusinessException;

/**
 * This is the model class for table "pub_interfaces".
 *
 * @property int $id 自增ID
 * @property string $system_alias 系统别名
 * @property string $uri_path 接口的path
 * @property string $alias 接口别名：systemAlias+uri_path
 * @property string $name 接口名称
 * @property string $type 接口分类
 * @property int $is_operate 是否操作类[0:否; 1:是]
 * @property string $description 描述
 * @property int $record_field_type 接口是否记录新字段[0:随系统; 1:强制开启；2:强制关闭]
 * @property int $access_log_type 日志记录方式[0:随系统; 1:强制开启；2:强制关闭]
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
 *
 * @property-read int $optionCount 子项目数量
 * @property-read InterfaceFields[] $options 子项目
 */
class Interfaces extends Model
{
    const RECORD_FIELD_TYPE_AUTO  = 0;
    const RECORD_FIELD_TYPE_OPEN  = 1;
    const RECORD_FIELD_TYPE_CLOSE = 2;

    /**
     * 字段记录方式
     *
     * @return array
     */
    public static function recordFieldTypes()
    {
        return [
            self::RECORD_FIELD_TYPE_AUTO  => '随系统', // 0
            self::RECORD_FIELD_TYPE_OPEN  => '强制开启', // 1
            self::RECORD_FIELD_TYPE_CLOSE => '强制关闭', // 2
        ];
    }

    const ACCESS_LOG_TYPE_AUTO  = 0;
    const ACCESS_LOG_TYPE_OPEN  = 1;
    const ACCESS_LOG_TYPE_CLOSE = 2;

    /**
     * 日志记录方式
     *
     * @return array
     */
    public static function accessLogTypes()
    {
        return [
            self::ACCESS_LOG_TYPE_AUTO  => '随系统', // 0
            self::ACCESS_LOG_TYPE_OPEN  => '强制开启', // 1
            self::ACCESS_LOG_TYPE_CLOSE => '强制关闭', // 2
        ];
    }

    const VALIDATE_TYPE_TYPE_AUTO  = 0;
    const VALIDATE_TYPE_TYPE_OPEN  = 1;
    const VALIDATE_TYPE_TYPE_CLOSE = 2;

    /**
     * 接口校验方式
     *
     * @return array
     */
    public static function validateTypes()
    {
        return [
            self::VALIDATE_TYPE_TYPE_AUTO  => '随系统', // 0
            self::VALIDATE_TYPE_TYPE_OPEN  => '强制开启', // 1
            self::VALIDATE_TYPE_TYPE_CLOSE => '强制关闭', // 2
        ];
    }

    const STRICT_VALIDATE_TYPE_AUTO  = 0;
    const STRICT_VALIDATE_TYPE_OPEN  = 1;
    const STRICT_VALIDATE_TYPE_CLOSE = 2;

    /**
     * 严格校验方式
     *
     * @return array
     */
    public static function strictValidateTypes()
    {
        return [
            self::STRICT_VALIDATE_TYPE_AUTO  => '随系统', // 0
            self::STRICT_VALIDATE_TYPE_OPEN  => '强制开启', // 1
            self::STRICT_VALIDATE_TYPE_CLOSE => '强制关闭', // 2
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'pub_interfaces';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['system_alias', 'alias'], 'required'],
            [['is_operate', 'record_field_type', 'access_log_type', 'validate_type', 'strict_validate_type', 'is_open_route_log', 'is_open_mocking', 'is_use_custom_mock'], 'integer'],
            [['mock_response', 'created_at', 'updated_at'], 'safe'],
            [['system_alias'], 'string', 'max' => 50],
            [['uri_path', 'route_log_key_fields'], 'string', 'max' => 200],
            [['alias'], 'string', 'max' => 150],
            [['name'], 'string', 'max' => 100],
            [['type'], 'string', 'max' => 20],
            [['description', 'route_log_message'], 'string', 'max' => 255],
            [['system_alias', 'uri_path'], 'unique', 'targetAttribute' => ['system_alias', 'uri_path']],
            [['alias'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id'                   => '自增ID',
            'system_alias'         => '系统别名',
            'uri_path'             => '接口的path',
            'alias'                => '接口别名',
            'name'                 => '接口名称',
            'type'                 => '接口分类',
            'is_operate'           => '是否操作类',
            'description'          => '描述',
            'record_field_type'    => '字段记录方式',
            'access_log_type'      => '日志记录方式',
            'validate_type'        => '接口校验方式',
            'strict_validate_type' => '开启严格校验',
            'is_open_route_log'    => '开启路由日志',
            'route_log_message'    => '路由操作提示',
            'route_log_key_fields' => '路由关键字',
            'is_open_mocking'      => '开启mock',
            'is_use_custom_mock'   => '自定义mock',
            'mock_response'        => '自定义mock响应',
            'created_at'           => '创建时间',
            'updated_at'           => '更新时间',
        ];
    }

    /**
     * 获取拥有的子项数量
     *
     * @return int|string
     */
    public function getOptionCount()
    {
        return $this->hasMany(
            InterfaceFields::class,
            ['interface_alias' => 'alias']
        )
            ->andWhere(['parent_field' => ''])
            ->count();
    }

    /**
     * 获取拥有的子项目
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOptions()
    {
        return $this->hasMany(
            InterfaceFields::class,
            ['interface_alias' => 'alias']
        )
            ->orderBy("alias")
            ->andWhere(['parent_field' => '']);
    }

    /**
     * json需要返回的字段
     *
     * @return array|false
     */
    public function fields()
    {
        return array_merge(parent::fields(), [
            'optionCount',
            'options',
        ]);
    }

    /**
     * 检查是否可以删除
     *
     * @return bool
     * @throws BusinessException
     */
    public function beforeDelete()
    {
        if ($this->optionCount > 0) {
            throw new BusinessException("该类型尚有子项目，不能删除");
        }
        return parent::beforeDelete();
    }
}
