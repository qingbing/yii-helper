# 组件 routeManager ： 路由及路由日志组件
- 注意事项
    - 该组件配置使用即可
    - 如果使用该组件，该组件务必加入应用启动，即：bootstrap
- 对外提供属性
    - errorCodeResponseInvalid : 响应无效返回代码，默认 -999999
    - errorCodeParamsInvalid : 参数无效时的返回代码 -999998
    - routeLogClasses : 自定义路由日志类集合 ['pathInfo' => 'routeLogClass']
    - openRoute : 开启路由记录，默认 false
    - openMock : 开启mock，默认 false
    - acceptNewInterface : 不存在的系统是否抛出异常终止，默认 true
    - mockMsg : mock的消息标识
    - ignoreHeaders ： 不被记入参数的 header 头，默认['x-forwarded-for']，x-forwarded-for 用于信任主机的ip透传
    
- 对外提供方法
    - 获取以"x-"透传的header参数 ： getCustomHeaders(?HeaderCollection $headers = null)

# coding 使用
## 1. 配置
该组件必须加入 bootstrap

```php
'bootstrap'        => ['routeManager'],
'components'       => [
    'routeManager' => [
        'class'              => \YiiHelper\components\RouteManager::class,
    ],
]
```

## 2. 自定义路由
如果用户对路由日志有特别需求，可以自行定义某个路径的路由记录组件(必须继承"\YiiHelper\components\routeManager\RouteLogBase")，然后配置该组件的 routeLogClasses 参数
