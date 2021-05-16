<?php

namespace YiiHelper\models\routeLog;

use YiiHelper\abstracts\Model;
use YiiHelper\behaviors\IpBehavior;
use YiiHelper\behaviors\TraceIdBehavior;
use YiiHelper\behaviors\UidBehavior;

/**
 * This is the model class for table "pub_route_log".
 *
 * @property int $id 自增ID
 * @property int $route_log_config_id 系统路由配置ID
 * @property string $trace_id 客户端日志ID
 * @property int $is_success 是否成功[0:失败; 1:成功]
 * @property string $keyword 关键字，用于后期筛选
 * @property string $message 操作消息
 * @property string|null $input 操作的具体内容
 * @property string|null $output 操作的具体内容
 * @property string|null $exts 扩展信息
 * @property string $ip 登录IP
 * @property int $uid 用户ID
 * @property string $created_at 创建时间
 */
class RouteLog extends Model
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'pub_route_log';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['route_log_config_id'], 'required'],
            [['route_log_config_id', 'is_success', 'uid'], 'integer'],
            [['input', 'output', 'exts', 'created_at'], 'safe'],
            [['trace_id'], 'string', 'max' => 32],
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
            'id'                  => '自增ID',
            'route_log_config_id' => '系统路由配置ID',
            'trace_id'            => '客户端日志ID',
            'is_success'          => '是否成功[0:失败; 1:成功]',
            'keyword'             => '关键字，用于后期筛选',
            'message'             => '操作消息',
            'input'               => '操作的具体内容',
            'output'              => '操作的具体内容',
            'exts'                => '扩展信息',
            'ip'                  => '登录IP',
            'uid'                 => '用户ID',
            'created_at'          => '创建时间',
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
            IpBehavior::class,
            TraceIdBehavior::class,
        ];
    }
}