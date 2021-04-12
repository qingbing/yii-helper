# 应用事件句柄 ： EventHandler
- 设计预想
    - 应用请求前调用 ： beforeRequest(Event $event)
    - 应用请求后调用 ： afterRequest(Event $event)
    - 应用响应发送前调用 ： beforeResponseSend(Event $event)
    - 应用响应发送后调用 ： beforeResponseSend(Event $event)
- beforeRequest、afterResponseSend 为设计接口请求日志和接口参数抓取是而使用
    - 使用时这两个配置要一并配置 components->interfaceLog,参考 [InterfaceLog](../components/InterfaceLog.md)
- afterRequest 暂未启用
- beforeResponseSend 主要为规范响应输出格式等问题

# coding 使用
## 1. 配置，不配置不调用

```php
    'on beforeRequest' => [EventHandler::class, 'beforeRequest'],
    'on afterRequest'  => [EventHandler::class, 'afterRequest'],
    'components'       => [
        'interfaceLog' => [
            'class' => InterfaceLog::class,
        ],
        'response'     => [
            'on beforeSend' => [EventHandler::class, 'beforeResponseSend'],
            'on afterSend'  => [EventHandler::class, 'afterResponseSend'],
        ],
    ]
```

按照上面配置后，调用执行顺序为

1. beforeRequest
1. afterRequest
1. beforeResponseSend
1. afterResponseSend
