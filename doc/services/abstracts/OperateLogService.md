# 服务 OperateLogService ： 操作日志服务
- 设置日志模型 ： setModel(OperateLog $model)
- 请求服务
    - 获取日志类型列表 ： types()
    - 操作日志列表查询 ： search(array $params = []): array
    - 获取日志详情 ： view($id)

