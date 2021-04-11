<?php

namespace YiiHelper\models;

use YiiHelper\abstracts\Model;

/**
 * This is the model class for table "pub_interface_fields".
 *
 * @property int $id 自增ID
 * @property string $interface_alias 接口别名：systemAlias+uri_path
 * @property string $parent_alias 上级字段别名:interfaceFieldsAlias
 * @property string $field 字段名
 * @property string $alias 字段别名:interfaceAlias+parentAlias+field
 * @property string $name 字段意义
 * @property string $type 字段类型[input,output]
 * @property string $data_area 字段区域[header,file,get,post]
 * @property string $data_type 数据类型[integer|float|boolean|string|object|array|items]
 * @property string $description 描述
 * @property int $is_required 是否必填[0:否; 1:是]
 * @property string $created_at 创建时间
 * @property string $updated_at 更新时间
 *
 * 接口参数字段
 */
class InterfaceFields extends Model
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'pub_interface_fields';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['interface_alias', 'field', 'alias'], 'required'],
            [['is_required'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['interface_alias'], 'string', 'max' => 150],
            [['parent_alias', 'alias', 'description'], 'string', 'max' => 255],
            [['field', 'data_type'], 'string', 'max' => 50],
            [['name'], 'string', 'max' => 100],
            [['type', 'data_area'], 'string', 'max' => 20],
            [['interface_alias', 'parent_alias', 'field'], 'unique', 'targetAttribute' => ['interface_alias', 'parent_alias', 'field']],
            [['alias'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id'              => '自增ID',
            'interface_alias' => '接口别名：systemAlias+uri_path',
            'parent_alias'    => '上级字段别名:interfaceFieldsAlias',
            'field'           => '字段名',
            'alias'           => '字段别名:interfaceAlias+parentAlias+field',
            'name'            => '字段意义',
            'type'            => '字段类型[input,output]',
            'data_area'       => '字段区域[header,file,get,post]',
            'data_type'       => '数据类型[integer|float|boolean|string|object|array|items]',
            'description'     => '描述',
            'is_required'     => '是否必填[0:否; 1:是]',
            'created_at'      => '创建时间',
            'updated_at'      => '更新时间',
        ];
    }
}
