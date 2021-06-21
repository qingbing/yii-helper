<?php
/**
 * @link        http://www.phpcorner.net
 * @author      qingbing<780042175@qq.com>
 * @copyright   Chengdu Qb Technology Co., Ltd.
 */

namespace YiiHelper\features\interfaceManager\actions;


use Exception;
use yii\base\Action;
use YiiHelper\models\interfaceManager\InterfaceSystems;
use YiiHelper\traits\TResponse;
use YiiHelper\traits\TValidator;
use Zf\Helper\Traits\TOptionFormat;

/**
 * 操作 ： 接口系统选项
 *
 * Class InterfaceSystemOptions
 * @package YiiHelper\features\interfaceManager\actions
 */
class InterfaceSystemOptions extends Action
{
    use TValidator;
    use TResponse;
    use TOptionFormat;

    /**
     * 接口系统选项
     *
     * @return array
     * @throws Exception
     */
    public function run()
    {
        // 参数验证和获取
        $params  = $this->validateParams([
            ['key', 'string', 'label' => '关键字'],
        ]);
        $items   = InterfaceSystems::find()
            ->andWhere(['=', 'is_enable', 1])
            ->andFilterWhere(['=', 'system_name', $params['key']])
            ->orderBy('sort_order ASC, id ASC')
            ->all();
        $options = $this->optionFormat($items, 'system_alias', 'system_name');
        return $this->success($options);
    }
}