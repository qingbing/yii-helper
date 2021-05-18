-- ----------------------------
-- 实现逻辑
-- 1. 界面管理 pub_system 表，对于接口中系统，用 header[x-system] 标识
-- 2. interfaceLog 组件使用系统表
-- ----------------------------

-- ----------------------------
--  Table structure for `pub_system`
-- ----------------------------
CREATE TABLE `pub_system` (
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

insert into `pub_system`
( `alias`, `name`, `description`, `uri_prefix`, `rule`, `ext`, `is_enable`, `is_continue`, `is_record_field`, `is_open_log`, `sort_order`) values
( 'program', '程序员后台', '程序员配置后台程序', '', 'inner', null, '1', '1', '1', '1', '127'),
( 'portal', '后台门户', '后台各系统统一入口转发', '', 'inner', null, '1', '1', '1', '1', '127'),
( 'admin', '管理员后台', '管理员配置后台程序', '', 'inner', null, '1', '1', '1', '1', '127'),
( 'site', '前台网站', '网站主页', '', 'inner', null, '1', '1', '1', '1', '127');
