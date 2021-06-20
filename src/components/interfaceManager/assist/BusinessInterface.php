<?php
/**
 * @link        http://www.phpcorner.net
 * @author      qingbing<780042175@qq.com>
 * @copyright   Chengdu Qb Technology Co., Ltd.
 */

namespace YiiHelper\components\interfaceManager\assist;


use Yii;
use yii\db\ActiveRecord;
use YiiHelper\helpers\AppHelper;
use YiiHelper\helpers\DynamicModel;
use YiiHelper\models\interfaceManager\InterfaceFields;
use YiiHelper\models\interfaceManager\Interfaces;
use YiiHelper\models\interfaceManager\InterfaceSystems;
use YiiHelper\validators\JsonValidator;
use Zf\Helper\Business\ParentTree;
use Zf\Helper\Util;

/**
 * 接口参数信息管理
 *
 * Class BusinessInterface
 * @package YiiHelper\components\interfaceManager\assist
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
        return AppHelper::app()->cacheHelper->get(self::getCacheKeyForSystem($systemAlias), function () use ($systemAlias) {
            return InterfaceSystems::find()
                ->andWhere(['=', 'system_alias', $systemAlias])
                ->andWhere(['=', 'is_enable', 1])
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
        return AppHelper::app()->cacheHelper->get(self::getCacheKeyForSystemInterface($systemAlias, $path), function () use ($systemAlias, $path) {
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
     * @throws \Throwable
     */
    public static function addInterface(string $systemAlias, string $pathInfo, ?array $input = [], ?array $output = [])
    {
        // 利用事务的形式，写入接口数据
        AppHelper::app()->getDb()->transaction(function () use ($systemAlias, $pathInfo, $input, $output) {
            // 写入接口主体信息
            $data = [
                'system_alias' => $systemAlias,
                'uri_path'     => $pathInfo,
                'alias'        => $systemAlias . '|' . str_replace('/', "_", $pathInfo),
            ];
            // 写入接口信息
            $interfaceModel = self::addInterfaceInfo($data);
            // 写入请求信息
            self::addHeaderFields($interfaceModel, 'input', $input['header'] ?? null);
            self::addFileFields($interfaceModel, $input['file'] ?? null);
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
            $model = self::addInterfaceField([
                'interface_alias' => $interfaceInfo->alias,
                'parent_field'    => $parentField,
                'field'           => $val['field'],
                'alias'           => "{$interfaceInfo->alias}|{$type}|{$dataArea}|{$alias}",
                'type'            => $type,
                'data_area'       => $dataArea,
                'data_type'       => $val['type'],
            ]);
            if (!$model->is_last_level && !empty($val['sub'])) {
                // 非最后级别并且 sub 不为空表示有子项目
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
                'parent_field'    => "",
                'field'           => $key,
                'alias'           => "{$interfaceInfo->alias}|{$type}|header|{$key}",
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
     * @param array|null $params
     */
    protected static function addFileFields(Interfaces $interfaceInfo, ?array $params = null)
    {
        if (empty($params)) {
            return;
        }
        foreach ($params as $key => $val) {
            if (is_string($val['name'])) {
                $data_type = "string";
            } elseif (is_real_array($val['name'])) {
                $data_type = "items";
            } else {
                $data_type = "object";
            }
            self::addInterfaceField([
                'interface_alias' => $interfaceInfo->alias,
                'parent_alias'    => "",
                'field'           => $key,
                'alias'           => "{$interfaceInfo->alias}|input|file|{$key}",
                'type'            => "input",
                'data_area'       => 'file',
                'data_type'       => $data_type,
            ]);
            // file 的 object 数组
            if ("object" === $data_type) {
                self::addParamFields($interfaceInfo, 'input', 'file', self::releaseParams($val['name'])['sub'], $key);
            }
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
        } elseif (is_array($data) && count($data) > 0) {
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

    /**
     * 获取mock数据
     *
     * @param string $systemAlias
     * @param string $path
     * @param string $type
     * @return array
     */
    public static function getMockData(string $systemAlias, string $path, string $type = InterfaceFields::TYPE_OUTPUT)
    {
        $interfaceFields = self::getSystemInterface($systemAlias, $path)['fields'];
        $tree            = ParentTree::getInstance()
            ->setFilter(function ($val) use ($type) {
                return $type == $val['type'];
            })
            ->setData($interfaceFields)
            ->setId("field")
            ->setPid("parent_field")
            ->setTopTag("")
            ->getTreeData();

        return self::fillParams($tree);
    }

    /**
     * 装填数据样例
     *
     * @param array $fieldTree
     * @return array
     */
    protected static function fillParams(array $fieldTree)
    {
        $R = [];
        foreach ($fieldTree as $data) {
            $datum = $data['attr'];
            switch ($datum['data_type']) {
                // 基本数据类型
                case InterfaceFields::DATA_TYPE_INTEGER: // 'integer';
                case InterfaceFields::DATA_TYPE_DOUBLE: // 'double';
                case InterfaceFields::DATA_TYPE_BOOLEAN: // 'boolean';
                case InterfaceFields::DATA_TYPE_STRING: // 'string';
                    $R[$datum['field']] = $datum['data_type'];
                    break;
                case InterfaceFields::DATA_TYPE_OBJECT: // 'object';
                    $R[$datum['field']] = self::fillParams($data['data']);
                    break;
                case InterfaceFields::DATA_TYPE_ARRAY: // 'array';
                    $R[$datum['field']] = ["options_01", "options_02", "..."];
                    break;
                case InterfaceFields::DATA_TYPE_ITEMS: // 'items';
                    $item               = self::fillParams($data['data']);
                    $rest               = array_combine(array_keys($item), array_fill(0, count($item), '...'));
                    $R[$datum['field']] = [$item, $rest];
                    break;
                // 扩展数据类型
                case InterfaceFields::DATA_TYPE_DATE: // date
                    $R[$datum['field']] = "2000-01-01";
                    break;
                case InterfaceFields::DATA_TYPE_DATETIME: // datetime
                    $R[$datum['field']] = "2000-01-01 01:01:01";
                    break;
                case InterfaceFields::DATA_TYPE_TIME: // time
                    $R[$datum['field']] = "01:01:01";
                    break;
                case InterfaceFields::DATA_TYPE_EMAIL: // email
                    $R[$datum['field']] = "xx@xx.xx";
                    break;
                case InterfaceFields::DATA_TYPE_IN: // in
                    $R[$datum['field']] = "in";
                    break;
                case InterfaceFields::DATA_TYPE_URL: // url
                    $R[$datum['field']] = "http://www.example.com";
                    break;
                case InterfaceFields::DATA_TYPE_IP: // ip
                    $R[$datum['field']] = "127.0.0.1";
                    break;
                case InterfaceFields::DATA_TYPE_NUMBER: // number
                    $R[$datum['field']] = "11.11";
                    break;
                case InterfaceFields::DATA_TYPE_COMPARE: // compare
                case InterfaceFields::DATA_TYPE_DEFAULT: // default
                case InterfaceFields::DATA_TYPE_MATCH: // match
                case InterfaceFields::DATA_TYPE_SAFE: // safe
                    $R[$datum['field']] = $datum['data_type'];
                    break;
            }
        }
        return $R;
    }

    /**
     * 过滤配置字段
     *
     * @param array $fields
     * @param string $type
     * @param string $area
     * @return array
     */
    private static function _filterFields(array $fields, string $type, ?string $area = null)
    {
        return array_filter($fields, function ($val) use ($type, $area) {
            if (null === $area) {
                return $type == $val['type'];
            }
            return $type == $val['type'] && $area == $val['data_area'];
        });
    }

    /**
     * 验证数据字段
     *
     * @param array $fields
     * @param array $data
     * @return DynamicModel
     * @throws \yii\base\InvalidConfigException
     */
    private static function _validateData(array $fields, array $data)
    {
        $rules         = [];
        $requireFields = [];
        foreach ($fields as $field) {
            $name = $field['parent_field'] ? "{$field['parent_field']}.{$field['field']}" : "{$field['field']}";
            if ($field['is_required']) {
                array_push($requireFields, $name);
            }
            $extRules = [];
            if (!empty($field['rules'])) {
                $extRules = json_decode($field['rules'], true);
                if (!is_array($extRules)) {
                    $extRules = [];
                }
            }
            $label  = $field['name'];
            $ignore = false;
            switch ($field['data_type']) {
                case InterfaceFields::DATA_TYPE_INTEGER : // integer
                case InterfaceFields::DATA_TYPE_DOUBLE  : // double
                case InterfaceFields::DATA_TYPE_NUMBER  : // number
                    $ruleType = $field['data_type'];
                    $extRules = Util::filterArrayByKeys($extRules, ['message', 'max', 'min', 'tooBig', 'tooSmall']);
                    break;
                case InterfaceFields::DATA_TYPE_BOOLEAN : // boolean
                    $ruleType = $field['data_type'];
                    $extRules = Util::filterArrayByKeys($extRules, ['message', 'trueValue', 'falseValue']);
                    break;
                case InterfaceFields::DATA_TYPE_STRING  : // string
                    $ruleType = $field['data_type'];
                    $extRules = Util::filterArrayByKeys($extRules, ['message', 'length', 'max', 'min', 'message', 'tooShort', 'tooLong']);
                    break;
                case InterfaceFields::DATA_TYPE_OBJECT  : // object
                    $ruleType = JsonValidator::class;
                    break;
                case InterfaceFields::DATA_TYPE_ARRAY   : // array
                    $ruleType = JsonValidator::class;
                    break;
                case InterfaceFields::DATA_TYPE_ITEMS   : // items
                    $ruleType = JsonValidator::class;
                    break;
                case InterfaceFields::DATA_TYPE_COMPARE : // compare
                    $ruleType = $field['data_type'];
                    $extRules = Util::filterArrayByKeys($extRules, ['message', 'compareAttribute', 'compareValue']);
                    if (!isset($extRules['compareAttribute'])) {
                        $ignore = true;
                    }
                    break;
                case InterfaceFields::DATA_TYPE_DATE    : // date
                    $ruleType = $field['data_type'];
                    $extRules = Util::filterArrayByKeys($extRules, ['message', 'format', 'max', 'min', 'tooBig', 'tooSmall', 'maxString', 'minString']);
                    if (!isset($extRules['format'])) {
                        $extRules['format'] = "php:Y-m-d";
                    }
                    break;
                case InterfaceFields::DATA_TYPE_DATETIME: // datetime
                    $ruleType = $field['data_type'];
                    $extRules = Util::filterArrayByKeys($extRules, ['message', 'format', 'max', 'min', 'tooBig', 'tooSmall', 'maxString', 'minString']);
                    if (!isset($extRules['format'])) {
                        $extRules['format'] = "php:Y-m-d H:i:s";
                    }
                    break;
                case InterfaceFields::DATA_TYPE_TIME    : // time
                    $ruleType = $field['data_type'];
                    $extRules = Util::filterArrayByKeys($extRules, ['message', 'format', 'max', 'min', 'tooBig', 'tooSmall', 'maxString', 'minString']);
                    if (!isset($extRules['format'])) {
                        $extRules['format'] = "php:H:i:s";
                    }
                    break;
                case InterfaceFields::DATA_TYPE_IN      : // in
                    $ruleType = $field['data_type'];
                    $extRules = Util::filterArrayByKeys($extRules, ['message', 'range', 'strict', 'not']);
                    if (!isset($extRules['range'])) {
                        $ignore = true;
                    }
                    break;
                case InterfaceFields::DATA_TYPE_DEFAULT : // default
                    $ruleType = $field['data_type'];
                    $extRules = Util::filterArrayByKeys($extRules, ['message', 'value']);
                    if (!isset($extRules['value'])) {
                        $ignore = true;
                    }
                    break;
                case InterfaceFields::DATA_TYPE_MATCH   : // match
                    $ruleType = $field['data_type'];
                    $extRules = Util::filterArrayByKeys($extRules, ['message', 'pattern']);
                    if (!isset($extRules['pattern'])) {
                        $ignore = true;
                    }
                    break;
                case InterfaceFields::DATA_TYPE_EMAIL   : // email
                case InterfaceFields::DATA_TYPE_URL     : // url
                case InterfaceFields::DATA_TYPE_IP      : // ip
                    $ruleType = $field['data_type'];
                    $extRules = Util::filterArrayByKeys($extRules, ['message']);
                    break;
                case InterfaceFields::DATA_TYPE_SAFE    : // safe
                default:
                    $ruleType = 'safe';
                    $extRules = Util::filterArrayByKeys($extRules, ['message']);
                    break;
            }
            if ($ignore) {
                continue;
            }
            $rule = [$name, $ruleType];
            if (!empty($label)) {
                $rule['label'] = $label;
            }
            $rule['default'] = $field['default'];
            array_push($rules, array_merge($rule, $extRules));
        }
        if (count($requireFields) > 0) {
            array_unshift($rules, [$requireFields, 'required']);
        }
        return DynamicModel::validateData($data, $rules);
    }

    /**
     * 验证参数是否正确，并返回验证后信息
     *
     * @param string $systemAlias
     * @param string $path
     * @param array|null $data
     * @param string $type
     * @return array
     * @throws \yii\base\InvalidConfigException
     */
    public static function validateData(string $systemAlias, string $path, ?array $data = null, string $type = InterfaceFields::TYPE_INPUT)
    {
        $interfaceFields = self::getSystemInterface($systemAlias, $path)['fields'];
        $isValidSuccess  = true;
        $errorMsg        = [];
        $errors          = [
            'header' => [],
            'get'    => [],
            'post'   => [],
        ];
        $validData       = [
            'header' => [],
            'get'    => [],
            'post'   => [],
        ];
        $headerFields    = self::_filterFields($interfaceFields, $type, InterfaceFields::DATA_AREA_HEADER);
        if (!empty($headerFields)) {
            $headerValid = self::_validateData($headerFields, $data['headers'] ?? []);
            if ($headerValid->hasErrors()) {
                $isValidSuccess   = false;
                $errors['header'] = $headerValid->getErrorSummary(true);
                $errorMsg         = array_merge($errorMsg, $headerValid->getErrorSummary(false));
            } else {
                $validData['header'] = $headerValid->values;
            }
        }
        $postFields = self::_filterFields($interfaceFields, $type, InterfaceFields::DATA_AREA_POST);
        if (!empty($postFields)) {
            $postValid = self::_validateData($postFields, $data['post'] ?? []);
            if ($postValid->hasErrors()) {
                $isValidSuccess = false;
                $errors['post'] = $postValid->getErrorSummary(true);
                $errorMsg       = array_merge($errorMsg, $postValid->getErrorSummary(false));
            } else {
                $validData['post'] = $postValid->values;
            }
        }
        $getFields = self::_filterFields($interfaceFields, $type, InterfaceFields::DATA_AREA_GET);
        if (!empty($getFields)) {
            $getValid = self::_validateData($getFields, $data['get'] ?? []);
            if ($getValid->hasErrors()) {
                $isValidSuccess = false;
                $errors['get']  = $getValid->getErrorSummary(true);
                $errorMsg       = array_merge($errorMsg, $getValid->getErrorSummary(false));
            } else {
                $validData['get'] = $getValid->values;
            }
        }
        if ($isValidSuccess) {
            return [
                "isValidSuccess" => $isValidSuccess,
                "validData"      => $validData,
            ];
        } else {
            return [
                "isValidSuccess" => $isValidSuccess,
                "errors"         => $errors,
                "errorMsg"       => array_shift($errorMsg),
            ];
        }
    }
}
