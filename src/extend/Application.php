<?php
/**
 * @link        http://www.phpcorner.net
 * @author      qingbing<780042175@qq.com>
 * @copyright   Chengdu Qb Technology Co., Ltd.
 */

namespace YiiHelper\extend;


use yii\base\InvalidRouteException;

/**
 * 扩展功能
 *
 * Class Application
 * @package YiiHelper\extend
 */
class Application extends \yii\web\Application
{
    /**
     * @inheritDoc
     *
     * @throws InvalidRouteException
     */
    public function runAction($route, $params = [])
    {
        try {
            return parent::runAction($route, $params);
        }
        catch (InvalidRouteException $e) {
            return parent::runAction($this->defaultRoute, $params);
        }
    }
}