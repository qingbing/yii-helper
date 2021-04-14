# 操作日志抽象类 OperateLog
- table : {{operate_log}}
- 抽象类
- 继承必须指定使用的数据库名 public static function tableName(){}
- 必须指定支持的日志类型 public static function types(){}
- 模型中登录组件使用 \YiiHelper\components\User，主要为了获取登录用户名
- db-sql 参考 sql/operate_log.sql
- 提供操作日志增加方法（需要用子类调用） public static function add(string $type, $data, string $keyword = '', string $message = '')

## test 代码
```php

/**
 * 日志操作模型
 *
 * Class OperateLog
 * @package app\models
 */
class OperateLog extends \YiiHelper\models\abstracts\OperateLog
{
    const TYPE_LOGIN = 'login';
    const TYPE_USER  = 'user';

    /**
     * @inheritDoc
     */
    public static function types()
    {
        return [
            self::TYPE_LOGIN => '登录日志', // login
            self::TYPE_USER  => '用户操作', // user
        ];
    }

    /**
     * 获取table表名
     *
     * @return string
     */
    public static function tableName()
    {
        return 'pro_operate_log';
    }
}
```