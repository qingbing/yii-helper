
-- ----------------------------
-- 用途 ：组件。捕获接口的基本信息（接口及字段信息）、记录接口的访问日志的路由操作日志（遵循系统和接口配置）
--
-- 实现逻辑
-- 1. 实现组件并配置成 interfaceManager，将其配置在 bootstrap 中（应用预加载）
-- 2. 在组件中，读取系统、接口、配置（pub_interface_systems,pub_interfaces,pub_interface_fields）等相关信息，进行相关信息捕获和记录
-- 3. 在组件中，根据配置记录访问和路由日志(pub_interface_access_logs,pub_interface_route_logs)
-- 4. 界面管理表 pub_interface_systems 信息，配置系统相关信息
-- 5. 界面管理表 pub_interface_type 信息，配置接口类型信息
-- 6. 界面管理表 pub_interfaces 信息，配置接口名称、接口类型、描述、日志记录、校验等信息
-- 7. 界面管理表 pub_interface_fields 信息，配置名称、描述、必填等信息
-- 8. 界面管理 pub_interface_access_logs， 查询接口访问信息
-- 9. 界面管理 pub_interface_route_logs， 查询接口路由信息
-- ----------------------------

-- ----------------------------
--  Table structure for `pub_interface_systems`
-- ----------------------------
CREATE TABLE `pub_interface_systems` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增ID',
  `system_alias` varchar(50) NOT NULL COMMENT '系统别名',
  `system_name` varchar(100) NOT NULL COMMENT '系统名称',
  `description` varchar(255) NOT NULL DEFAULT '' COMMENT '描述',
  `uri_prefix` varchar(100) NOT NULL DEFAULT '' COMMENT '系统调用时访问URI前缀',
  `rule` varchar(20) NOT NULL DEFAULT 'inner' COMMENT 'inner->当前系统；transfer->当前系统转发；outer->外部系统',
  `ext` json DEFAULT NULL COMMENT '扩展字段数据',

  `is_enable` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '系统是否启用状态[0:未启用; 1:已启用]，未启用抛异常',
  `is_allow_new_interface` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否允许新接口[0:抛异常; 1:继续调用]',
  `is_record_field` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '接口是否记录新字段，调试接口字段[0:不记录，在生成; 1:记录，用于接口调试阶段]',

  `is_open_access_log` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否开启接口访问日志[0:未启用; 1:已启用]',
  `is_open_validate` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '开启接口校验[0:不启用; 1:已启用]',
  `is_strict_validate` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '开启严格校验[0:未启用; 1:已启用],启用是每个字段都必须在{interface_fields}中定义',

  `sort_order` tinyint(4) unsigned NOT NULL DEFAULT '0' COMMENT '显示排序',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_systemAlias` (`system_alias`),
  UNIQUE KEY `uk_systemName` (`system_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='接口对接系统表';


-- ----------------------------
--  Data for "pub_interface_systems"
-- ----------------------------
insert into `pub_interface_systems`
( `system_alias`, `system_name`, `description`, `uri_prefix`, `rule`, `ext`, `is_enable`, `is_allow_new_interface`, `is_record_field`, `is_open_access_log`, `is_open_validate`, `is_strict_validate`, `sort_order`)
values
( 'program', '程序员后台', '程序员配置后台程序', '', 'inner', null, '1', '1', '0', '0', '0', '0', '127'),
( 'portal', '后台门户', '后台各系统统一入口转发', '', 'inner', null, '1', '1', '0', '0', '0', '0', '127'),
( 'admin', '管理员后台', '管理员配置后台程序', '', 'inner', null, '1', '1', '0', '0', '0', '0', '127'),
( 'site', '前台网站', '网站主页', '', 'inner', null, '1', '1', '0', '0', '0', '0', '127');


-- ----------------------------
--  Table structure for `pub_interface_type`
-- ----------------------------
CREATE TABLE `pub_interface_type` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增ID',
  `system_alias` varchar(50) NOT NULL DEFAULT '' COMMENT '系统别名',
  `type` varchar(32) NOT NULL DEFAULT '' COMMENT '接口分类',
  `type_name` varchar(200) NOT NULL DEFAULT '' COMMENT '类型名称',
  `sort_order` tinyint(4) NOT NULL DEFAULT '0' COMMENT '排序',
  `description` varchar(255) NOT NULL DEFAULT '' COMMENT '分类描述',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_systemAlias_routeType` (`system_alias`, `type`),
  KEY `idx_systemAlias` (`system_alias`),
  KEY `idx_type` (`type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='接口分类';


