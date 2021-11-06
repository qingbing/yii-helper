<?php
/**
 * @link        http://www.phpcorner.net
 * @author      qingbing<780042175@qq.com>
 * @copyright   Chengdu Qb Technology Co., Ltd.
 */

namespace {

    class Yii
    {
        /**
         * @var MyApplication
         */
        public static $app;
    }

    class MyApplication
    {
        /**
         * @var string
         */
        public $systemAlias;
        /**
         * @var \YiiHelper\components\User
         */
        public $user;
        /**
         * @var \yii\redis\Connection
         */
        public $redis;
        /**
         * @var \YiiHelper\components\CacheHelper
         */
        public $cacheHelper;
        /**
         * @var \YiiHelper\components\TokenManager
         */
        public $token;
        /**
         * @var \YiiHelper\proxy\ConfigureProxy::class
         */
        public $configure;
    }
}
