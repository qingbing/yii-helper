<?php
/**
 * @link        http://www.phpcorner.net
 * @author      qingbing<780042175@qq.com>
 * @copyright   Chengdu Qb Technology Co., Ltd.
 */

namespace YiiHelper\proxy;

use YiiHelper\proxy\base\InnerProxy;

class PortalProxy extends InnerProxy
{
    const URL_HEALTH = 'health';
    const URL_TEST   = 'test/test';

    public function health()
    {
        return $this->send(self::URL_HEALTH);
    }

    public function test()
    {
        return $this->send(self::URL_TEST);
    }
}