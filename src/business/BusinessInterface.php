<?php
/**
 * @link        http://www.phpcorner.net
 * @author      qingbing<780042175@qq.com>
 * @copyright   Chengdu Qb Technology Co., Ltd.
 */

namespace YiiHelper\business;

use Throwable;
use Yii;
use yii\db\ActiveRecord;
use YiiHelper\models\InterfaceFields;
use YiiHelper\models\Interfaces;
use YiiHelper\models\InterfaceSystem;

/**
 * 接口参数信息管理
 *
 * Class BusinessInterface
 * @package YiiHelper\business
 */
class BusinessInterface
{
    const CACHE_KEY_SYSTEM           = __CLASS__ . ":system:";
    const CACHE_KEY_SYSTEM_INTERFACE = __CLASS__ . ":systemInterface:";

    /**
     * 返回接口系统信息的缓存键
     *
     * @param string $systemAlias
     * @return string
     */
    public static function getCacheKeyForSystem(string $systemAlias)
    {
        return self::CACHE_KEY_SYSTEM . $systemAlias;
    }

    /**
     * 返回系统接口信息的缓存键
     *
     * @param string $systemAlias
     * @param string $path
     * @return string
     */
    public static function getCacheKeyForSystemInterface(string $systemAlias, string $path)
    {
        return self::CACHE_KEY_SYSTEM . $systemAlias . ":{$path}";
    }

    /**
     * 获取系统信息
     *
     * @param string $systemAlias
     * @return mixed
     */
    public static function getSystem(string $systemAlias)
    {
        return Yii::$app->cacheHelper->get(self::getCacheKeyForSystem($systemAlias), function () use ($systemAlias) {
            return InterfaceSystem::find()
                ->andWhere(['=', 'alias', $systemAlias])
                ->asArray()
                ->one();
        }, 3600);
    }

    /**
     * 获取具体接口的信息
     *
     * @param string $systemAlias
     * @param string $path
     * @return mixed
     */
    public static function getSystemInterface(string $systemAlias, string $path)
    {
        return Yii::$app->cacheHelper->get(self::getCacheKeyForSystemInterface($systemAlias, $path), function () use ($systemAlias, $path) {
            $interface = Interfaces::find()
                ->andWhere(['=', 'system_alias', $systemAlias])
                ->andWhere(['=', 'uri_path', $path])
                ->asArray()
                ->one();
            if (!$interface) {
                return [
                    'info'   => null,
                    'fields' => [],
                ];
            }
            $fields = InterfaceFields::find()
                ->andWhere(['=', 'interface_alias', $interface['alias']])
                ->asArray()
                ->all();
            return [
                'info'   => $interface,
                'fields' => $fields,
            ];
        }, 3600);
    }

    /**
     * 添加一个接口及参数信息
     *
     * @param string $systemAlias
     * @param string $pathInfo
     * @param array|null $input
     * @param array|null $output
     * @throws Throwable
     */
    public static function addInterface(string $systemAlias, string $pathInfo, ?array $input = [], ?array $output = [])
    {
        // 利用事务的形式，写入接口数据
        Yii::$app->getDb()->transaction(function () use ($systemAlias, $pathInfo, $input, $output) {
            // 写入接口主体信息
            $data = [
                'system_alias' => $systemAlias,
                'uri_path'     => $pathInfo,
                'alias'        => $systemAlias . ':' . $pathInfo,
            ];
            // 写入接口信息
            $interfaceModel = self::addInterfaceInfo($data);
            // 写入请求信息
            self::addHeaderFields($interfaceModel, 'input', $input['header'] ?? null);
            self::addFileFields($interfaceModel, 'input', $input['file'] ?? null);
            self::addParamFields($interfaceModel, 'input', 'get', self::releaseParams($input['get'] ?? null)['sub']);
            self::addParamFields($interfaceModel, 'input', 'post', self::releaseParams($input['post'] ?? null)['sub']);
            // 写入响应信息
            self::addHeaderFields($interfaceModel, 'output', $output['header'] ?? null);
            self::addParamFields($interfaceModel, 'output', 'response', self::releaseParams($output['response'] ?? null)['sub']);
        });
    }

