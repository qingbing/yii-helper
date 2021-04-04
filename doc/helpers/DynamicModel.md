# 动态数据验证模型 DynamicModel
覆盖动态数据验证类，让其支持属性的"."验证，例如 people.name，对应验证数据为 ["people"]["name"]
- 继承 \yii\base\DynamicModel
- 覆盖 validateData(array $data, $rules = [], array $labels = [])
- 覆盖 __get($name)
- 覆盖 __set($name, $value)
- 覆盖 getAttributeLabel($attribute)
- 覆盖 getErrors($attribute = null)
- 覆盖 hasErrors($attribute = null)

## test 代码
```php
$data   = [
    'people' => [
        'name' => "user",
        'sex'  => "na",
    ],
    'id'     => 5,
];
$rules  = [
    [['people.name', 'id'], 'required'],
    ['people.name', 'string', 'max' => 2],
    ['people.sex', 'in', 'range' => ['nan', 'nv']],
    ['id', 'integer'],
];
$labels    = [
    'people' => [
        'name' => "姓名",
        'sex'  => "性别",
    ]
];
// 或者,推荐
$labels = [
    'people.name' => "姓名",
    'people.sex'  => "性别",
];
$validator = DynamicModel::validateData($data, $rules, $labels);
if ($validator->validate()) {
    var_dump(222);
} else {
    var_dump(111);
    var_dump('people.sex : ' . $validator->hasErrors('people.sex'));
    var_dump('people.name : ' . $validator->hasErrors('people.name'));
    var_dump('people : ' . $validator->hasErrors('people'));
    var_dump('people.sex : ', $validator->getErrors('people.sex'));
    var_dump('people.name : ', $validator->getErrors('people.name'));
    var_dump('people : ', $validator->getErrors('people'));
    var_dump($validator->getErrors());
}
```
## test 结果

```text
int(111)
string(14) "people.sex : 1"
string(15) "people.name : 1"
string(10) "people : 1"
string(13) "people.sex : "
array(1) {
  [0]=>
  string(21) "性别是无效的。"
}
string(14) "people.name : "
array(1) {
  [0]=>
  string(37) "姓名只能包含至多2个字符。"
}
string(9) "people : "
array(1) {
  [0]=>
  string(37) "姓名只能包含至多2个字符。"
}
array(2) {
  ["people.name"]=>
  array(1) {
    [0]=>
    string(37) "姓名只能包含至多2个字符。"
  }
  ["people.sex"]=>
  array(1) {
    [0]=>
    string(21) "性别是无效的。"
  }
}

```