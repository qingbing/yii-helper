<?php
/**
 * @link        http://www.phpcorner.net
 * @author      qingbing<780042175@qq.com>
 * @copyright   Chengdu Qb Technology Co., Ltd.
 */

namespace YiiHelper\tools\lifeCollection;


use yii\base\BaseObject;
use yii\di\Instance;

/**
 * 工具 : 生命周期集合管理
 *
 * Class LifeCollection
 * @package YiiHelper\tools\lifeCollection
 *
 * @method array getAllData()
 * @method bool pushData($data)
 * @method bool isExpireData($data)
 * @method bool pop(int $count)
 * @method bool getCount()
 * @method bool clearOverdue()
 */
class LifeCollection extends BaseObject
{
    /**
     * @var int 生命周期时间
     */
    public $expireTtl = 7200;
    /**
     * @var Store
     */
    public $store = [
        'class' => \YiiHelper\tools\lifeCollection\drivers\RedisStore::class,
    ];

    /**
     * @inheritDoc
     * @throws \yii\base\InvalidConfigException
     */
    public function init()
    {
        $this->store = Instance::ensure($this->store, Store::class);
        if ($this->expireTtl > 0) {
            $this->store->expireTtl = $this->expireTtl;
        }
    }

    /**
     * 访问存储驱动
     *
     * @param string $name
     * @param array $params
     * @return mixed
     */
    public function __call($name, $params)
    {
        return call_user_func_array([$this->store, $name], $params);
    }
}