<?php
/**
 * @link        http://www.phpcorner.net
 * @author      qingbing<780042175@qq.com>
 * @copyright   Chengdu Qb Technology Co., Ltd.
 */

namespace YiiHelper\helpers\client;


use Yii;
use yii\helpers\FileHelper;
use yii\httpclient\Response;
use yii\web\UploadedFile;
use YiiHelper\models\routeManager\RouteSystems;
use Zf\Helper\Exceptions\CustomException;
use Zf\Helper\Exceptions\ParameterException;

/**
 * 抽象类 : 系统代理
 *
 * Class SystemProxy
 * @package YiiHelper\helpers\client
 */
abstract class SystemProxy extends Proxy
{
    /**
     * @var RouteSystems
     */
    public $system;

    /**
     * @inheritDoc
     *
     * @throws ParameterException
     * @throws \yii\base\InvalidConfigException
     */
    public function init()
    {
        parent::init();
        // 设置系统
        if (!empty($this->system)) {
            $this->setSystem($this->system);
        }
    }

    /**
     * 设置系统模型
     *
     * @param mixed $system
     * @return $this
     * @throws ParameterException
     */
    public function setSystem($system)
    {
        if (is_string($system) && !empty($system)) {
            $system = RouteSystems::getCacheSystem($system);
        }
        if (!$system instanceof RouteSystems) {
            throw new ParameterException("设置系统参数错误");
        }
        $this->system = $system;
        return $this;
    }

    /**
     * 获取请求方法
     *
     * @return string
     */
    protected function getMethod()
    {
        return Yii::$app->getRequest()->getMethod();
    }

    /**
     * 获取请求 pathInfo
     *
     * @return string
     * @throws \yii\base\InvalidConfigException
     */
    protected function getPathInfo()
    {
        return Yii::$app->getRequest()->getPathInfo();
    }

    /**
     * 判断是否是 form-data， form-data 表示可以上传文件
     *
     * @return bool
     */
    protected function isFormData()
    {
        // isFormData 来自 RequestBehavior
        return Yii::$app->getRequest()->isFormData();
    }

    /**
     * 获取访问请求的参数
     *
     * @return array
     */
    protected function getParams()
    {
        // getParams 来自 RequestBehavior
        return Yii::$app->getRequest()->getParams();
    }

    /**
     * 保存转发文件
     *
     * @return array
     * @throws CustomException
     * @throws \yii\base\Exception
     */
    protected function getUploadedFiles()
    {
        $files = [];
        foreach (array_keys($_FILES) as $fileKey) {
            $dir = Yii::getAlias("@runtime/tmp/$fileKey");
            if (!is_dir($dir)) {
                FileHelper::createDirectory($dir);
            }
            $uploadedFile = UploadedFile::getInstanceByName($fileKey);
            if ($uploadedFile) {
                $file = $dir . '/' . $uploadedFile->name;
                if (!$uploadedFile->saveAs($file)) {
                    throw new CustomException("转发文件保存失败");
                }
                $files[$fileKey] = $file;
            } else {
                $uploadedFiles = UploadedFile::getInstancesByName($fileKey);
                foreach ($uploadedFiles as $uploadedFile) {
                    $file = $dir . '/' . $uploadedFile->name;
                    if (!$uploadedFile->saveAs($file)) {
                        throw new CustomException("转发文件保存失败");
                    }
                    $files[$fileKey][] = $file;
                }
            }
        }
        return $files;
    }

    /**
     * 删除转发文件
     *
     * @param array $files
     */
    protected function unlinkUploadedFiles(array $files)
    {
        foreach ($files as $fileKey => $savedFiles) {
            if (is_string($savedFiles)) {
                if (file_exists($savedFiles)) {
                    unlink($savedFiles);
                }
            } else {
                foreach ($savedFiles as $savedFile) {
                    if (file_exists($savedFile)) {
                        unlink($savedFile);
                    }
                }
            }
        }
    }

    /**
     * 转发系统，获取响应结果
     *
     * @param bool $parsed
     * @return Response
     */
    abstract public function transmit($parsed = true);

    /**
     * 解析请求响应
     *
     * @param Response $response
     * @return mixed
     */
    abstract public function parseResponse(Response $response);
}