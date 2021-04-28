<?php

namespace YiiHelper\models;

use YiiHelper\abstracts\Model;

/**
 * This is the model class for table "pub_interfaces".
 *
 * @property int $id 自增ID
 * @property string $system_alias 系统别名
 * @property string $uri_path 接口的path
 * @property string $alias 接口别名：systemAlias+uri_path
 * @property string $name 接口名称
 * @property string $type 接口类型[view:查看接口;operate:操作接口]
 * @property string $description 描述
 * @property int $log_type 日志记录方式[0:随系统; 1:强制开启；2:强制关闭]
 * @property int $is_open_validate 开启日志检查[0:未启用; 1:已启用]
 * @property int $is_strict_validate 开启严格检查[0:未启用; 1:已启用],启用是每个字段都必须在{interface_fields}中定义
 * @property string $created_at 创建时间
 * @property string $updated_at 更新时间
 *
 * 接口信息
 */
class Interfaces extends Model
{
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
            [['log_type', 'is_open_validate', 'is_strict_validate'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['system_alias'], 'string', 'max' => 50],
            [['uri_path'], 'string', 'max' => 200],
            [['alias'], 'string', 'max' => 150],
            [['name'], 'string', 'max' => 100],
            [['type'], 'string', 'max' => 20],
            [['description'], 'string', 'max' => 255],
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
            'id'                 => '自增ID',
            'system_alias'       => '系统别名',
            'uri_path'           => '接口path',
            'alias'              => '接口别名',
            'name'               => '接口名称',
            'type'               => '接口类型',
            'description'        => '描述',
            'log_type'           => '日志记录方式',
            'is_open_validate'   => '开启接口校验',
            'is_strict_validate' => '开启严格校验',
            'created_at'         => '创建时间',
            'updated_at'         => '更新时间',
        ];
    }
}
