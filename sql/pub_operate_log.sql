
-- ----------------------------
--  Table structure for `pub_operate_log`
-- ----------------------------
CREATE TABLE `pub_operate_log` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增ID',
  `trace_id` varchar(32) NOT NULL DEFAULT '' COMMENT '客户端日志ID',
  `system_alias` varchar(50) NOT NULL DEFAULT '' COMMENT '系统别名',
  `type` varchar(32) NOT NULL DEFAULT '' COMMENT '操作类型-用字符串描述',
  `keyword` varchar(100) NOT NULL DEFAULT '' COMMENT '关键字，用于后期筛选',
  `message` varchar(255) NOT NULL DEFAULT '' COMMENT '操作消息',
  `data` json COMMENT '操作的具体内容',
  `ip` varchar(15) NOT NULL DEFAULT '' COMMENT '登录IP',
  `uid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '用户ID',
  `nickname` varchar(50) NOT NULL DEFAULT '' COMMENT '用户昵称',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `idx_traceId` (`trace_id`),
  KEY `idx_systemAlias` (`system_alias`),
  KEY `idx_type` (`type`),
  KEY `idx_uid` (`uid`),
  KEY `idx_create_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='操作日志表';