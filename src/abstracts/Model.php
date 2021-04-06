<?php
/**
 * @link        http://www.phpcorner.net
 * @author      qingbing<780042175@qq.com>
 * @copyright   Chengdu Qb Technology Co., Ltd.
 */

namespace YiiHelper\abstracts;

use yii\db\ActiveRecord;
use YiiHelper\traits\TSave;

/**
 * db-model 基类
 *
 * Class Model
 * @package YiiHelper\abstracts
 */
abstract class Model extends ActiveRecord
{
    // 使用 保存片段
    use TSave;
}