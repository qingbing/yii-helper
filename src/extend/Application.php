<?php
/**
 * @link        http://www.phpcorner.net
 * @author      qingbing<780042175@qq.com>
 * @copyright   Chengdu Qb Technology Co., Ltd.
 */

namespace YiiHelper\extend;


use yii\base\InvalidRouteException;
use YiiHelper\components\CacheHelper;
use YiiHelper\components\interfaceManager\InterfaceManager;
use YiiHelper\components\User;
use Zf\Helper\DataStore;

/**
 * 扩展功能
 *
 * Class Application
 * @package YiiHelper\extend
 *
 * @property-read InterfaceManager $interfaceManager
 * @property-read CacheHelper $cacheHelper
 * @property-read User $user
 *
 * @method User getUser()
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
     * 获取配置的 params 参数
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function getParam(string $key, $default = null)
    {
        return $this->params[$key] ?? $default;
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