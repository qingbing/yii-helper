# 抽象服务类 LoginBase ： 账户登录基类
- 定义抽象函数，获取登录类型 ： abstract public function getType(): string
- 登录服务 signIn() 包含各种检查
    - 密码检查
    - 账户检查
    - 用户检查
    - 用户生效判断
    - 用户失效判断
    - 整体网站IP段配置
    - 用户网站IP段配置
