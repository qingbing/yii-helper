<?php

namespace YiiHelper\models\tableHeader;

use YiiHelper\abstracts\Model;

/**
 * This is the model class for table "pub_header_option".
 *
 * @property int $id 自增ID
 * @property string $header_key 所属表头分类（来自header）
 * @property string $name 字段名
 * @property string $label 显示名
 * @property string $width 固定宽度
 * @property string $fixed 列固定:[left,right,""]
 * @property string $default 默认值,当字段没有是返回，基本无用
 * @property string $align 表格内容对齐方式:[center,left,right]
 * @property int $is_tooltip 当内容过长被隐藏时显示 tooltip
 * @property int $is_resizable 对应列是否可以通过拖动改变宽度
 * @property string $component 使用组件
 * @property string|null $params 参数内容
 * @property string $description 描述
 * @property int $sort_order 分类排序
 * @property int $is_required 是否必选，为"是"时不能没取消
 * @property int $is_default 是否默认开启
 * @property int $is_enable 是否开启
 * @property string $operate_ip 操作IP
 * @property int $operate_uid 操作UID
 * @property string $created_at 创建时间
 * @property string $updated_at 更新时间
 */
class HeaderOption extends Model
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'pub_header_option';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['header_key', 'name', 'label'], 'required'],
            [['is_tooltip', 'is_resizable', 'sort_order', 'is_required', 'is_default', 'is_enable', 'operate_uid'], 'integer'],
            [['params', 'created_at', 'updated_at'], 'safe'],
            [['header_key', 'default'], 'string', 'max' => 100],
            [['name', 'component'], 'string', 'max' => 60],
            [['label'], 'string', 'max' => 50],
            [['width', 'fixed', 'align'], 'string', 'max' => 20],
            [['description'], 'string', 'max' => 255],
            [['operate_ip'], 'string', 'max' => 15],
            [['header_key', 'name'], 'unique', 'targetAttribute' => ['header_key', 'name']],
            [['header_key', 'label'], 'unique', 'targetAttribute' => ['header_key', 'label']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id'           => '自增ID',
            'header_key'   => '表头标志',
            'name'         => '字段名',
            'label'        => '显示名',
            'width'        => '固定宽度',
            'fixed'        => '列固定:[left,right,\"\"]',
            'default'      => '默认值,当字段没有是返回，基本无用',
            'align'        => '表格内容对齐方式:[center,left,right]',
            'is_tooltip'   => '当内容过长被隐藏时显示 tooltip',
            'is_resizable' => '对应列是否可以通过拖动改变宽度',
            'component'    => '使用组件',
            'params'       => '参数内容',
            'description'  => '描述',
            'sort_order'   => '分类排序',
            'is_required'  => '是否必选，为\"是\"时不能没取消',
            'is_default'   => '是否默认开启',
            'is_enable'    => '是否开启',
            'operate_ip'   => '操作IP',
            'operate_uid'  => '操作UID',
            'created_at'   => '创建时间',
            'updated_at'   => '更新时间',
        ];
    }
}
