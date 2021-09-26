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
use YiiHelper\helpers\Req;
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
     * @var bool 开启 oauth 验证
     */
    public $enableOauth = false;
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
     * @var string 传递是否用户登录的 header
     */
    public $isGuestHeader = 'x-portal-is-guest';
    /**
     * @var string 传递登录用户id的 header
     */
    public $uidHeader = 'x-portal-uid';
    /**
     * @var string oauth组件ID
     */
    public $oauthId = 'oauth';
    /**
     * @var bool 是否登录，从 header['x-portal-is-guest'] 中获取
     */
    public $isGuest;
    /**
     * @var string 当前登录用户id 从 header['x-portal-uid'] 中获取
     */
    public $uid;
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
        // 获取登录信息
        $header        = $this->request->getHeaders();
        $this->isGuest = !!$header->get($this->isGuestHeader);
        $this->uid     = $header->get($this->uidHeader);
        // 设置用户登录信息
        Req::setIsGuest($this->isGuest);
        Req::setUid($this->uid);
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
            // 控制台程序
            return;
        }
        if (!$this->enableOauth) {
            // 未开启 oauth 验证
            return;
        }
        if (!$this->oauthChecking()) {
            // 免 oauth 请求
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