# 抽象控制器 LoginController ： 用户登录相关接口
- 抽象类
- 控制器需要继承，使用是可以通过重写
- 重写 protected $accountRules， 可以改变控制器登录的方式，可以继续新增编写登录的方式
- protected $service ： 需要子类在构建中实例化，建议该服务继承 \YiiHelper\services\abstracts\LoginService
