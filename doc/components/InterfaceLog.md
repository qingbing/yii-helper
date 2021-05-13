# 组件 InterfaceLog ： 接口及接口日志组件
- 注意事项
    - 该组件配置使用即可
    - 如果使用该组件，该组件务必加入应用启动，即：bootstrap
- 对外提供属性
    - acceptNewInterface ： 是否开启接口记录，默认 false
    - openLog ： 是否开启接口日志，默认 false
    - defaultSystem ： 默认系统，针对 path 中不带有 "/" 的路由
    - ignoreHeaders ： 不被记入参数的 header 头，默认['x-forwarded-for']，x-forwarded-for 用于信任主机的ip透传
- 对外提供方法
    - 系统信息 ： getSystemInfo()
    - 接口信息 ： getInterfaceInfo()
    - 获取以"x-"透传的header参数 ： getCustomHeaders(?HeaderCollection $headers = null)
    - 请求开始前调用 ： beforeRequest(Application $application)
    - 记录真实请求开始时间 ： beginRequest()
    - 请求结束后调用 ： afterResponseSend(Response $response)
    - 中断请求，主要用于 transmit ： intercept(string $message = '请求中断', $data = null)

# coding 使用
## 1. 配置
该组件必须加入

```php
'bootstrap'        => ['interfaceLog'],
'components'       => [
    'interfaceLog' => [
        'class'              => InterfaceLog::class,
        'acceptNewInterface' => false,
        'openLog'            => false,
        'defaultSystem'      => 'portal',
        'ignoreHeaders'      => [
            'x-forwarded-for',
        ],
    ],
]
```

