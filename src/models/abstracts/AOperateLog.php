<?php
/**
 * @link        http://www.phpcorner.net
 * @author      qingbing<780042175@qq.com>
 * @copyright   Chengdu Qb Technology Co., Ltd.
 */

namespace YiiHelper\models\abstracts;


use YiiHelper\abstracts\Model;
use YiiHelper\behaviors\IpBehavior;
use YiiHelper\behaviors\TraceIdBehavior;
use YiiHelper\behaviors\UidBehavior;
use YiiHelper\behaviors\NicknameBehavior;

/**
 * 操作日志抽象类
 * 1. 继承时需要定义日志表名
 * 2. 继承时需要实现日志支持类型:types()
 * 3. db-sql 参考 sql/operate_log.sql
 *
 * Class AOperateLog
 * @package YiiHelper\models\abstracts
 */
abstract class AOperateLog extends Model
{
    /**
     * 获取所有日志类型
     *
     * @return array
     */
    abstract public static function types();

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

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['data', 'created_at'], 'safe'],
            [['uid'], 'integer'],
            [['type', 'trace_id'], 'string', 'max' => 32],
            [['keyword'], 'string', 'max' => 100],
            [['message'], 'string', 'max' => 255],
            [['ip'], 'string', 'max' => 15],
            [['nickname'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id'         => 'ID',
            'trace_id'   => 'Trace Id',
            'type'       => 'Type',
            'keyword'    => 'Keyword',
            'message'    => 'Message',
            'data'       => 'Data',
            'ip'         => 'Ip',
            'uid'        => 'Uid',
            'nickname'   => 'Nickname',
            'created_at' => 'Created At',
        ];
    }

    /**
     * 添加一条记录
     *
     * @param string $type
     * @param string $keyword
     * @param mixed $data
     * @param string $message
     * @return bool
     */
    public static function add(string $type, $data, string $keyword = '', string $message = '')
    {
        $data = [
            'type'    => $type, // 操作类型-用字符串描述
            'keyword' => $keyword, // 关键字，用于后期筛选
            'message' => $message, // 操作消息
            'data'    => $data, // 操作的具体内容
        ];

        $model = new static();
        $model->setAttributes($data);
        return $model->saveOrException();
    }
}