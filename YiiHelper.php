<?php
/**
 * @link        http://www.phpcorner.net
 * @author      qingbing<780042175@qq.com>
 * @copyright   Chengdu Qb Technology Co., Ltd.
 */

namespace {

    use YiiHelper\extend\Application;

    /**
     * Class Yii
     *
     * @property-read $app Application
     */
    class Yii
    {
        /**
         * @var Application
         */
        public static $app;

        /**
         * @return Application
         * @throws \yii\base\InvalidConfigException
         */
        public static function app(): Application
        {
            return new Application();
        }
    }
}

namespace yii {

}

namespace yii\console {

}

namespace yii\web {
    /**
     * Class Application
     * @package yii\web
     *
     * @property-read $user \YiiHelper\components\User
     * @method \YiiHelper\components\User getUser()
     *
     * @property-read $mailer \yii\swiftmailer\Mailer
     * @method \yii\swiftmailer\Mailer getMailer()
     *
     * @property-read $interfaceLog \YiiHelper\components\InterfaceLog
     * @property-read $cacheHelper \YiiHelper\components\CacheHelper
     */
    class Application
    {
        /**
         * @var \YiiHelper\components\InterfaceLog
         */
        public $interfaceLog;
        /**
         * @var \YiiHelper\components\CacheHelper
         */
        public $cacheHelper;
    }
}


namespace YiiHelper\extend {
    /**
     * Class Application
     * @package YiiHelper\components
     */
    class Application extends \yii\web\Application
    {
    }
}