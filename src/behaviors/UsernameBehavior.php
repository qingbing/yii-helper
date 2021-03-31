<?php
/**
 * @link        http://www.phpcorner.net
 * @author      qingbing<780042175@qq.com>
 * @copyright   Chengdu Qb Technology Co., Ltd.
 */

namespace YiiHelper\behaviors;


use yii\behaviors\AttributeBehavior;
use yii\db\ActiveRecord;
use YiiHelper\components\User;

/**
 * 模型中登录用户名自动填充行为
 *
 * Class UsernameBehavior
 * @package YiiHelper\behaviors
 */
class UsernameBehavior extends AttributeBehavior
{
    /**
     * @var array 操作事件及字段定义
     */
    public $attributes = [
        ActiveRecord::EVENT_BEFORE_INSERT => 'username',
    ];

    /**
     * 获取登录用户名
     *
     * @param \yii\base\Event $event
     * @return int|mixed|string
     */
    protected function getValue($event)
    {
        if (null === $this->value) {
            if (\Yii::$app->getUser()->getIsGuest() || !\Yii::$app->getUser() instanceof User) {
                return '';
            }
            return \Yii::$app->getUser()->getUsername();
        }
        return parent::getValue($event);
    }
}