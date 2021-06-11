<?php

namespace YiiHelper\models\interfaceLogs;

use YiiHelper\abstracts\Model;
use Zf\Helper\Exceptions\BusinessException;

/**
 * This is the model class for table "pub_interface_fields".
 *
 * @property int $id 自增ID
 * @property string $interface_alias 接口别名：systemAlias+uri_path
 * @property string $parent_field 上级字段别名
 * @property string $field 字段名
 * @property string $alias 字段别名:interfaceAlias+parentAlias+field
 * @property string $name 字段意义
 * @property string $type 字段类型[input,output]
 * @property string $data_area 字段区域[header,file,get,post]
 * @property string $data_type 数据类型[integer|float|boolean|string|object|array|items]
 * @property string $description 描述
 * @property int $is_required 是否必填[0:否; 1:是]
 * @property int $is_ignore 是否忽略字段，这些字段后台不接收[0:否; 1:是]
 * @property int $is_last_level 最后级别，不含子字段
 * @property string $created_at 创建时间
 * @property string $updated_at 更新时间
 *
 * @property-read int $optionCount 子项目数量
 * @property-read InterfaceFields[] $options 子项目
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
            [['is_required', 'is_ignore', 'is_last_level'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['interface_alias'], 'string', 'max' => 150],
            [['parent_field', 'alias', 'description'], 'string', 'max' => 255],
            [['field', 'data_type'], 'string', 'max' => 50],
            [['name'], 'string', 'max' => 100],
            [['type', 'data_area'], 'string', 'max' => 20],
            [['interface_alias', 'parent_field', 'field'], 'unique', 'targetAttribute' => ['interface_alias', 'parent_field', 'field']],
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
            'interface_alias' => '接口别名',
            'parent_field'    => '上级字段别名',
            'field'           => '字段名',
            'alias'           => '字段别名',
            'name'            => '字段意义',
            'type'            => '字段类型',
            'data_area'       => '字段区域',
            'data_type'       => '数据类型',
            'description'     => '描述',
            'is_required'     => '是否必填',
            'is_ignore'       => '忽略字段',
            'is_last_level'   => '最后级别',
            'created_at'      => '创建时间',
            'updated_at'      => '更新时间',
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
            [
                'interface_alias' => 'interface_alias',
                'parent_field'    => 'field',
            ]
        )->count();
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
            [
                'interface_alias' => 'interface_alias',
                'parent_field'    => 'field',
            ]
        )
            ->orderBy("alias");
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