-- ----------------------------
--  Table structure for `pub_interfaces`
-- ----------------------------
CREATE TABLE `pub_interfaces` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增ID',
  `system_alias` varchar(50) NOT NULL COMMENT '系统别名',
  `uri_path` varchar(200) NOT NULL DEFAULT '' COMMENT '接口的path',
  `alias` varchar(150) NOT NULL COMMENT '接口别名：systemAlias+uri_path',
  `name` varchar(100) NOT NULL DEFAULT '' COMMENT '接口名称',
  `type` varchar(20) NOT NULL DEFAULT '' COMMENT '接口分类',
  `is_operate` tinyint(2) unsigned NOT NULL DEFAULT '0' COMMENT '是否操作类[0:否; 1:是]',
  `description` varchar(255) NOT NULL DEFAULT '' COMMENT '描述',

  `record_field_type` tinyint(2) unsigned NOT NULL DEFAULT '0' COMMENT '接口是否记录新字段[0:随系统; 1:强制开启；2:强制关闭]',
  `access_log_type` tinyint(2) unsigned NOT NULL DEFAULT '0' COMMENT '日志记录方式[0:随系统; 1:强制开启；2:强制关闭]',
  `validate_type` tinyint(2) unsigned NOT NULL DEFAULT '0' COMMENT '接口校验方式[0:随系统; 1:强制开启；2:强制关闭]',
  `strict_validate_type` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '开启严格校验[0:随系统; 1:强制开启；2:强制关闭]',

  `is_open_route_log` tinyint(2) unsigned NOT NULL DEFAULT '0' COMMENT '是否开启路由日志[0:否; 1:是]',
  `route_log_message` varchar(255) NOT NULL DEFAULT '' COMMENT '路由操作提示',
  `route_log_key_fields` varchar(200) NOT NULL DEFAULT '' COMMENT '路由关键字',

  `is_open_mocking` tinyint(2) unsigned NOT NULL DEFAULT '0' COMMENT '路由响应是否mock[0:否; 1:是]',
  `is_use_custom_mock` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否使用自定义mock',
  `mock_response` json COMMENT '开启mock时的响应json',

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
  `parent_field` varchar(255) NOT NULL DEFAULT '' COMMENT '上级字段别名',
  `field` varchar(50) NOT NULL COMMENT '字段名',
  `alias` varchar(255) NOT NULL COMMENT '字段别名:interfaceAlias+parentAlias+field',
  `name` varchar(100) NOT NULL DEFAULT '' COMMENT '字段意义',
  `default` varchar(100) DEFAULT null COMMENT '默认值',
  `type` varchar(20) NOT NULL DEFAULT 'post' COMMENT '字段类型[input,output]',
  `data_area` varchar(20) NOT NULL DEFAULT 'post' COMMENT '字段区域[header,file,get,post]',
  `data_type` varchar(50) NOT NULL DEFAULT '' COMMENT '数据类型[integer,double,boolean,string,object,array,items,compare,date,datetime,time,email,in,url,ip,number,default,match,safe,file,image]',
  `description` varchar(255) NOT NULL DEFAULT '' COMMENT '描述',
  `is_required` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否必填[0:否; 1:是]',
  `is_ignore` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否忽略字段，这些字段后台不接收[0:否; 1:是]',
  `is_last_level` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '最后级别，不含子字段',
  `rules` json COMMENT '额外验证规则',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_interfaceAlias_field` (`interface_alias`, `parent_field`, `field`),
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
  `request_data` json DEFAULT NULL COMMENT '接口发送信息',

  `is_intercept` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否参数拦截[0:否; 1:是]',
  `is_success` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否成功[0:失败; 1:成功]',

  `message` varchar(100) NOT NULL DEFAULT '' COMMENT '返回消息',
  `response_code` int(5) unsigned NOT NULL DEFAULT '0' COMMENT 'http状态返回码',
  `response_data` json DEFAULT NULL COMMENT '接口返回信息',
  `exts` json COMMENT '扩展信息',

  `response_time` float(10,6) unsigned NOT NULL DEFAULT '0' COMMENT '接口真实耗时',
  `use_time` float(10,6) unsigned NOT NULL DEFAULT '0' COMMENT '整体接口耗时',

  `ip` varchar(15) NOT NULL DEFAULT '' COMMENT '登录IP',
  `uid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '用户ID',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `idx_traceId` (`trace_id`),
  KEY `idx_interfaceId` (`interface_id`),
  KEY `idx_isIntercept` (`is_intercept`),
  KEY `idx_isSuccess` (`is_success`),
  KEY `idx_responseTime` (`response_time`),
  KEY `idx_useTime` (`use_time`),
  KEY `idx_ip` (`ip`),
  KEY `idx_uid` (`uid`),
  KEY `idx_createdAt` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='接口访问日志表';


-- ----------------------------
--  Table structure for `pub_interface_route_logs`
-- ----------------------------
CREATE TABLE `pub_interface_route_logs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增ID',
  `trace_id` varchar(32) NOT NULL DEFAULT '' COMMENT '客户端日志ID',
  `interface_id` bigint(20) unsigned NOT NULL COMMENT '接口ID',
  `method` varchar(10) NOT NULL DEFAULT '' COMMENT '请求方法[get post put...]',

  `is_success` tinyint(2) unsigned NOT NULL DEFAULT '1' COMMENT '是否成功[0:失败; 1:成功]',
  `keyword` varchar(100) NOT NULL DEFAULT '' COMMENT '关键字，用于后期筛选',
  `message` varchar(255) NOT NULL DEFAULT '' COMMENT '操作消息',
  `input` json COMMENT '请求内容',
  `output` json COMMENT '响应内容',
  `exts` json COMMENT '扩展信息',

  `use_time` float(10,6) unsigned NOT NULL DEFAULT '0' COMMENT '路由耗时',

  `ip` varchar(15) NOT NULL DEFAULT '' COMMENT '登录IP',
  `uid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '用户ID',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `idx_traceId` (`trace_id`),
  KEY `idx_interfaceId` (`interface_id`),
  KEY `idx_isSuccess` (`is_success`),
  KEY `idx_keyword` (`keyword`),
  KEY `idx_message` (`message`),
  KEY `idx_useTime` (`use_time`),
  KEY `idx_ip` (`ip`),
  KEY `idx_uid` (`uid`),
  KEY `idx_createdAt` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='接口路由日志表';
