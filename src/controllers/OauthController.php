<?php
/**
 * @link        http://www.phpcorner.net
 * @author      qingbing<780042175@qq.com>
 * @copyright   Chengdu Qb Technology Co., Ltd.
 */

namespace YiiHelper\controllers;


use Exception;
use YiiHelper\abstracts\RestController;

/**
 * 控制器 : oauth验证
 *
 * Class OauthController
 * @package YiiHelper\controllers
 */
class OauthController extends RestController
{
    /**
     * @return array
     * @throws Exception
     */
    public function actionToken()
    {
        // 参数验证和获取
        $params      = $this->validateParams([
            [['uuid', 'app_key', 'token', 'timestamp'], 'required'],
            ['uuid', 'string', 'label' => '访问标记'],
            ['app_key', 'string', 'label' => '访问公钥'],
            ['token', 'string', 'label' => '访问token'],
            ['timestamp', 'string', 'label' => '时间戳'],
        ]);
        $accessToken = \Yii::$app->oauth->getServer()->generateAccessToken($params['uuid'], $params['app_key'], $params['timestamp'], $params['token']);
        return $this->success($accessToken, '获取访问token');
    }
}