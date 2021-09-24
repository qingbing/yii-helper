<?php
/**
 * @link        http://www.phpcorner.net
 * @author      qingbing<780042175@qq.com>
 * @copyright   Chengdu Qb Technology Co., Ltd.
 */

namespace YiiHelper\components\oauth\drivers;


use Yii;
use yii\base\BaseObject;
use YiiHelper\components\oauth\Oauth;

/**
 * Class BaseOauth
 * @package YiiHelper\components\oauth\drivers
 *
 * @property string $appSecret
 * @property string $systemCode
 */
abstract class BaseOauth extends BaseObject
{
    /**
     * @var Oauth 组件
     */
    public $oauth;
    /**
     * @var string 访问系统代码
     */
    private $_systemCode;
    /**
     * @var string oauth秘钥
     */
    private $_appSecret;

    /**
     * 获取访问系统代码
     *
     * @return string
     */
    public function getSystemCode(): string
    {
        if (null === $this->_systemCode) {
            $this->_systemCode = Yii::$app->id;
        }
        return $this->_systemCode;
    }

    /**
     * 设置访问系统代码
     *
     * @param string $systemCode
     * @return $this
     */
    public function setSystemCode(string $systemCode)
    {
        $this->_systemCode = $systemCode;
        return $this;
    }

    /**
     * 获取oauth秘钥
     *
     * @return string
     */
    public function getAppSecret(): string
    {
        return $this->_appSecret;
    }

    /**
     * 设置oauth秘钥
     *
     * @param string $appSecret
     * @return $this
     */
    public function setAppSecret(string $appSecret)
    {
        $this->_appSecret = $appSecret;
        return $this;
    }

    /**
     * 生成token
     *
     * @param string $uuid
     * @param string $appKey
     * @param mixed $timestamp
     * @return string
     */
    public function generateToken(string $uuid, string $appKey, $timestamp)
    {
        return $this->oauth->generateToken($this->getSystemCode(), $uuid, $appKey, $this->getAppSecret(), $timestamp);
    }
}