    /**
     * 添加 get post 接口字段信息
     *
     * @param Interfaces $interfaceInfo
     * @param string $type
     * @param string $dataArea
     * @param array|null $params
     * @param string $parentField
     */
    protected static function addParamFields(Interfaces $interfaceInfo, string $type, string $dataArea, ?array $params, $parentField = '')
    {
        foreach ($params as $val) {
            $alias = $parentField ? "{$parentField}.{$val['field']}" : $val['field'];
            self::addInterfaceField([
                'interface_alias' => $interfaceInfo->alias,
                'parent_alias'    => $parentField,
                'field'           => $val['field'],
                'alias'           => "{$interfaceInfo->alias}|{$alias}",
                'type'            => $type,
                'data_area'       => $dataArea,
                'data_type'       => $val['type'],
            ]);
            if (!empty($val['sub'])) {
                self::addParamFields($interfaceInfo, $type, $dataArea, $val['sub'], $alias);
            }
        }
    }

    /**
     * 添加 header 接口字段信息
     *
     * @param Interfaces $interfaceInfo
     * @param string $type
     * @param array|null $params
     */
    protected static function addHeaderFields(Interfaces $interfaceInfo, string $type = 'input', ?array $params = null)
    {
        if (empty($params)) {
            return;
        }
        foreach ($params as $key => $val) {
            self::addInterfaceField([
                'interface_alias' => $interfaceInfo->alias,
                'parent_alias'    => "",
                'field'           => $key,
                'alias'           => "{$interfaceInfo->alias}:{$key}",
                'type'            => $type,
                'data_area'       => 'header',
                'data_type'       => 'string',
            ]);
        }
    }

    /**
     * 添加 file 接口字段信息
     *
     * @param Interfaces $interfaceInfo
     * @param string $type
     * @param array|null $params
     */
    protected static function addFileFields(Interfaces $interfaceInfo, string $type = 'input', ?array $params = null)
    {
        if (empty($params)) {
            return;
        }
        foreach ($params as $key => $val) {
            self::addInterfaceField([
                'interface_alias' => $interfaceInfo->alias,
                'parent_alias'    => "",
                'field'           => $key,
                'alias'           => "{$interfaceInfo->alias}:{$key}",
                'type'            => $type,
                'data_area'       => 'header',
                'data_type'       => is_array($val['name']) ? 'string' : 'items',
            ]);
        }
    }

    /**
     * 保存接口信息
     *
     * @param array $data
     * @return array|ActiveRecord|Interfaces|null|void
     */
    protected static function addInterfaceInfo(array $data)
    {
        $model = Interfaces::find()
            ->andWhere(['=', 'alias', $data['alias']])
            ->one();
        if (null !== $model) {
            return $model;
        }
        $model = new Interfaces();
        $model->setAttributes($data);
        if ($model->save()) {
            return $model;
        }
        Yii::warning([
            'message' => '接口信息写入失败',
            'file'    => __FILE__,
            'line'    => __LINE__,
            'model'   => 'Interfaces',
            'data'    => $data,
        ], 'interface');
    }

    /**
     * 保存接口字段信息
     *
     * @param array $data
     * @return array|ActiveRecord|InterfaceFields|null|void
     */
    protected static function addInterfaceField(array $data)
    {
        $model = InterfaceFields::find()
            ->andWhere(['=', 'alias', $data['alias']])
            ->one();
        if (null !== $model) {
            return $model;
        }
        $model = new InterfaceFields();
        $model->setAttributes($data);
        if ($model->save()) {
            return $model;
        }
        Yii::warning([
            'message' => '接口信息写入失败',
            'file'    => __FILE__,
            'line'    => __LINE__,
            'model'   => 'InterfaceFields',
            'data'    => $data,
        ], 'interface');
    }

    /**
     * 解析参数的各级数据类型
     *
     * @param mixed $data
     * @return array
     */
    public static function releaseParams($data)
    {
        $data = is_string($data) ? json_decode($data) : json_decode(json_encode($data));
        return self::_releaseParams($data);
    }

    private static function _releaseParams($data, $field = "root")
    {
        $type = gettype($data);
        $item = [
            "field" => $field,
            "type"  => $type,
            'sub'   => [],
        ];
        if (is_object($data)) {
            // 子字段分析
            foreach ($data as $field => $datum) {
                $item['sub'][$field] = self::_releaseParams($datum, $field);
            }
        } else if (is_array($data) && count($data) > 0) {
            if (is_object($data[0])) {
                $item['type'] = 'items';
                // 子数组合并field，值以最后次出现的为准
                $fields = [];
                foreach ($data as $datum) {
                    $fields = array_merge($fields, (array)$datum);
                }
                // 子字段分析
                foreach ($fields as $field => $datum) {
                    $item['sub'][$field] = self::_releaseParams($datum, $field);
                }
            }
        }
        return $item;
    }
}