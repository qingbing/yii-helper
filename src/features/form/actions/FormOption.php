<?php
/**
 * @link        http://www.phpcorner.net
 * @author      qingbing<780042175@qq.com>
 * @copyright   Chengdu Qb Technology Co., Ltd.
 */

namespace YiiHelper\features\form\actions;


use Exception;
use yii\base\Action;
use yii\web\Response;
use YiiHelper\models\form\FormCategory;
use YiiHelper\models\form\FormOption as FormOptionModel;
use YiiHelper\traits\TResponse;
use YiiHelper\traits\TValidator;

/**
 * 操作 ： 表单选项接口
 *
 * Class FormOption
 * @package YiiHelper\features\form\actions
 */
class FormOption extends Action
{
    use TValidator;
    use TResponse;

    /**
     * 获取前端表单选项
     *
     * @return array
     * @throws Exception
     */
    public function run()
    {
        // 参数验证和获取
        $params = $this->validateParams([
            ['key', 'required'],
            ['key', 'exist', 'label' => '表单标记', 'targetClass' => FormCategory::class, 'targetAttribute' => 'key'],
        ]);
        // 获取所有表单选项
        $options         = FormOptionModel::getEnableOptions($params['key']);
        $_replaceOptions = "___replace_option___";
        $R               = [];
        foreach ($options as $option) {
            $_               = [];
            $_['field']      = $option->field;
            $_['label']      = $option->label;
            $_['input_type'] = $option->input_type;
            $_['default']    = $option->default;
            is_array($option->exts) && count($option->exts) > 0 && ($_['exts'] = $option->exts);
            if (
                (
                    $option->input_type === FormOptionModel::INPUT_TYPE_INPUT_RADIO ||
                    $option->input_type === FormOptionModel::INPUT_TYPE_INPUT_CHECKBOX ||
                    $option->input_type === FormOptionModel::INPUT_TYPE_INPUT_SELECT
                ) && isset($option->exts['options']) && is_array($option->exts['options'])
            ) {
                $_options = [];
                foreach ($option->exts['options'] as $k => $v) {
                    $_options["{$_replaceOptions}{$k}"] = $v;
                }
                $_['exts']['options'] = $_options;
            }
            if (is_array($option->rules) && count($option->rules) > 0) {
                $rules = $option->rules;
            } else {
                $rules = [];
            }
            if ($option->is_required) {
                $rule = [
                    "type"    => "required",
                    "message" => $option->required_msg,
                ];
                if (empty($option->required_msg)) {
                    unset($rule['message']);
                }
                array_unshift($rules, $rule);
            }
            count($rules) > 0 && ($_['rules'] = $rules);
            $R[$option->field] = $_;
        }
        // 渲染结果
        $data = $this->success($R, "表单选项");
        $json = replace(json_encode($data, JSON_UNESCAPED_UNICODE), [
            $_replaceOptions => '',
        ]);
        return $json;
    }
}