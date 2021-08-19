<?php

namespace YiiHelper\models\routeManager;

use YiiHelper\abstracts\Model;

/**
 * This is the model class for table "{{%route_systems}}".
 *
 * @property int $id 自增ID
 * @property string $code 系统别名
 * @property string $name 系统名称
 * @property string $description 描述
 * @property string $uri_prefix 系统调用时访问URI前缀
 * @property string $type 系统类型[inner->当前系统；transfer->当前系统转发；outer->外部系统]
 * @property string $rule 参数验证通过后的规则，eg：可以加入时间戳，sign验证等，每一个规则都需要代码支持
 * @property string|null $ext 扩展字段数据
 * @property int $is_enable 系统是否启用状态[0:未启用; 1:已启用]，未启用抛异常
 * @property int $is_allow_new_interface 是否允许未注册接口[0:不允许; 1:允许]
 * @property int $is_record_field 是否记录新接口文档[0:抛异常; 1:继续调用]
 * @property int $is_open_validate 开启接口校验[0:不启用; 1:已启用]
 * @property int $is_strict_validate 开启严格校验[0:未启用; 1:已启用],启用是每个字段都必须在{interface_fields}中定义
 * @property int $sort_order 显示排序
 * @property string $created_at 创建时间
 * @property string $updated_at 更新时间
 */
class RouteSystems extends Model
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%route_systems}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['code', 'name'], 'required'],
            [['ext', 'created_at', 'updated_at'], 'safe'],
            [['is_enable', 'is_allow_new_interface', 'is_record_field', 'is_open_validate', 'is_strict_validate', 'sort_order'], 'integer'],
            [['code'], 'string', 'max' => 50],
            [['name', 'uri_prefix'], 'string', 'max' => 100],
            [['description'], 'string', 'max' => 255],
            [['type', 'rule'], 'string', 'max' => 20],
            [['code'], 'unique'],
            [['name'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id'                     => '自增ID',
            'code'                   => '系统别名',
            'name'                   => '系统名称',
            'description'            => '描述',
            'uri_prefix'             => '系统调用时访问URI前缀',
            'type'                   => '系统类型[inner->当前系统；transfer->当前系统转发；outer->外部系统]',
            'rule'                   => '参数验证通过后的规则，eg：可以加入时间戳，sign验证等，每一个规则都需要代码支持',
            'ext'                    => '扩展字段数据',
            'is_enable'              => '系统是否启用状态[0:未启用; 1:已启用]，未启用抛异常',
            'is_allow_new_interface' => '是否允许未注册接口[0:不允许; 1:允许]',
            'is_record_field'        => '是否记录新接口文档[0:抛异常; 1:继续调用]',
            'is_open_validate'       => '开启接口校验[0:不启用; 1:已启用]',
            'is_strict_validate'     => '开启严格校验[0:未启用; 1:已启用],启用是每个字段都必须在{interface_fields}中定义',
            'sort_order'             => '显示排序',
            'created_at'             => '创建时间',
            'updated_at'             => '更新时间',
        ];
    }
}
