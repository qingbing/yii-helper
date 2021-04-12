<?php
/**
 * @link        http://www.phpcorner.net
 * @author      qingbing<780042175@qq.com>
 * @copyright   Chengdu Qb Technology Co., Ltd.
 */

namespace YiiHelper\traits;


use YiiHelper\helpers\DynamicModel;
use Zf\Helper\Exceptions\BusinessException;

/**
 * 数据验证片段
 *
 * Trait TValidator
 * @package YiiHelper\traits
 */
trait TValidator
{
    /**
     * 将参数放入验证规则进行规则验证
     *
     * @param array $rules
     * @return array
     * @throws BusinessException
     * @throws \yii\base\InvalidConfigException
     */
    protected function validateParams($rules = [], $labels = [])
    {
        // 数据获取
        $request = \Yii::$app->getRequest();
        $data    = array_merge($request->getQueryParams(), $request->getBodyParams());
        if (empty($rules)) {
            return $data;
        }
        // 验证并返回数据
        if ($this->validate($data, $rules, $labels)) {
            return $data;
        }
    }

    /**
     * 动态验证数据
     *
     * @param array $data
     * @param array $rules
     * @param array $labels
     * @return bool
     * @throws BusinessException
     * @throws \yii\base\InvalidConfigException
     */
    protected function validate(array $data, $rules = [], $labels = [])
    {
        if (empty($rules)) {
            return true;
        }
        $model = DynamicModel::validateData($data, $rules, $labels);
        if ($model->hasErrors()) {
            // 验证失败
            $error = $model->getErrorSummary(false);
            throw new BusinessException(reset($error), 10000);
        }
        return true;
    }
}