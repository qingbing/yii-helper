-- ----------------------------
-- 实现逻辑
-- 1. 通过 yii.bootstrap.routeManager 加载路由信息进入 pub_route_record
-- 2. 界面管理 pub_route_type 表
-- 3. 界面管理 pub_route_record 的 type、is_operate、description
-- 4. 界面管理 pub_route_log_config 的路由配置信息
-- 5. 通过 yii.routeRecord.openLog 操作日志进入 pub_route_log
-- 6. 界面查询 pub_route_log 操作日志表
-- ----------------------------

-- ----------------------------
--  Table structure for `pub_route_record`
-- ----------------------------
CREATE TABLE `pub_route_type` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增ID',
  `system_alias` varchar(50) NOT NULL DEFAULT '' COMMENT '系统别名',
  `route_type` varchar(32) NOT NULL DEFAULT '' COMMENT '路由分类',
  `type_name` varchar(200) NOT NULL DEFAULT '' COMMENT '类型名称',
  `sort_order` tinyint(4) NOT NULL DEFAULT '0' COMMENT '排序',
  `description` varchar(255) NOT NULL DEFAULT '' COMMENT '路由描述',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_systemAlias_routeType` (`system_alias`, `route_type`),
  KEY `idx_systemAlias` (`system_alias`),
  KEY `idx_routeType` (`route_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='系统路由类型';


-- ----------------------------
--  Table structure for `pub_route_record`
-- ----------------------------
CREATE TABLE `pub_route_record` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增ID',
  `system_alias` varchar(50) NOT NULL DEFAULT '' COMMENT '系统别名',
  `route` varchar(160) NOT NULL DEFAULT '' COMMENT 'URL路由',
  `route_type` varchar(32) NOT NULL DEFAULT '' COMMENT '路由分类：界面设定',
  `sort_order` tinyint(4) NOT NULL DEFAULT '0' COMMENT '排序',
  `is_operate` tinyint(2) unsigned NOT NULL DEFAULT '0' COMMENT '是否操作类[0:否; 1:是]',
  `description` varchar(255) NOT NULL DEFAULT '' COMMENT '路由描述',
  `access_times` bigint(20) unsigned NOT NULL DEFAULT 0 COMMENT '访问次数',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_systemAlias_route` (`system_alias`, `route`),
  KEY `idx_systemAlias` (`system_alias`),
  KEY `idx_route` (`route`),
  KEY `idx_create_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='系统路由记录';


-- ----------------------------
--  Table structure for `pub_route_log_config`
-- ----------------------------
CREATE TABLE `pub_route_log_config` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增ID',
  `system_alias` varchar(50) NOT NULL DEFAULT '' COMMENT '系统别名',
  `route` varchar(160) NOT NULL DEFAULT '' COMMENT 'URL路由',
  `is_logging` tinyint(2) unsigned NOT NULL DEFAULT '0' COMMENT '是否记录日志[0:否; 1:是]',
  `message` varchar(255) NOT NULL DEFAULT '' COMMENT '路由操作提示',
  `key_fields` varchar(200) NOT NULL DEFAULT '' COMMENT '路由关键字',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_systemAlias_route` (`system_alias`, `route`),
  KEY `idx_systemAlias` (`system_alias`),
  KEY `idx_route` (`route`),
  KEY `idx_logging` (`is_logging`),
  KEY `idx_create_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='系统路由配置';


-- ----------------------------
--  Table structure for `pub_route_access_logs`
-- ----------------------------
CREATE TABLE `pub_route_access_logs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增ID',
  `route_log_config_id` bigint(20) unsigned NOT NULL COMMENT '系统路由配置ID',
  `trace_id` varchar(32) NOT NULL DEFAULT '' COMMENT '客户端日志ID',
  `is_success` tinyint(2) unsigned NOT NULL DEFAULT '1' COMMENT '是否成功[0:失败; 1:成功]',
  `keyword` varchar(100) NOT NULL DEFAULT '' COMMENT '关键字，用于后期筛选',
  `message` varchar(255) NOT NULL DEFAULT '' COMMENT '操作消息',
  `input` json COMMENT '操作的具体内容',
  `output` json COMMENT '操作的具体内容',
  `exts` json COMMENT '扩展信息',
  `ip` varchar(15) NOT NULL DEFAULT '' COMMENT '登录IP',
  `uid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '用户ID',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `idx_routeLogConfigId` (`route_log_config_id`),
  KEY `idx_traceId` (`trace_id`),
  KEY `idx_isSuccess` (`is_success`),
  KEY `idx_keyword` (`keyword`),
  KEY `idx_uid` (`uid`),
  KEY `idx_create_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='路由日志表';