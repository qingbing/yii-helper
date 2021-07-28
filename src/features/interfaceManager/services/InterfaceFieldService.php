<?php
/**
 * @link        http://www.phpcorner.net
 * @author      qingbing<780042175@qq.com>
 * @copyright   Chengdu Qb Technology Co., Ltd.
 */

namespace YiiHelper\features\interfaceManager\services;

use YiiHelper\abstracts\Service;
use YiiHelper\features\interfaceManager\services\interfaces\IInterfaceFieldService;
use YiiHelper\models\interfaceManager\InterfaceFields;
use YiiHelper\models\interfaceManager\Interfaces;
use Zf\Helper\Exceptions\BusinessException;
use Zf\Helper\Exceptions\CustomException;

/**
 * 服务 ： 接口字段管理
 *
 * Class InterfaceFieldService
 * @package YiiHelper\features\interfaceManager\services
 */
class InterfaceFieldService extends Service implements IInterfaceFieldService
{
    /**
     * 字段按类型和区域过滤
     *
     * @param array $fields
     * @param string $type
     * @param string $dataArea
     * @return array
     */
    protected function filterFields(array $fields, string $type, string $dataArea)
    {
        return array_values(array_filter($fields, function ($field) use ($type, $dataArea) {
            /* @var InterfaceFields $field */
            return $field->type == $type && $field->data_area == $dataArea;
        }));
    }

    /**
     * 接口字段列表
     *
     * @param array $params
     * @return array
     * @throws CustomException
     */
    public function list(array $params = []): array
    {
        $interface = Interfaces::findOne([
            'alias' => $params['alias'],
        ]);
        if (null === $interface) {
            throw new CustomException("不存在的接口");
        }
        $options = $interface->options;
        return [
            'input'  => [
                'headers' => $this->filterFields($options, InterfaceFields::TYPE_INPUT, InterfaceFields::DATA_AREA_HEADER),
                'files'   => $this->filterFields($options, InterfaceFields::TYPE_INPUT, InterfaceFields::DATA_AREA_FILE),
                'get'     => $this->filterFields($options, InterfaceFields::TYPE_INPUT, InterfaceFields::DATA_AREA_GET),
                'post'    => $this->filterFields($options, InterfaceFields::TYPE_INPUT, InterfaceFields::DATA_AREA_POST),
            ],
            'output' => [
                'headers'  => $this->filterFields($options, InterfaceFields::TYPE_OUTPUT, InterfaceFields::DATA_AREA_HEADER),
                'response' => $this->filterFields($options, InterfaceFields::TYPE_OUTPUT, InterfaceFields::DATA_AREA_RESPONSE),
            ],
        ];
    }

    /**
     * 添加接口字段
     *
     * @param array $params
     * @return bool
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\db\Exception
     */
    public function add(array $params): bool
    {
        $model           = \Yii::createObject(InterfaceFields::class);
        $_alias          = $params['parent_field'] ? "{$params['parent_field']}.{$params['field']}" : $params['field'];
        $params['alias'] = "{$params['interface_alias']}|{$params['type']}|{$params['data_area']}|{$_alias}";
        $model->setFilterAttributes($params);
        return $model->saveOrException();
    }

    /**
     * 编辑接口字段
     *
     * @param array $params
     * @return bool
     * @throws BusinessException
     * @throws \yii\db\Exception
     */
    public function edit(array $params): bool
    {
        $model = $this->getModel($params);
        unset($params['id']);
        $model->setFilterAttributes($params);
        return $model->saveOrException();
    }

    /**
     * 删除接口字段
     *
     * @param array $params
     * @return bool
     * @throws BusinessException
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function del(array $params): bool
    {
        $model = $this->getModel($params);
        return $model->delete();
    }

    /**
     * 查看接口字段详情
     *
     * @param array $params
     * @return mixed|InterfaceFields
     * @throws BusinessException
     */
    public function view(array $params)
    {
        return $this->getModel($params);
    }

    /**
     * 获取当前操作模型
     *
     * @param array $params
     * @return InterfaceFields
     * @throws BusinessException
     */
    protected function getModel(array $params): InterfaceFields
    {
        $model = InterfaceFields::findOne([
            'id' => $params['id'] ?? null
        ]);
        if (null === $model) {
            throw new BusinessException("接口字段不存在");
        }
        return $model;
    }
}