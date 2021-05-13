
-- ----------------------------
--  Table structure for `pub_interface_system`
-- ----------------------------
CREATE TABLE `pub_interface_system` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增ID',
  `alias` varchar(50) NOT NULL COMMENT '系统别名',
  `name` varchar(100) NOT NULL COMMENT '系统名称',
  `description` varchar(255) NOT NULL DEFAULT '' COMMENT '描述',
  `uri_prefix` varchar(100) NOT NULL DEFAULT '' COMMENT '系统调用时访问URI前缀',
  `rule` varchar(20) NOT NULL DEFAULT 'inner' COMMENT '接口调用规则，inner当前系统，不需要验证和调用',
  `ext` json DEFAULT NULL COMMENT '扩展字段数据',
  `is_enable` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '系统是否启用状态[0:未启用; 1:已启用]，未启用抛异常',
  `is_continue` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '接口未申明(pub_interfaces)是否继续调用[0:抛异常; 1:继续调用]',
  `is_record_field` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '接口是否记录新字段，调试接口字段[0:不记录，在生成; 1:记录，用于接口调试阶段]',
  `is_open_log` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否开启接口日志[0:未启用; 1:已启用]',
  `sort_order` tinyint(4) unsigned NOT NULL DEFAULT '0' COMMENT '显示排序',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_alias` (`alias`),
  UNIQUE KEY `uk_name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='接口对接系统表';

insert into `pub_interface_system`
( `alias`, `name`, `description`, `uri_prefix`, `rule`, `ext`, `is_enable`, `is_continue`, `is_record_field`, `is_open_log`, `sort_order`) values
( 'program', '程序员后台', '程序员配置后台程序', '', 'inner', null, '1', '1', '1', '1', '127'),
( 'portal', '后台门户', '后台各系统统一入口转发', '', 'inner', null, '1', '1', '1', '1', '127'),
( 'admin', '管理员后台', '管理员配置后台程序', '', 'inner', null, '1', '1', '1', '1', '127'),
( 'site', '前台网站', '网站主页', '', 'inner', null, '1', '1', '1', '1', '127');

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
--  Table structure for `pub_interface_logs`
-- ----------------------------
CREATE TABLE `pub_interface_logs` (
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


