<?php

namespace YiiHelper\models\interfaceManager;


use YiiHelper\abstracts\Model;

/**
 * This is the model class for table "pub_interface_route_logs".
 *
 * @property int $id 自增ID
 * @property string $trace_id 客户端日志ID
 * @property int $interface_id 接口ID
 * @property string $method 请求方法[get post put...]
 * @property int $is_success 是否成功[0:失败; 1:成功]
 * @property string $keyword 关键字，用于后期筛选
 * @property string $message 操作消息
 * @property string|null $input 请求内容
 * @property string|null $output 响应内容
 * @property string|null $exts 扩展信息
 * @property float $use_time 路由耗时
 * @property string $ip 登录IP
 * @property int $uid 用户ID
 * @property string $created_at 创建时间
 */
class InterfaceRouteLogs extends Model
{
    const METHOD_GET  = 'get';
    const METHOD_POST = 'post';
    const METHOD_PUT  = 'put';

    /**
     * 获取所有请求方式
     *
     * @return array
     */
    public static function methods()
    {
        return [
            self::METHOD_GET  => 'get',
            self::METHOD_POST => 'post',
            self::METHOD_PUT  => 'put',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'pub_interface_route_logs';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['interface_id'], 'required'],
            [['interface_id', 'is_success', 'uid'], 'integer'],
            [['input', 'output', 'exts', 'created_at'], 'safe'],
            [['use_time'], 'number'],
            [['trace_id'], 'string', 'max' => 32],
            [['method'], 'string', 'max' => 10],
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
            'trace_id'     => '链路ID',
            'interface_id' => '接口ID',
            'method'       => '请求方法',
            'is_success'   => '是否成功',
            'keyword'      => '关键字',
            'message'      => '操作消息',
            'input'        => '请求内容',
            'output'       => '响应内容',
            'exts'         => '扩展信息',
            'use_time'     => '路由耗时',
            'ip'           => '登录IP',
            'uid'          => '用户ID',
            'created_at'   => '创建时间',
        ];
    }
}
