<?php
/**
 * @link        http://www.phpcorner.net
 * @author      qingbing<780042175@qq.com>
 * @copyright   Chengdu Qb Technology Co., Ltd.
 */

namespace YiiHelper\abstracts;

use YiiHelper\traits\TResponse;

/**
 * web 基类
 * Class WebController
 * @package YiiHelper\abstracts
 */
abstract class WebController extends \yii\rest\Controller
{
    use TResponse;
}