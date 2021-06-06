<?php
/**
 * @link        http://www.phpcorner.net
 * @author      qingbing<780042175@qq.com>
 * @copyright   Chengdu Qb Technology Co., Ltd.
 */

namespace YiiHelper\features\tableHeader\actions;


use yii\base\Action;
use YiiHelper\models\tableHeader\Header;
use YiiHelper\models\tableHeader\HeaderOption;
use YiiHelper\traits\TResponse;
use YiiHelper\traits\TValidator;

/**
 * 操作 ： 表头选项接口
 *
 * Class TableHeader
 * @package YiiHelper\features\tableHeader\actions
 */
class TableHeader extends Action
{
    use TValidator;
    use TResponse;

    /**
     *
     * @return array
     * @throws \Zf\Helper\Exceptions\BusinessException
     * @throws \yii\base\InvalidConfigException
     */
    public function run()
    {
        // 参数验证和获取
        $params = $this->validateParams([
            ['key', 'required'],
            ['key', 'exist', 'label' => '表头标记', 'targetClass' => Header::class, 'targetAttribute' => 'key'],
        ]);
        // 获取所有表头选项
        $options = HeaderOption::find()
            ->andWhere(['=', 'header_key', $params['key']])
            ->andWhere(['=', 'is_enable', 1])
            ->orderBy('sort_order ASC, id ASC')
            ->all();
        /* @var HeaderOption[] $options */
        $R = [];
        foreach ($options as $option) {
            $_            = [];
            $_['field']   = $option->field;
            $_['label']   = $option->label;
            $_['default'] = $option->default;
            empty($option->width) || ($_['width'] = $option->width);
            empty($option->fixed) || ($_['fixed'] = $option->fixed);
            empty($option->align) || ($_['align'] = $option->align);
            empty($option->component) || ($_['component'] = $option->component);
            empty($option->options) || ($_['options'] = $option->options);
            empty($option->params) || ($_['params'] = $option->params);
            1 == $option->is_tooltip && ($_['is_tooltip'] = true);
            1 == $option->is_resizable && ($_['is_resizable'] = true);
            1 == $option->is_editable && ($_['is_editable'] = true);
            $R[$option->field] = $_;
        }
        // 渲染结果
        return $this->success($R);
    }
}