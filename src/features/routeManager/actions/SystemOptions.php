<?php
/**
 * @link        http://www.phpcorner.net
 * @author      qingbing<780042175@qq.com>
 * @copyright   Chengdu Qb Technology Co., Ltd.
 */

namespace YiiHelper\features\routeManager\actions;


use Exception;
use yii\base\Action;
use YiiHelper\models\routeManager\RouteSystems;
use YiiHelper\traits\TResponse;
use YiiHelper\traits\TValidator;
use Zf\Helper\Traits\TOptionFormat;

/**
 * 操作 ： 接口系统选项
 *
 * Class SystemOptions
 * @package YiiHelper\features\routeManager\actions
 */
class SystemOptions extends Action
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
        $items   = RouteSystems::find()
            ->andWhere(['=', 'is_enable', 1])
            ->andFilterWhere(['=', 'name', $params['key']])
            ->orderBy('sort_order ASC, id ASC')
            ->all();
        $options = $this->optionFormat($items, 'code', 'name');
        return $this->success($options);
    }
}