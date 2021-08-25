
-- ----------------------------
--  Table structure for `{{%route_systems}}`
-- ----------------------------
CREATE TABLE `{{%route_systems}}` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增ID',
  `code` varchar(50) NOT NULL COMMENT '系统别名',
  `name` varchar(100) NOT NULL COMMENT '系统名称',
  `description` varchar(255) NOT NULL DEFAULT '' COMMENT '描述',
  `uri_prefix` varchar(100) NOT NULL DEFAULT '' COMMENT '系统调用时访问URI前缀',
  `type` varchar(20) NOT NULL DEFAULT 'inner' COMMENT '系统类型[inner->当前系统；transfer->当前系统转发；outer->外部系统]',
  `rule` varchar(20) NOT NULL DEFAULT 'none' COMMENT '参数验证通过后的规则，eg：可以加入时间戳，sign验证等，每一个规则都需要代码支持',
  `ext` json DEFAULT NULL COMMENT '扩展字段数据',

  -- 访问
  `is_enable` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '系统是否启用状态[0:未启用; 1:已启用]，未启用抛异常',
  `is_allow_new_interface` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否允许未注册接口[0:不允许; 1:允许]',

  -- 记录
  `is_record_field` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否记录新接口文档[0:抛异常; 1:继续调用]',

  -- 验证
  `is_open_validate` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '开启接口校验[0:不启用; 1:已启用]',
  `is_strict_validate` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '开启严格校验[0:未启用; 1:已启用],启用是每个字段都必须在{interface_fields}中定义',

  `sort_order` tinyint(4) unsigned NOT NULL DEFAULT '0' COMMENT '显示排序',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_code` (`code`),
  UNIQUE KEY `uk_name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='系统信息表';

-- ----------------------------
--  Data for "{{%route_systems}}"
-- ----------------------------

insert into `{{%route_systems}}`
( `code`, `name`, `description`, `uri_prefix`, `type`, `rule`, `ext`, `is_enable`, `is_allow_new_interface`, `is_record_field`, `is_open_validate`, `is_strict_validate`, `sort_order`, `created_at`, `updated_at`)
 values
( 'program', '程序员后台', '程序员配置后台程序', '', 'inner', 'none', null, '1', '1', '0', '1', '0', '127', '2021-08-17 16:18:44', '2021-08-17 16:19:56'),
( 'portal', '后台门户', '后台各系统统一入口转发', '', 'inner', 'none', null, '1', '1', '0', '1', '0', '127', '2021-08-17 16:18:44', '2021-08-17 16:19:59'),
( 'admin', '管理员后台', '管理员配置后台程序', '', 'inner', 'none', null, '1', '1', '0', '1', '0', '127', '2021-08-17 16:18:44', '2021-08-17 16:20:01'),
( 'site', '前台网站', '网站主页', '', 'inner', 'none', null, '1', '1', '0', '1', '0', '127', '2021-08-17 16:18:44', '2021-08-17 16:20:02');


