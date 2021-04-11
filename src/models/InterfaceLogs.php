<?php

namespace YiiHelper\models;

use YiiHelper\abstracts\Model;

/**
 * This is the model class for table "pub_interface_logs".
 *
 * @property int $id 自增ID
 * @property string $trace_id 客户端日志ID
 * @property int $interface_id 接口ID
 * @property string $method 请求方法[get post put...]
 * @property string $client_ip 客户端IP
 * @property string|null $request_data 接口发送信息
 * @property int $is_intercept 是否参数拦截[0:否; 1:是]
 * @property int $is_success 是否成功[0:失败; 1:成功]
 * @property string $message 返回消息
 * @property int $response_code http状态返回码
 * @property string|null $response_data 接口返回信息
 * @property float $response_time 接口真实耗时
 * @property float $use_time 整体接口耗时
 * @property string $created_at 创建时间
 * @property string $updated_at 更新时间
 *
 * 接口日志
 */
class InterfaceLogs extends Model
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'pub_interface_logs';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['interface_id'], 'required'],
            [['interface_id', 'is_intercept', 'is_success', 'response_code'], 'integer'],
            [['request_data', 'response_data', 'created_at', 'updated_at'], 'safe'],
            [['response_time', 'use_time'], 'number'],
            [['trace_id'], 'string', 'max' => 32],
            [['method'], 'string', 'max' => 10],
            [['client_ip'], 'string', 'max' => 20],
            [['message'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id'            => '自增ID',
            'trace_id'      => '客户端日志ID',
            'interface_id'  => '接口ID',
            'method'        => '请求方法[get post put...]',
            'client_ip'     => '客户端IP',
            'request_data'  => '接口发送信息',
            'is_intercept'  => '是否参数拦截[0:否; 1:是]',
            'is_success'    => '是否成功[0:失败; 1:成功]',
            'message'       => '返回消息',
            'response_code' => 'http状态返回码',
            'response_data' => '接口返回信息',
            'response_time' => '接口真实耗时',
            'use_time'      => '整体接口耗时',
            'created_at'    => '创建时间',
            'updated_at'    => '更新时间',
        ];
    }
}