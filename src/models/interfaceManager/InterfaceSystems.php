<?php

namespace YiiHelper\models\interfaceManager;


use YiiHelper\abstracts\Model;
use Zf\Helper\Exceptions\BusinessException;

/**
 * This is the model class for table "pub_interface_systems".
 *
 * @property int $id 自增ID
 * @property string $system_alias 系统别名
 * @property string $system_name 系统名称
 * @property string $description 描述
 * @property string $uri_prefix 系统调用时访问URI前缀
 * @property string $rule inner->当前系统；transfer->当前系统转发；outer->外部系统
 * @property string|null $ext 扩展字段数据
 * @property int $is_enable 系统是否启用状态[0:未启用; 1:已启用]，未启用抛异常
 * @property int $is_allow_new_interface 是否允许新接口[0:抛异常; 1:继续调用]
 * @property int $is_record_field 接口是否记录新字段，调试接口字段[0:不记录，在生成; 1:记录，用于接口调试阶段]
 * @property int $is_open_access_log 是否开启接口访问日志[0:未启用; 1:已启用]
 * @property int $is_open_validate 开启接口校验[0:不启用; 1:已启用]
 * @property int $is_strict_validate 开启严格校验[0:未启用; 1:已启用],启用是每个字段都必须在{interface_fields}中定义
 * @property int $sort_order 显示排序
 * @property string $created_at 创建时间
 * @property string $updated_at 更新时间
 *
 * @property-read integer $interfaceCount
 * @property-read Interfaces $interfaces
 */
class InterfaceSystems extends Model
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
        return 'pub_interface_systems';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['system_alias', 'system_name'], 'required'],
            [['ext', 'created_at', 'updated_at'], 'safe'],
            [['is_enable', 'is_allow_new_interface', 'is_record_field', 'is_open_access_log', 'is_open_validate', 'is_strict_validate', 'sort_order'], 'integer'],
            [['system_alias'], 'string', 'max' => 50],
            [['system_name', 'uri_prefix'], 'string', 'max' => 100],
            [['description'], 'string', 'max' => 255],
            [['rule'], 'string', 'max' => 20],
            [['system_alias'], 'unique'],
            [['system_name'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id'                     => '自增ID',
            'system_alias'           => '系统别名',
            'system_name'            => '系统名称',
            'description'            => '描述',
            'uri_prefix'             => 'URI前缀',
            'rule'                   => '系统规则类型',
            'ext'                    => '扩展字段数据',
            'is_enable'              => '启用状态',
            'is_allow_new_interface' => '接受新接口',
            'is_record_field'        => '记录新字段',
            'is_open_access_log'     => '开启访问日志',
            'is_open_validate'       => '开启接口校验',
            'is_strict_validate'     => '开启严格校验',
            'sort_order'             => '显示排序',
            'created_at'             => '创建时间',
            'updated_at'             => '更新时间',
        ];
    }

    /**
     * 获取系统中接口数量
     *
     * @return int|string
     */
    public function getInterfaceCount()
    {
        return $this->hasOne(Interfaces::class, [
            'system_alias' => 'system_alias'
        ])->count();
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInterfaces()
    {
        return $this->hasMany(Interfaces::class, [
            'system_alias' => 'system_alias'
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
        if ($this->interfaceCount > 0) {
            throw new BusinessException("该系统尚有接口关联，不能删除");
        }
        return parent::beforeDelete();
    }

    /**
     * 获取所有启用中的系统
     *
     * @param bool $isOption 是否选项卡
     * @return array
     */
    public static function all(bool $isOption = true)
    {
        $res     = self::find()
            ->select(['system_alias', 'system_name'])
            ->andWhere(['=', 'is_enable', 1])
            ->orderBy('sort_order DESC, id ASC')
            ->asArray()
            ->all();
        $options = [];
        if (!$isOption) {
            foreach ($res as $re) {
                array_push($options, [
                    'key'   => $re['system_alias'],
                    'value' => $re['system_name'],
                ]);
            }
        } else {
            foreach ($res as $re) {
                $options[$re['system_alias']] = $re['system_name'];
            }
        }
        return $options;
    }
}
