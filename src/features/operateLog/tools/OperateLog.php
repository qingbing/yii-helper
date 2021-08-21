<?php
/**
 * @link        http://www.phpcorner.net
 * @author      qingbing<780042175@qq.com>
 * @copyright   Chengdu Qb Technology Co., Ltd.
 */

namespace YiiHelper\features\operateLog\tools;


use YiiHelper\helpers\AppHelper;
use YiiHelper\helpers\Instance;
use Zf\Helper\Abstracts\Singleton;

/**
 * 工具 ： 操作日志
 *
 * Class OperateLog
 * @package YiiHelper\features\operateLog\tools
 */
class OperateLog extends Singleton
{
    /**
     * @param string $type
     * @param mixed $data
     * @param string $keyword
     * @param string $message
     * @return bool
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\db\Exception
     */
    public function add(string $type, $data, string $keyword = '', string $message = '')
    {
        $data = [
            'system_alias' => AppHelper::app()->getSystemAlias(),
            'type'         => $type, // 操作类型-用字符串描述
            'keyword'      => $keyword, // 关键字，用于后期筛选
            'message'      => $message, // 操作消息
            'data'         => $data, // 操作的具体内容
        ];

        $model = Instance::modelOperateLog();
        $model->setAttributes($data);
        return $model->saveOrException();
    }
}