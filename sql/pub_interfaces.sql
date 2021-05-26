-- ----------------------------
-- 用途 ：组件
-- 1. 实现组件 \YiiHelper\components\InterfaceLog::class
-- 2. 开启组件的 acceptNewInterface=true, 访问的接口（接口和接口参数信息将记录）
-- 3. 开启组件的 openLog=true, 在库里面的接口将回被记录访问日志
--
-- 实现逻辑
-- 1. 实现组件并配置成 interfaceLog
-- 2. 通过开启 acceptNewInterface 将接口记录入 pub_interfaces；接口字段录入 pub_interface_fields
-- 3. 通过开启 openLog， 将在库的接口访问录入 pub_interface_access_logs
-- 4. 界面管理表 pub_interfaces 信息，配置接口名称、接口类型、描述、日志记录、校验等信息
-- 5. 界面管理表 pub_interface_fields 信息，配置名称、描述、必填等信息
-- 6. 界面管理 pub_interface_access_logs， 查询接口访问信息
-- ----------------------------


-- ----------------------------
--  Table structure for `pub_interfaces`
-- ----------------------------
CREATE TABLE `pub_interfaces` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增ID',
  `system_alias` varchar(50) NOT NULL COMMENT '系统别名',
  `uri_path` varchar(200) NOT NULL DEFAULT '' COMMENT '接口的path',
  `alias` varchar(150) NOT NULL COMMENT '接口别名：systemAlias+uri_path',
  `name` varchar(100) NOT NULL DEFAULT '' COMMENT '接口名称',
  `type` varchar(20) NOT NULL DEFAULT '' COMMENT '接口类型[view:查看接口;operate:操作接口]',
  `description` varchar(255) NOT NULL DEFAULT '' COMMENT '描述',
  `log_type` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '日志记录方式[0:随系统; 1:强制开启；2:强制关闭]',
  `is_open_validate` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '开启接口校验[0:不启用; 1:已启用]',
  `is_strict_validate` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '开启严格校验[0:未启用; 1:已启用],启用是每个字段都必须在{interface_fields}中定义',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_systemAlias_uriPath` (`system_alias`, `uri_path`),
  UNIQUE KEY `uk_alias` (`alias`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='系统接口表';


-- ----------------------------
--  Table structure for `pub_interfaces`
-- ----------------------------
CREATE TABLE `pub_interface_fields` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增ID',
  `interface_alias` varchar(150) NOT NULL COMMENT '接口别名：systemAlias+uri_path',
  `parent_alias` varchar(255) NOT NULL DEFAULT '' COMMENT '上级字段别名:interfaceFieldsAlias',
  `field` varchar(50) NOT NULL COMMENT '字段名',
  `alias` varchar(255) NOT NULL COMMENT '字段别名:interfaceAlias+parentAlias+field',
  `name` varchar(100) NOT NULL DEFAULT '' COMMENT '字段意义',
  `type` varchar(20) NOT NULL DEFAULT 'post' COMMENT '字段类型[input,output]',
  `data_area` varchar(20) NOT NULL DEFAULT 'post' COMMENT '字段区域[header,file,get,post]',
  `data_type` varchar(50) NOT NULL DEFAULT '' COMMENT '数据类型[integer|float|boolean|string|object|array|items]',
  `description` varchar(255) NOT NULL DEFAULT '' COMMENT '描述',
  `is_required` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否必填[0:否; 1:是]',
  `is_ignore` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否忽略字段，这些字段后台不接收[0:否; 1:是]',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_interfaceAlias_field` (`interface_alias`, `parent_alias`, `field`),
  UNIQUE KEY `uk_alias` (`alias`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='系统接口字段表';


-- ----------------------------
--  Table structure for `pub_interface_access_logs`
-- ----------------------------
CREATE TABLE `pub_interface_access_logs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增ID',
  `trace_id` varchar(32) NOT NULL DEFAULT '' COMMENT '客户端日志ID',
  `interface_id` bigint(20) unsigned NOT NULL COMMENT '接口ID',
  `method` varchar(10) NOT NULL DEFAULT '' COMMENT '请求方法[get post put...]',
  `client_ip` varchar(20) NOT NULL DEFAULT '' COMMENT '客户端IP',
  `request_data` json DEFAULT NULL COMMENT '接口发送信息',

  `is_intercept` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否参数拦截[0:否; 1:是]',
  `is_success` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否成功[0:失败; 1:成功]',
  `message` varchar(100) NOT NULL DEFAULT '' COMMENT '返回消息',
  `response_code` int(5) unsigned NOT NULL DEFAULT '0' COMMENT 'http状态返回码',
  `response_data` json DEFAULT NULL COMMENT '接口返回信息',
  `response_time` float(10,6) unsigned NOT NULL DEFAULT '0' COMMENT '接口真实耗时',
  `use_time` float(10,6) unsigned NOT NULL DEFAULT '0' COMMENT '整体接口耗时',

  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `idx_traceId` (`trace_id`),
  KEY `idx_interfaceId` (`interface_id`),
  KEY `idx_clientIp` (`client_ip`),
  KEY `idx_isIntercept` (`is_intercept`),
  KEY `idx_isSuccess` (`is_success`),
  KEY `idx_useTime` (`use_time`),
  KEY `idx_responseTime` (`response_time`),
  KEY `idx_createdAt` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='接口访问日志表';


