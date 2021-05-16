<?php

namespace YiiHelper\models\routeLog;

use YiiHelper\abstracts\Model;

/**
 * This is the model class for table "pub_route_record".
 *
 * @property int $id 自增ID
 * @property string $system_alias 系统别名
 * @property string $route URL路由
 * @property string $route_type 路由分类：界面设定
 * @property int $sort_order 排序
 * @property int $is_operate 是否操作类[0:否; 1:是]
 * @property string $description 路由描述
 * @property int $access_times 访问次数
 * @property string $created_at 创建时间
 * @property string $updated_at 更新时间
 */
class RouteRecord extends Model
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'pub_route_record';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['sort_order', 'is_operate', 'access_times'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['system_alias'], 'string', 'max' => 50],
            [['route'], 'string', 'max' => 160],
            [['route_type'], 'string', 'max' => 32],
            [['description'], 'string', 'max' => 255],
            [['system_alias', 'route'], 'unique', 'targetAttribute' => ['system_alias', 'route']],
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
            'route'        => 'URL路由',
            'route_type'   => '路由分类：界面设定',
            'sort_order'   => '排序',
            'is_operate'   => '是否操作类[0:否; 1:是]',
            'description'  => '路由描述',
            'access_times' => '访问次数',
            'created_at'   => '创建时间',
            'updated_at'   => '更新时间',
        ];
    }
}