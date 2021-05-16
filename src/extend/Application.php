<?php
/**
 * @link        http://www.phpcorner.net
 * @author      qingbing<780042175@qq.com>
 * @copyright   Chengdu Qb Technology Co., Ltd.
 */

namespace YiiHelper\extend;


use yii\base\InvalidRouteException;
use YiiHelper\components\CacheHelper;
use YiiHelper\components\InterfaceLog;
use YiiHelper\components\RouteManager;
use Zf\Helper\DataStore;

/**
 * 扩展功能
 *
 * Class Application
 * @package YiiHelper\extend
 *
 * @property-read RouteManager $routeManager
 * @property-read InterfaceLog $interfaceLog
 * @property-read CacheHelper $cacheHelper
 */
class Application extends \yii\web\Application
{
    /**
     * @var string 默认中转传输路由
     */
    public $transmitRoute = 'transmit/index';
    /**
     * @var string 默认系统
     */
    public $defaultSystem = 'portal';

    /**
     * 获取当前请求的系统标记
     *
     * @return string
     */
    public function getSystemAlias()
    {
        return DataStore::get(__CLASS__ . ":system", function () {
            $system = $this->getRequest()->getHeaders()->get('x-system');
            if (null === $system || $system == $this->defaultSystem) {
                return $this->defaultSystem;
            }
            return $system;
        });
    }

    /**
     * @inheritDoc
     *
     * @throws InvalidRouteException
     */
    public function runAction($route, $params = [])
    {
        if ($this->getSystemAlias() == $this->defaultSystem) {
            return parent::runAction($route, $params);
        }
        return parent::runAction($this->transmitRoute, $params);
    }
}