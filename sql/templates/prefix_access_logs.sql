-- ----------------------------
--  Table structure for `{{%access_logs}}`
-- ----------------------------
CREATE TABLE `{{%access_logs}}` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增ID',
  `system_code` varchar(50) NOT NULL COMMENT '系统别名',
  `trace_id` varchar(32) NOT NULL DEFAULT '' COMMENT '客户端日志ID',
  `url_path` varchar(200) NOT NULL DEFAULT '' COMMENT '接口的path',
  `method` varchar(10) NOT NULL DEFAULT '' COMMENT '请求方法[get post put...]',
  `request_data` json DEFAULT NULL COMMENT '接口发送信息',

  `is_success` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否成功[0:失败; 1:成功]',

  `message` varchar(255) NOT NULL DEFAULT '' COMMENT '返回消息',
  `response_code` int(5) unsigned NOT NULL DEFAULT '0' COMMENT 'http状态返回码',
  `response_data` json DEFAULT NULL COMMENT '接口返回信息',
  `exts` json COMMENT '扩展信息',

  `use_time` float(10,6) unsigned NOT NULL DEFAULT '0' COMMENT '接口耗时',

  `ip` varchar(15) NOT NULL DEFAULT '' COMMENT '登录IP',
  `uid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '用户ID',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `idx_traceId` (`trace_id`),
  KEY `idx_systemCode` (`system_code`),
  KEY `idx_urlPath` (`url_path`),
  KEY `idx_isSuccess` (`is_success`),
  KEY `idx_useTime` (`use_time`),
  KEY `idx_ip` (`ip`),
  KEY `idx_uid` (`uid`),
  KEY `idx_createdAt` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='接口访问日志表';
