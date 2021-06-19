<?php

namespace YiiHelper\models\interfaceManager;


use YiiHelper\abstracts\Model;
use Zf\Helper\Exceptions\BusinessException;

/**
 * This is the model class for table "pub_interface_type".
 *
 * @property int $id 自增ID
 * @property string $system_alias 系统别名
 * @property string $type 接口分类
 * @property string $type_name 类型名称
 * @property int $sort_order 排序
 * @property string $description 接口描述
 * @property string $created_at 创建时间
 * @property string $updated_at 更新时间
 *
 * @property-read integer $interfaceCount
 * @property-read Interfaces $interfaces
 */
class InterfaceType extends Model
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'pub_interface_type';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['sort_order'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['system_alias'], 'string', 'max' => 50],
            [['type'], 'string', 'max' => 32],
            [['type_name'], 'string', 'max' => 200],
            [['description'], 'string', 'max' => 255],
            [['system_alias', 'type'], 'unique', 'targetAttribute' => ['system_alias', 'type']],
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
            'type'         => '接口分类',
            'type_name'    => '类型名称',
            'sort_order'   => '排序',
            'description'  => '接口描述',
            'created_at'   => '创建时间',
            'updated_at'   => '更新时间',
        ];
    }


    /**
     * 获取拥有的子项数量
     *
     * @return int|string
     */
    public function getInterfaceCount()
    {
        return $this->hasOne(
            Interfaces::class,
            ['system_alias' => 'system_alias', 'type' => 'type']
        )->count();
    }

    /**
     * 获取拥有的子项目
     *
     * @return \yii\db\ActiveQuery
     */
    public function getInterfaces()
    {
        return $this->hasMany(
            Interfaces::class,
            ['system_alias' => 'system_alias', 'type' => 'type']
        )->orderBy("id");
    }

    /**
     * 检查是否可以删除
     *
     * @return bool
     * @throws BusinessException
     */
    public function beforeDelete()
    {
        if ($this->interfaceCount > 0) {
            throw new BusinessException("该类型尚有子项目，不能删除");
        }
        return parent::beforeDelete();
    }
}
