<?php
/**
 * @link        http://www.phpcorner.net
 * @author      qingbing<780042175@qq.com>
 * @copyright   Chengdu Qb Technology Co., Ltd.
 */

namespace YiiHelper\models\operateLog;

use YiiHelper\abstracts\Model;
use YiiHelper\behaviors\IpBehavior;
use YiiHelper\behaviors\NicknameBehavior;
use YiiHelper\behaviors\TraceIdBehavior;
use YiiHelper\behaviors\UidBehavior;

/**
 * @property int $id 自增ID
 * @property string $trace_id 客户端日志ID
 * @property string $system_alias 系统别名
 * @property string $type 操作类型-用字符串描述
 * @property string $keyword 关键字，用于后期筛选
 * @property string $message 操作消息
 * @property string|null $data 操作的具体内容
 * @property string $ip 登录IP
 * @property int $uid 用户ID
 * @property string $nickname 用户昵称
 * @property string $created_at 创建时间
 *
 * Class OperateLog
 * @package YiiHelper\models\operateLog
 */
class OperateLog extends Model
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'pub_operate_log';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['data', 'created_at'], 'safe'],
            [['uid'], 'integer'],
            [['trace_id', 'type'], 'string', 'max' => 32],
            [['system_alias', 'nickname'], 'string', 'max' => 50],
            [['keyword'], 'string', 'max' => 100],
            [['message'], 'string', 'max' => 255],
            [['ip'], 'string', 'max' => 15],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id'           => '自增ID',
            'trace_id'     => '日志ID',
            'system_alias' => '系统别名',
            'type'         => '操作类型',
            'keyword'      => '关键字',
            'message'      => '操作消息',
            'data'         => '操作内容',
            'ip'           => '登录IP',
            'uid'          => '用户ID',
            'nickname'     => '用户昵称',
            'created_at'   => '创建时间',
        ];
    }

    /**
     * 绑定 behavior
     *
     * @return array
     */
    public function behaviors()
    {
        return [
            UidBehavior::class,
            NicknameBehavior::class,
            IpBehavior::class,
            TraceIdBehavior::class
        ];
    }
}
