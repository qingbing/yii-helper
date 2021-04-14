<?php
/**
 * @link        http://www.phpcorner.net
 * @author      qingbing<780042175@qq.com>
 * @copyright   Chengdu Qb Technology Co., Ltd.
 */

namespace YiiHelper\controllers\abstracts;


use Exception;
use Yii;
use yii\validators\EmailValidator;
use YiiHelper\abstracts\RestController;
use YiiHelper\models\abstracts\UserAccount;
use YiiHelper\services\abstracts\LoginService;
use YiiHelper\validators\MobileValidator;
use YiiHelper\validators\NameValidator;
use YiiHelper\validators\UsernameValidator;
use Zf\Helper\Exceptions\BusinessException;

/**
 * 用户登录相关接口
 *
 * Class LoginController
 * @package YiiHelper\controllers
 */
abstract class LoginController extends RestController
{
    /**
     * @var array 开通登录的方式
     */
    protected $accountRules = [
        UserAccount::TYPE_USERNAME => ['account', UsernameValidator::class],
        UserAccount::TYPE_EMAIL    => ['account', EmailValidator::class],
        UserAccount::TYPE_MOBILE   => ['account', MobileValidator::class],
        UserAccount::TYPE_NAME     => ['account', NameValidator::class],
    ];

    /**
     * @todo 需要子类在构建中实例化
     * @var LoginService
     */
    protected $service;

    /**
     * 账户登录
     *
     * @return array
     * @throws BusinessException
     * @throws Exception
     */
    public function actionSignIn()
    {
        // 校验规则
        $rules = [
            [['account', 'password'], 'required'],
        ];
        $type  = $this->getParam('type', 'email');
        if (!isset($this->accountRules[$type]) || !in_array($type, Yii::$app->getUser()->loginTypes)) {
            throw new BusinessException('不支持的登录方式');
        }
        $rules[] = $this->accountRules[$type];
        // 参数校验
        $this->validateParams($rules, [
            'type'     => '登录类型',
            'account'  => '登录账户',
            'password' => '登录密码',
        ]);
        // 参数获取
        $params = [
            'type'     => $type,
            'account'  => $this->getParam('account'),
            'password' => $this->getParam('password'),
        ];
        // 数据处理
        $res = $this->service->signIn($params);
        //结果返回渲染
        return $this->success($res, '登录成功');
    }

    /**
     * 用户退出登录
     *
     * @return array
     * @throws Exception
     */
    public function actionSignOut()
    {
        // 数据处理
        $res = $this->service->signOut();
        //结果返回渲染
        return $this->success($res, '退出成功');
    }
}