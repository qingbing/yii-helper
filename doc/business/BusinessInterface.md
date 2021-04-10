# 业务功能类-接口参数信息管理 ： BusinessInterface
- 返回接口系统信息的缓存键 : getCacheKeyForSystem(string $systemAlias)
- 返回系统接口信息的缓存键 : getCacheKeyForSystemInterface(string $systemAlias, string $path)
- 获取系统信息 : getSystem(string $systemAlias)
- 获取具体接口的信息 : getSystemInterface(string $systemAlias, string $path)
- 添加一个接口及参数信息 : addInterface(string $systemAlias, string $pathInfo, array $input = [], array $output = [])
- 解析参数的各级数据类型 : releaseParams($data)

