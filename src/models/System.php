<?php

namespace YiiHelper\models;

use YiiHelper\abstracts\Model;

/**
 * This is the model class for table "pub_system".
 *
 * @property int $id 自增ID
 * @property string $alias 系统别名
 * @property string $name 系统名称
 * @property string $description 描述
 * @property string $uri_prefix 系统调用时访问URI前缀
 * @property string $rule inner->当前系统；transfer->当前系统转发；outer->外部系统
 * @property string|null $ext 扩展字段数据
 * @property int $is_enable 系统是否启用状态[0:未启用; 1:已启用]，未启用抛异常
 * @property int $is_continue 接口未申明(pub_interfaces)是否继续调用[0:抛异常; 1:继续调用]
 * @property int $is_record_field 接口是否记录新字段，调试接口字段[0:不记录，在生成; 1:记录，用于接口调试阶段]
 * @property int $is_open_log 是否开启接口日志[0:未启用; 1:已启用]
 * @property int $sort_order 显示排序
 * @property string $created_at 创建时间
 * @property string $updated_at 更新时间
 *
 * 接口系统
 */
class System extends Model
{
    const RULE_INNER    = "inner";
    const RULE_TRANSFER = "transfer";
    const RULE_OUTER    = "outer";

    /**
     * 系统规则
     *
     * @return array
     */
    public static function systemRules()
    {
        return [
            self::RULE_INNER    => "当前系统",
            self::RULE_TRANSFER => "内部系统", // 带转发
            self::RULE_OUTER    => "外部系统", // 带转发
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'pub_system';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['alias', 'name'], 'required'],
            [['ext', 'created_at', 'updated_at'], 'safe'],
            [['is_enable', 'is_continue', 'is_record_field', 'is_open_log', 'sort_order'], 'integer'],
            [['alias'], 'string', 'max' => 50],
            [['name', 'uri_prefix'], 'string', 'max' => 100],
            [['description'], 'string', 'max' => 255],
            [['rule'], 'string', 'max' => 20],
            [['alias'], 'unique'],
            [['name'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id'              => '自增ID',
            'alias'           => '系统别名',
            'name'            => '系统名称',
            'description'     => '描述',
            'uri_prefix'      => 'URI前缀',
            'rule'            => '调用规则',
            'ext'             => '扩展数据',
            'is_enable'       => '启用状态',
            'is_continue'     => '未申明是否调用',
            'is_record_field' => '是否记录新字段',
            'is_open_log'     => '开启接口日志',
            'sort_order'      => '显示排序',
            'created_at'      => '创建时间',
            'updated_at'      => '更新时间',
        ];
    }

    /**
     * 获取所有启用中的系统
     *
     * @param bool $isOption 是否选项卡
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function all(bool $isOption = true)
    {
        $res     = self::find()
            ->select(['alias', 'name'])
            ->andWhere(['=', 'is_enable', 1])
            ->orderBy('sort_order DESC, id ASC')
            ->asArray()
            ->all();
        $options = [];
        if (!$isOption) {
            foreach ($res as $re) {
                array_push($options, [
                    'key'   => $re['alias'],
                    'value' => $re['name'],
                ]);
            }
        } else {
            foreach ($res as $re) {
                $options[$re['alias']] = $re['name'];
            }
        }
        return $options;
    }
}
