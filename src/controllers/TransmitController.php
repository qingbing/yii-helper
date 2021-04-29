<?php
/**
 * @link        http://www.phpcorner.net
 * @author      qingbing<780042175@qq.com>
 * @copyright   Chengdu Qb Technology Co., Ltd.
 */

namespace YiiHelper\controllers;


use YiiHelper\abstracts\RestController;

class TransmitController extends RestController
{
    public function actionIndex()
    {
        var_dump($this->request->pathInfo);
        exit;
    }
}