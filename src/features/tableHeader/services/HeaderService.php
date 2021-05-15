<?php
/**
 * @link        http://www.phpcorner.net
 * @author      qingbing<780042175@qq.com>
 * @copyright   Chengdu Qb Technology Co., Ltd.
 */

namespace YiiHelper\features\tableHeader\services;


use YiiHelper\abstracts\Service;
use YiiHelper\features\tableHeader\models\Header;
use YiiHelper\features\tableHeader\services\interfaces\IHeaderService;
use YiiHelper\helpers\Pager;
use Zf\Helper\Exceptions\BusinessException;

/**
 * 逻辑类 ： 表头管理
 *
 * Class HeaderService
 * @package YiiHelper\features\tableHeader\services
 */
class HeaderService extends Service implements IHeaderService
{
    /**
     * 表头列表
     *
     * @param array|null $params
     * @return array
     */
    public function list(?array $params = null): array
    {
        $query = Header::find();
        $this->attributeWhere($query, $params, 'is_open');
        $this->likeWhere($query, $params, ['key', 'name']);
        return Pager::getInstance()->pagination($query, $params['pageNo'], $params['pageSize']);
    }

    /**
     * 添加表头
     *
     * @param array $params
     * @return bool
     * @throws \yii\db\Exception
     */
    public function add(array $params): bool
    {
        $model = new Header();
        $model->setAttributes($params);
        return $model->saveOrException();
    }

    /**
     * 编辑表头
     *
     * @param array $params
     * @return bool
     * @throws BusinessException
     * @throws \yii\db\Exception
     */
    public function edit(array $params): bool
    {
        $model = $this->getModel($params);
        unset($params['key']);
        $model->setAttributes($params);
        return $model->saveOrException();
    }

    /**
     * 删除表头
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
     * 查看表头详情
     *
     * @param array $params
     * @return mixed|Header
     * @throws BusinessException
     */
    public function view(array $params)
    {
        return $this->getModel($params);
    }

    /**
     * 获取当前操作表头
     *
     * @param array $params
     * @return Header
     * @throws BusinessException
     */
    protected function getModel(array $params): Header
    {
        $key   = $params['key'] ?? null;
        $model = Header::findOne([
            'key' => $key
        ]);
        if (null === $model) {
            throw new BusinessException("表头不存在");
        }
        return $model;
    }
}