<?php

namespace YiiHelper\models\interfaceManager;


use YiiHelper\abstracts\Model;

/**
 * This is the model class for table "pub_interface_access_logs".
 *
 * @property int $id 自增ID
 * @property string $trace_id 客户端日志ID
 * @property int $interface_id 接口ID
 * @property string $method 请求方法[get post put...]
 * @property string|null $request_data 接口发送信息
 * @property int $is_intercept 是否参数拦截[0:否; 1:是]
 * @property int $is_success 是否成功[0:失败; 1:成功]
 * @property string $message 返回消息
 * @property int $response_code http状态返回码
 * @property string|null $response_data 接口返回信息
 * @property string|null $exts 扩展信息
 * @property float $response_time 接口真实耗时
 * @property float $use_time 整体接口耗时
 * @property string $ip 登录IP
 * @property int $uid 用户ID
 * @property string $created_at 创建时间
 */
class InterfaceAccessLogs extends Model
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
        return 'pub_interface_access_logs';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['interface_id'], 'required'],
            [['interface_id', 'is_intercept', 'is_success', 'response_code', 'uid'], 'integer'],
            [['request_data', 'response_data', 'exts', 'created_at'], 'safe'],
            [['response_time', 'use_time'], 'number'],
            [['trace_id'], 'string', 'max' => 32],
            [['method'], 'string', 'max' => 10],
            [['message'], 'string', 'max' => 100],
            [['ip'], 'string', 'max' => 15],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id'            => '日志ID',
            'trace_id'      => '链路ID',
            'interface_id'  => '接口ID',
            'method'        => '请求方法',
            'request_data'  => '发送信息',
            'is_intercept'  => '参数拦截',
            'is_success'    => '是否成功',
            'message'       => '返回消息',
            'response_code' => 'http状态码',
            'response_data' => '响应信息',
            'exts'          => '扩展信息',
            'response_time' => '接口耗时',
            'use_time'      => '整体耗时',
            'ip'            => '登录IP',
            'uid'           => '用户ID',
            'created_at'    => '创建时间',
        ];
    }
}