-- ----------------------------
--  Table structure for `{{%route_interfaces}}`
-- ----------------------------
CREATE TABLE `{{%route_interfaces}}` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增ID',
  `system_code` varchar(50) NOT NULL COMMENT '系统别名',
  `url_path` varchar(200) NOT NULL DEFAULT '' COMMENT '接口的path',
  `name` varchar(100) NOT NULL DEFAULT '' COMMENT '接口名称',
  `source` varchar(20) NOT NULL DEFAULT 'auto' COMMENT '接口来源',
  `is_operate` tinyint(2) unsigned NOT NULL DEFAULT '0' COMMENT '是否操作类[0:否; 1:是]',
  `description` varchar(255) NOT NULL DEFAULT '' COMMENT '描述',

  -- 记录新字段
  `record_field_type` tinyint(2) unsigned NOT NULL DEFAULT '0' COMMENT '接口是否记录新字段[0:随系统; 1:强制开启；2:强制关闭]',

  -- 验证
  `validate_type` tinyint(2) unsigned NOT NULL DEFAULT '0' COMMENT '接口校验方式[0:随系统; 1:强制开启；2:强制关闭]',
  `strict_validate_type` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '开启严格校验[0:随系统; 1:强制开启；2:强制关闭]',

  -- 路由日志
  `is_open_route_log` tinyint(2) unsigned NOT NULL DEFAULT '0' COMMENT '是否开启路由日志[0:否; 1:是]',
  `route_log_message` varchar(255) NOT NULL DEFAULT '' COMMENT '路由操作提示',
  `route_log_key_fields` varchar(200) NOT NULL DEFAULT '' COMMENT '路由关键字',

  -- mock
  `is_open_mocking` tinyint(2) unsigned NOT NULL DEFAULT '0' COMMENT '路由响应是否mock[0:否; 1:是]',
  `is_use_custom_mock` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否使用自定义mock',
  `mock_response` json COMMENT '开启mock时的响应json',

  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_urlPath` (`url_path`),
  KEY `idx_systemCode` (`system_code`),
  KEY `idx_source` (`source`),
  KEY `idx_isOperate` (`is_operate`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='接口信息表';


-- ----------------------------
--  Table structure for `{{%route_interface_fields}}`
-- ----------------------------
CREATE TABLE `{{%route_interface_fields}}` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增ID',
  `url_path` varchar(200) NOT NULL DEFAULT '' COMMENT '接口的path',
  `parent_alias` varchar(255) NOT NULL DEFAULT '' COMMENT '上级字段别名',
  `alias` varchar(255) NOT NULL COMMENT '字段别名',
  `field` varchar(50) NOT NULL COMMENT '字段名',
  `name` varchar(100) NOT NULL DEFAULT '' COMMENT '字段意义',
  `default` varchar(100) DEFAULT null COMMENT '默认值',
  `type` varchar(20) NOT NULL DEFAULT 'post' COMMENT '字段类型[input,output]',
  `data_area` varchar(20) NOT NULL DEFAULT 'post' COMMENT '字段区域[header,file,get,post]',
  `data_type` varchar(50) NOT NULL DEFAULT '' COMMENT '数据类型[integer,double,boolean,string,object,array,items,compare,date,datetime,time,email,in,url,ip,number,default,match,safe,file,image,safe]',
  `description` varchar(255) NOT NULL DEFAULT '' COMMENT '描述',
  `is_required` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否必填[0:否; 1:是]',
  `is_last_level` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '最后级别，子字段不记录',
  `rules` json COMMENT '额外验证规则',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_urlPath_parentAlias_field` (`url_path`, `parent_alias`, `field`), -- url_path+parent_alias+field = alias
  UNIQUE KEY `uk_alias` (`alias`),
  KEY `idx_urlPath` (`url_path`),
  KEY `idx_alias` (`alias`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='系统接口字段表';


-- ----------------------------
--  Table structure for `{{%route_logs}}`
-- ----------------------------
CREATE TABLE `{{%route_logs}}` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增ID',
  `system_code` varchar(50) NOT NULL COMMENT '系统别名',
  `url_path` varchar(200) NOT NULL DEFAULT '' COMMENT '接口的path',
  `trace_id` varchar(32) NOT NULL DEFAULT '' COMMENT '客户端日志ID',
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
  KEY `idx_systemCode` (`system_code`),
  KEY `idx_urlPath` (`url_path`),
  KEY `idx_traceId` (`trace_id`),
  KEY `idx_isSuccess` (`is_success`),
  KEY `idx_keyword` (`keyword`),
  KEY `idx_message` (`message`),
  KEY `idx_useTime` (`use_time`),
  KEY `idx_ip` (`ip`),
  KEY `idx_uid` (`uid`),
  KEY `idx_createdAt` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='接口路由日志表';
