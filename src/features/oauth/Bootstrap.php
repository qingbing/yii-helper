<?php
/**
 * @link        http://www.phpcorner.net
 * @author      qingbing<780042175@qq.com>
 * @copyright   Chengdu Qb Technology Co., Ltd.
 */

namespace YiiHelper\features\oauth;


use yii\base\Application;
use yii\base\BaseObject;
use yii\base\BootstrapInterface;
use yii\web\Request;
use YiiHelper\components\oauth\Oauth;
use Zf\Helper\Exceptions\ForbiddenHttpException;

/**
 * 页面启动检查 accessToken
 *
 * Class Bootstrap
 * @package YiiHelper\features\oauth
 */
class Bootstrap extends BaseObject implements BootstrapInterface
{
    /**
     * @var string 获取 accessToken 的pathInfo，该路径不需要检查 accessToken
     */
    public $oauthPathInfo = "oauth/token";
    /**
     * @var array 定义不需要检查 accessToken 的路径集合
     */
    public $noCheckPathInfos = [];
    /**
     * @var string 获取 oauth-accessToken 的header名
     */
    public $accessTokenHeader = 'r-oauth-access-token';
    /**
     * @var string 获取 oauth-uuid 的header
     */
    public $oauthUuidHeader = 'r-oauth-uuid';
    /**
     * @var string oauth组件ID
     */
    public $oauthId = 'oauth';
    /**
     * @var Request
     */
    protected $request;
    /**
     * @var bool
     */
    protected $isConsole;

    /**
     * @inheritDoc
     */
    public function init()
    {
        $this->request   = \Yii::$app->getRequest();
        $this->isConsole = $this->request->getIsConsoleRequest();
    }

    /**
     * Bootstrap method to be called during application bootstrap stage.
     *
     * @param Application $app the application currently running
     * @throws ForbiddenHttpException
     * @throws \Zf\Helper\Exceptions\ProgramException
     * @throws \yii\base\InvalidConfigException
     */
    public function bootstrap($app)
    {
        if ($this->isConsole) {
            return;
        }
        if (!$this->oauthChecking()) {
            return;
        }
        $accessToken = $this->request->getHeaders()->get($this->accessTokenHeader);
        $uuid        = $this->request->getHeaders()->get($this->oauthUuidHeader);
        if ($this->getOauth()->getServer()->isExpireAccessToken($uuid, $accessToken)) {
            return;
        }
        throw new ForbiddenHttpException("无accessToken,您无权访问该请求");
    }

    /**
     * 判断请求是否需要检查accessToken
     * @return bool
     * @throws \yii\base\InvalidConfigException
     */
    protected function oauthChecking()
    {
        $pathInfo = $this->request->getPathInfo();
        if ($pathInfo == $this->oauthPathInfo) {
            return false;
        }
        return !in_array($pathInfo, $this->noCheckPathInfos);
    }

    /**
     * @return Oauth|null
     * @throws \yii\base\InvalidConfigException
     */
    protected function getOauth()
    {
        return \Yii::$app->get($this->oauthId);
    }
}