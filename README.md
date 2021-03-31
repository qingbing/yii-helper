# yii-helper
## 描述
yii 公用的一些基础类库

## 文档链接
1. [对于Yii某些封装的提示完善,仅供提示使用](doc/YiiHelper.md)

### 抽象类
1. [控制台基类 : ConsoleController](doc/abstracts/ConsoleController.md)
1. [db-model基类 : Model](doc/abstracts/Model.md)
1. [控制器基类 : RestController](doc/abstracts/RestController.md)


### 组件封装
1. [扩展用户登录组件 : User](doc/components/User.md)


### 封装行为
1. [模型中客户端IP自动填充行为 : IpBehavior](doc/behaviors/IpBehavior.md)
1. [模型中客户端日志ID自动填充行为 : TraceIdBehavior](doc/behaviors/TraceIdBehavior.md)
1. [模型中用户ID自动填充行为 : UidBehavior](doc/behaviors/UidBehavior.md)
1. [模型中登录用户名自动填充行为 : UsernameBehavior](doc/behaviors/UsernameBehavior.md)

### 控制器
1. [健康状态控制器 : HealthController](doc/controllers/HealthController.md)


### 过滤器
1. [Action过滤器 : ActionFilter](doc/filters/ActionFilter.md)


### 助手类器
1. [动态数据验证模型 : DynamicModel](doc/helpers/DynamicModel.md)
1. [响应类 : Response](doc/helpers/Response.md)
1. [数据分页类 : Pager](doc/helpers/Pager.md)


### 片段
1. [响应处理片段 : Response](doc/traits/TResponse.md)
1. [制作保存失败抛出异常片段 : TSave](doc/traits/TSave.md)
1. [数据验证片段 : TValidator](doc/traits/TValidator.md)


# ====== 组件编号 102 ======
# 异常文件编号
1. 1020001 : \YiiHelper\filters\ActionFilter