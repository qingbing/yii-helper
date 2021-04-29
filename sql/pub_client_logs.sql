
-- ----------------------------
--  Table structure for `pub_client_logs`
-- ----------------------------
CREATE TABLE `pub_client_logs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增ID',
  `trace_id` bigint(20) unsigned NOT NULL COMMENT '接口ID',
  `system` varchar(20) NOT NULL DEFAULT '' COMMENT '系统',
  `path_info` varchar(20) NOT NULL DEFAULT '' COMMENT '访问路由',
  `is_success` tinyint(2) unsigned NOT NULL DEFAULT '1' COMMENT '是否成功[0:失败; 1:成功]',
  `use_time` float(4,6) NOT NULL DEFAULT '0' COMMENT '接口耗时',
  `message` varchar(100) NOT NULL DEFAULT '' COMMENT '返回消息',
  `send_data` json DEFAULT NULL COMMENT '接口发送信息',
  `callback_data` json DEFAULT NULL COMMENT '接口返回信息',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `callback_at` datetime NOT NULL DEFAULT '1000-01-01 00:00:00' COMMENT '回传时间',
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `idx_traceId` (`trace_id`),
  KEY `idx_pathInfo` (`path_info`),
  KEY `idx_isSuccess` (`is_success`),
  KEY `idx_useTime` (`use_time`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='远程调用日志表';


