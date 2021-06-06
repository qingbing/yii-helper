<?php
/**
 * @link        http://www.phpcorner.net
 * @author      qingbing<780042175@qq.com>
 * @copyright   Chengdu Qb Technology Co., Ltd.
 */

namespace YiiHelper\features\form\services;


use YiiHelper\abstracts\Service;
use YiiHelper\features\form\services\interfaces\IFormSettingService;
use YiiHelper\features\form\tools\FormSetting;
use Zf\Helper\Exceptions\BusinessException;

/**
 * 服务 ： 配置表单管理
 *
 * Class FormSettingService
 * @package YiiHelper\features\form\services
 */
class FormSettingService extends Service implements IFormSettingService
{
    /**
     * 获取配置表单选项
     *
     * @param array $params
     * @return bool|mixed|string|null
     * @throws BusinessException
     */
    public function get(array $params)
    {
        return FormSetting::getInstance($params['key'])->get();
    }

    /**
     * 保存配置表单数据
     *
     * @param array $params
     * @return bool
     * @throws BusinessException
     * @throws \yii\db\Exception
     */
    public function save(array $params): bool
    {
        return FormSetting::getInstance($params['key'])->save($params);
    }
}