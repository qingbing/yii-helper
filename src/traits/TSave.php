<?php
/**
 * @link        http://www.phpcorner.net
 * @author      qingbing<780042175@qq.com>
 * @copyright   Chengdu Qb Technology Co., Ltd.
 */

namespace YiiHelper\traits;


use yii\db\Exception;

/**
 * 制作保存失败抛出异常片段
 *
 * Class TSave
 * @package YiiHelper\traits
 */
trait TSave
{
    /**
     * 保存数据，如果保存不成功，直接跑出异常
     * 其参数的详细文档参考
     * @link \yii\db\BaseActiveRecord::save($runValidation = true, $attributeNames = null)
     *
     * 该方法在 db-insert 和 db-update 的模型中被调用
     */
    public function saveOrException($runValidation = true, $attributeNames = null)
    {
        if (!$this->save($runValidation, $attributeNames)) {
            throw new Exception("数据保存失败");
        }
        return true;
    }
}