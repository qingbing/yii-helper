# yii扩展类 ： Application

- 扩展 \yii\web\Application
- 增加找不到路由就运行指定默认路由的功能（为后期做transmit做准备）

# coding

```php
# 修改 /web/index.php（网页入口文件）， 使用 该应用来启动项目

(new \YiiHelper\extend\Application($config))->run();
```
