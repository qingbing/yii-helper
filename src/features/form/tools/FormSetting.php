<?php
/**
 * @link        http://www.phpcorner.net
 * @author      qingbing<780042175@qq.com>
 * @copyright   Chengdu Qb Technology Co., Ltd.
 */

namespace YiiHelper\features\form\tools;


use YiiHelper\helpers\AppHelper;
use YiiHelper\models\form\FormCategory;
use YiiHelper\models\form\FormOption;
use Zf\Helper\Exceptions\BusinessException;

/**
 * 工具 ： 配置表单
 *
 * Class FormSetting
 * @package YiiHelper\features\form\tools
 */
class FormSetting
{
    const CACHE_KEY = "FormSetting:";

    /**
     * 获取实例
     *
     * @param string $code
     * @return $this
     */
    public static function getInstance(string $code)
    {
        return new self($code);
    }

    /**
     * @var string
     */
    protected $key;
    /**
     * @var FormCategory
     */
    protected $category;
    /**
     * @var \YiiHelper\models\form\FormSetting
     */
    protected $setting;
    /**
     * @var string
     */
    private $_cacheKey;

    /**
     * 获取配置表单实例
     *
     * FormSetting constructor.
     * @param string $key
     */
    final private function __construct(string $key)
    {
        $this->key = $key;
    }

    /**
     * 获取配置表单缓存键
     *
     * @return string
     */
    public function getCacheKey()
    {
        if (null === $this->_cacheKey) {
            $this->_cacheKey = self::CACHE_KEY . $this->key;
        }
        return $this->_cacheKey;
    }

    /**
     * 实例基本信息初始化
     *
     * @throws BusinessException
     */
    protected function init()
    {
        $category = FormCategory::findOne([
            'key' => $this->key,
        ]);
        if (null === $category) {
            throw new BusinessException("不存在的表单类型");
        }
        if ($category->is_setting != 1) {
            throw new BusinessException(replace('表单"{key}"不是配置表单', [
                "{key}" => $this->key,
            ]));
        }
        $this->category = $category;
        $setting        = $category->setting;
        if (null === $setting) {
            $setting      = new \YiiHelper\models\form\FormSetting();
            $setting->key = $category->key;
        }
        $this->setting = $setting;
    }

    /**
     * 获取参数的默认值
     *
     * @return array
     */
    protected function getDefaults()
    {
        $options = FormOption::getEnableOptions($this->key);
        $R       = [];
        foreach ($options as $option) {
            $R[$option->field] = $option->default;
        }
        return $R;
    }

    /**
     * 合并配置表单值
     *
     * @param array|null $values
     * @return array
     */
    protected function mergeSetting(?array $values = null)
    {
        // 获取参数的默认值
        $defaults = $this->getDefaults();
        if (null === $values || 0 === count($defaults)) {
            return $defaults;
        }
        $R = [];
        foreach ($defaults as $field => $default) {
            $R[$field] = $values[$field] ?? $default;
        }
        return $R;
    }

    /**
     * 获取配置表单数据
     *
     * @param string|null $formKey
     * @param string|null $default
     * @return bool|mixed|string|null
     */
    public function get(?string $formKey = null, ?string $default = null)
    {
        $settings = AppHelper::app()->cacheHelper->get($this->getCacheKey(), function () {
            // 基本数据初始化
            $this->init();
            // 获取 setting 参数
            if (is_array($this->setting->values) || null === $this->setting->values) {
                $values = $this->setting->values;
            } else {
                $values = json_decode($this->setting->values, true);
            }
            return $this->mergeSetting($values);
        });

        if (null === $formKey) {
            return $settings;
        }
        if (isset($settings[$formKey])) {
            return $settings[$formKey];
        }
        return $default;
    }

    /**
     * 保存配置表单数据
     *
     * @param array $params
     * @return bool
     * @throws BusinessException
     * @throws \yii\db\Exception
     */
    public function save(array $params)
    {
        // 先清理缓存
        AppHelper::app()->cacheHelper->delete($this->getCacheKey());
        // 基本数据初始化
        $this->init();
        $settings        = $this->mergeSetting($params);
        $setting         = $this->setting;
        $setting->values = $settings;
        return $setting->saveOrException();
    }
}
