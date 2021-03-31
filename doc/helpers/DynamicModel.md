# 动态数据验证模型 DynamicModel
覆盖动态数据验证类，让其支持属性的"."验证，例如 people.name，对应验证数据为 ["people"]["name"]
- 继承 \yii\base\DynamicModel
- 覆盖 validateData(array $data, $rules = [], array $labels = [])
- 覆盖 __get($name)
- 覆盖 __set($name, $value)
- 覆盖 getAttributeLabel($attribute)
- 覆盖 getErrors($attribute = null)
- 覆盖 hasErrors($attribute = null)

