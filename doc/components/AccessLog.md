# 组件 AccessLog ： 系统接口访问日志组件
- 注意事项
    - 该组件配置使用即可
    - 如果使用该组件，该组件务必加入应用启动，即：bootstrap
- 对外提供属性
    - open : 开启访问日志
    - ignoreHeaders ： 不被记入参数的 header 头，默认['x-forwarded-for']，x-forwarded-for 用于信任主机的ip透传
    - accessLogModel ： 日志模型类，默认"\YiiHelper\models\abstracts\AccessLogs"
    
- 对外提供方法
    - 获取以"x-"透传的header参数 ： getCustomHeaders(?HeaderCollection $headers = null)

# coding 使用
## 1. 配置
该组件必须加入 bootstrap

```php
'bootstrap'        => ['accessLog'],
'components'       => [
    'AccessLog' => [
        'class' => \YiiHelper\components\AccessLog::class,
    ],
]
```