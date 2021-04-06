# 工具类：模板替换

## code 使用

```php
$content = \YiiHelper\tools\ReplaceSetting::getInstance('test')->getContent([
    '{{login_nickname}}' => 'qingbing',
    '{{expire_time}}'    => Format::date(),
    '{{site_name}}'      => 'PHP角',
]);
var_dump($content);
```
