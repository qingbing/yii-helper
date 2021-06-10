<?php

namespace YiiHelper\models\routeLog;

use YiiHelper\abstracts\Model;
use Zf\Helper\Exceptions\BusinessException;

/**
 * This is the model class for table "pub_route_type".
 *
 * @property int $id 自增ID
 * @property string $system_alias 系统别名
 * @property string $route_type 路由分类
 * @property string $type_name 类型名称
 * @property int $sort_order 排序
 * @property string $description 路由描述
 * @property string $created_at 创建时间
 * @property string $updated_at 更新时间
 *
 * @property-read int $recordCount 子路由数量
 */
class RouteType extends Model
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'pub_route_type';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['sort_order'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['system_alias'], 'string', 'max' => 50],
            [['route_type'], 'string', 'max' => 32],
            [['type_name'], 'string', 'max' => 200],
            [['description'], 'string', 'max' => 255],
            [['system_alias', 'route_type'], 'unique', 'targetAttribute' => ['system_alias', 'route_type']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id'           => '自增ID',
            'system_alias' => '系统别名',
            'route_type'   => '路由分类',
            'type_name'    => '类型名称',
            'sort_order'   => '排序',
            'description'  => '路由描述',
            'created_at'   => '创建时间',
            'updated_at'   => '更新时间',
        ];
    }

    /**
     * 获取拥有的子路由数量
     *
     * @return int|string
     */
    public function getRecordCount()
    {
        return $this->hasOne(
            RouteRecord::class,
            ['route_type' => 'route_type']
        )->count();
    }

    /**
     * 检查是否可以删除
     *
     * @return bool
     * @throws BusinessException
     */
    public function beforeDelete()
    {
        if ($this->recordCount > 0) {
            throw new BusinessException("该类型尚有子路由，不能删除");
        }
        return parent::beforeDelete();
    }

    /**
     * 获取所有启用中的系统
     *
     * @param string $systemAlias
     * @param bool $isOption 是否选项卡
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function all(?string $systemAlias, bool $isOption = true)
    {
        $res     = self::find()
            ->select(['route_type', 'type_name'])
            ->andFilterWhere(['=', 'system_alias', $systemAlias])
            ->orderBy('route_type ASC, sort_order ASC, id ASC')
            ->asArray()
            ->all();
        $options = [];
        if (!$isOption) {
            foreach ($res as $re) {
                array_push($options, [
                    'key'   => $re['route_type'],
                    'value' => $re['type_name'],
                ]);
            }
        } else {
            foreach ($res as $re) {
                $options[$re['route_type']] = $re['type_name'];
            }
        }
        return $options;
    }
}