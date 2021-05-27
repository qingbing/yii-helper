<?php

namespace YiiHelper\models\tableHeader;

use YiiHelper\abstracts\Model;
use Zf\Helper\Exceptions\BusinessException;

/**
 * This is the model class for table "pub_header".
 *
 * @property string $key 表头索引或标志，全站唯一
 * @property string $name 表头标志别名
 * @property string $description 表头描述
 * @property int $sort_order 排序
 * @property int $is_open 是否开放表头，否时管理员不可操作（不可见）
 *
 * @property-read int $optionCount 拥有的子项数量
 * @property-read HeaderOption[] $options 拥有的子项目
 */
class Header extends Model
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'pub_header';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['key', 'name'], 'required'],
            [['sort_order', 'is_open'], 'integer'],
            [['key', 'name', 'description'], 'string', 'max' => 255],
            [['name'], 'unique'],
            [['key'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'key'         => '表头标志',
            'name'        => '表头别名',
            'description' => '描述',
            'sort_order'  => '排序',
            'is_open'     => '公开表头',
        ];
    }

    /**
     * 获取拥有的子项数量
     *
     * @return int|string
     */
    public function getOptionCount()
    {
        return $this->hasOne(
            HeaderOption::class,
            ['header_key' => 'key']
        )->count();
    }

    /**
     * 获取拥有的子项目
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOptions()
    {
        return $this->hasOne(
            HeaderOption::class,
            ['header_key' => 'key']
        );
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
