<?php

namespace YiiHelper\models\tableHeader;

use YiiHelper\abstracts\Model;

/**
 * This is the model class for table "pub_header".
 *
 * @property string $key 表头索引或标志，全站唯一
 * @property string $name 表头标志别名
 * @property string $description 表头描述
 * @property int $sort_order 排序
 * @property int $is_open 是否开放表头，否时管理员不可操作（不可见）
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
}
