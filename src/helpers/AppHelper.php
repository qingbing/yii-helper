<?php
/**
 * @link        http://www.phpcorner.net
 * @author      qingbing<780042175@qq.com>
 * @copyright   Chengdu Qb Technology Co., Ltd.
 */

namespace YiiHelper\helpers;


use Yii;

/**
 * Yii-App 辅助类
 *
 * Class AppHelper
 * @package YiiHelper\helpers
 */
class AppHelper
{
    /**
     * 判断使用的是否是yii的基础版
     *
     * @param string $vendorName
     * @return bool
     */
    public static function getIsBasicYii($vendorName = 'vendor')
    {
        $basePath = self::getBasePath();
        return is_dir("{$basePath}/{$vendorName}");
    }

    /**
     * 获取 Yii 的运行日志路径
     *
     * @return string
     */
    public static function getRuntimePath()
    {
        return Yii::$app->getRuntimePath();
    }

    /**
     * 获取项目基本路径
     *
     * @return string
     */
    public static function getBasePath()
    {
        return Yii::$app->getBasePath();
    }

    /**
     * 获取程序根目录
     *
     * @param string $vendorName
     * @return string
     */
    public static function getRootPath($vendorName = 'vendor')
    {
        $basePath = self::getBasePath();
        return self::getIsBasicYii($vendorName) ? $basePath : dirname($basePath);
    }

    /**
     * 获取配置文件目录
     *
     * @param string | null $module
     * @param string $vendorName
     * @return string
     */
    public static function getConfigPath($module = null, $vendorName = 'vendor')
    {
        if (self::getIsBasicYii($vendorName)) {
            return self::getBasePath() . "/config";
        }
        if (null === $module) {
            return self::getBasePath() . "/config";
        }
        return self::getRootPath() . "/{$module}/config";
    }

    /**
     * 获取 db 连接的 dsn 中的数据库名
     *
     * @param string | null $dsn
     * @return string
     */
    public static function getDatabaseName($dsn = null)
    {
        $dsn = $dsn ?? Yii::$app->getDb()->dsn;
        if (preg_match("#dbname=(\S*)#", strtolower($dsn), $ms)) {
            return trim($ms[1], "'");
        }
        return '';
    }
}