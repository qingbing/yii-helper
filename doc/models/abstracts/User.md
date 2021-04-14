# db模型 User ： 用户模型
- table : {{user}}
- 实现了接口 \yii\web\IdentityInterface
- 抽象类，供登录组件（[\YiiHelper\components\User](../../../doc/components/User.md)）时使用，继承时需要覆写函数 public static function tableName()
- 定义抽象方法已获取账户信息 ： abstract protected function getUserAccount($uid, string $type, string $account): UserAccount; 
