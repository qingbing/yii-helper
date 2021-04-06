<?php
/**
 * @link        http://www.phpcorner.net
 * @author      qingbing<780042175@qq.com>
 * @copyright   Chengdu Qb Technology Co., Ltd.
 */

namespace YiiHelper\models;


use yii\db\ActiveRecord;
use YiiHelper\traits\TSave;

/**
 * 模型基类
 *
 * Class BaseModel
 * @package YiiHelper\models\abstracts
 */
class BaseModel extends ActiveRecord
{
    // 使用 保存片段
    use TSave;
}