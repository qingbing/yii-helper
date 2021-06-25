<?php
/**
 * @link        http://www.phpcorner.net
 * @author      qingbing<780042175@qq.com>
 * @copyright   Chengdu Qb Technology Co., Ltd.
 */

namespace YiiHelper\features\interfaceManager\actions;


use Exception;
use yii\base\Action;
use YiiHelper\models\interfaceManager\InterfaceType;
use YiiHelper\traits\TResponse;
use YiiHelper\traits\TValidator;
use Zf\Helper\Traits\TOptionFormat;

/**
 * 操作 ： 接口分类选项
 *
 * Class InterfaceTypeOptions
 * @package YiiHelper\features\interfaceManager\actions
 */
class InterfaceTypeOptions extends Action
{
    use TValidator;
    use TResponse;
    use TOptionFormat;

    /**
     * 接口分类选项
     *
     * @return array
     * @throws Exception
     */
    public function run()
    {
        // 参数验证和获取
        $params  = $this->validateParams([
            ['system_alias', 'string', 'label' => '系统别名'],
            ['key', 'string', 'label' => '关键字'],
        ]);
        $items   = InterfaceType::find()
            ->andFilterWhere(['=', 'system_alias', $params['system_alias']])
            ->andFilterWhere(['like', 'type_name', $params['key']])
            ->orderBy('sort_order ASC, id ASC')
            ->all();
        $options = $this->optionFormat($items, 'type', 'type_name');
        return $this->success($options);
    }
}