<?php
/**
 * @link        http://www.phpcorner.net
 * @author      qingbing<780042175@qq.com>
 * @copyright   Chengdu Qb Technology Co., Ltd.
 */

namespace YiiHelper\behaviors;


use yii\base\Behavior;

/**
 * 行为 : web 请求的附加行为
 *
 * Class RequestBehavior
 * @package YiiHelper\behaviors
 */
class RequestBehavior extends Behavior
{
    /**
     * @return null|string
     */
    public function getShortContentType()
    {
        $rawContentType = $this->owner->getContentType();
        if (!$rawContentType) {
            return $rawContentType;
        }
        if (($pos = strpos($rawContentType, ';')) !== false) {
            // e.g. text/html; charset=UTF-8
            $contentType = substr($rawContentType, 0, $pos);
        } else {
            $contentType = $rawContentType;
        }
        return $contentType;
    }

    /**
     * 获取所有请求参数
     *
     * @return array
     */
    public function getAllParams()
    {
        return array_merge($this->owner->getQueryParams(), $this->owner->getBodyParams());
    }
}