<?php
/**
 * @link        http://www.phpcorner.net
 * @author      qingbing<780042175@qq.com>
 * @copyright   Chengdu Qb Technology Co., Ltd.
 */

namespace YiiHelper\controllers;


use Exception;
use Yii;
use YiiHelper\abstracts\RestController;

/**
 * 控制器 : 系统访问 token 控制
 *
 * Class TokenController
 * @package YiiHelper\controllers
 */
class TokenController extends RestController
{
    /**
     * @return array
     * @throws Exception
     */
    public function actionIndex()
    {
        return $this->success(Yii::$app->token->generateToken(), '获取访问token');
    }
}