# yii-helper
## 描述
yii 公用的一些基础类库

## 功能集
1. [完整功能 ： 健康（应用心跳）探测](doc/features/1.health.md)
1. [完整功能 ： 替换配置](doc/features/2.replace-setting.md)


## 文档链接
1. [IP地址解析 : Ip2Location](doc/Ip2Location.md)
1. [对于Yii某些封装的提示完善,仅供提示使用](doc/YiiHelper.md)

### 抽象类
1. [控制台基类 : ConsoleController](doc/abstracts/ConsoleController.md)
1. [db-model基类 : Model](doc/abstracts/Model.md)
1. [控制器基类 : RestController](doc/abstracts/RestController.md)


### 组件封装
1. [缓存助手 : CacheHelper](doc/components/CacheHelper.md)
1. [接口及接口日志组件 : InterfaceLog](doc/components/InterfaceLog.md)
1. [扩展用户登录组件 : User](doc/components/User.md)


### 封装行为
1. [模型中客户端IP自动填充行为 : IpBehavior](doc/behaviors/IpBehavior.md)
1. [模型中登录用户昵称自动填充行为 : NicknameBehavior](doc/behaviors/NicknameBehavior.md)
1. [模型中客户端日志ID自动填充行为 : TraceIdBehavior](doc/behaviors/TraceIdBehavior.md)
1. [模型中用户ID自动填充行为 : UidBehavior](doc/behaviors/UidBehavior.md)

### 业务功能
1. [业务功能类-接口参数信息管理 : BusinessInterface](doc/business/BusinessInterface.md)

### 控制器
1. [健康状态控制器 : HealthController](doc/controllers/HealthController.md)
1. [替换模版 : ReplaceSettingController](doc/controllers/ReplaceSettingController.md)


### 过滤器
1. [Action过滤器 : ActionFilter](doc/filters/ActionFilter.md)


### 助手类器
1. [动态数据验证模型 : DynamicModel](doc/helpers/DynamicModel.md)
1. [响应类 : Response](doc/helpers/Response.md)
1. [请求助手 : Req](doc/helpers/Req.md)
1. [数据分页类 : Pager](doc/helpers/Pager.md)


### 模型类

#### 抽象模型
1. [接口参数字段 : InterfaceFields](doc/models/InterfaceFields.md)
1. [接口日志 : InterfaceLogs](doc/models/InterfaceLogs.md)
1. [接口信息 : Interfaces](doc/models/Interfaces.md)
1. [接口系统 : InterfaceSystem](doc/models/InterfaceSystem.md)
1. [替换配置 : ReplaceSetting](doc/models/ReplaceSetting.md)
1. [操作日志抽象类 : AOperateLog](doc/models/abstracts/AOperateLog.md)


### 控制器服务类
1. [操作日志服务 : OperateLogService](doc/services/OperateLogService.md)


### yii扩展类
1. [yii扩展类 : Application](doc/extend/Application.md)
1. [应用事件句柄 : EventHandler](doc/extend/EventHandler.md)


### 工具类
1. [模板替换 : ReplaceSetting](doc/tools/ReplaceSetting.md)


### 片段
1. [响应处理片段 : Response](doc/traits/TResponse.md)
1. [制作保存失败抛出异常片段 : TSave](doc/traits/TSave.md)
1. [数据验证片段 : TValidator](doc/traits/TValidator.md)


### 片段
1. [yii-validator扩展验证数据类型为json字符串 : JsonValidator](doc/validators/JsonValidator.md)


# ====== 组件编号 102 ======
# 异常文件编号
1. 1020001 : \YiiHelper\filters\ActionFilter