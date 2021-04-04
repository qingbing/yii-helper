<?php
/**
 * @link        http://www.phpcorner.net
 * @author      qingbing<780042175@qq.com>
 * @copyright   Chengdu Qb Technology Co., Ltd.
 */

namespace YiiHelper\abstracts;

use yii\base\InvalidConfigException;
use yii\helpers\ArrayHelper;
use yii\rest\Controller;
use YiiHelper\traits\TResponse;
use YiiHelper\traits\TValidator;
use Zf\Helper\Exceptions\ParameterException;

/**
 * web 基类
 * Class WebController
 * @package YiiHelper\abstracts
 */
abstract class RestController extends Controller
{
    // 使用响应片段
    use TResponse;
    // 使用参数验证片段
    use TValidator;

    /**
     * 获取参数
     *
     * @param string $key
     * @param null|mixed $default
     * @return array|mixed|string|null
     * @throws \Exception
     */
    protected function getParam(string $key, $default = null)
    {
        $subKey = '';
        if (false !== strpos($key, '.')) {
            list($key, $subKey) = explode('.', $key, 2);
        }
        $val = $this->request->post($key);
        if (null === $val) {
            $val = $this->request->get($key, $default);
        }
        if (is_string($val)) {
            $val = trim($val);
        }
        if (!empty($subKey)) {
            if (!is_array($val)) {
                return $default;
            }
            return ArrayHelper::getValue($val, $subKey, $default);
        }
        return $val;
    }

    /**
     * 分页参数校验
     *
     * @throws ParameterException
     * @throws InvalidConfigException
     */
    protected function pageParams()
    {
        // 参数校验
        $this->validateParams([
            ['pageNo', 'integer', 'min' => 1],
            ['pageSize', 'integer', 'min' => 1],
        ], [
            'pageNo'   => '页码',
            'pageSize' => '分页条数',
        ]);
        // 参数获取并返回
        return [
            'pageNo'   => $this->getParam('pageNo', 1),
            'pageSize' => $this->getParam('pageSize', 10),
        ];
    }
}