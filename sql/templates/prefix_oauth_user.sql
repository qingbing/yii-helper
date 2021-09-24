-- ----------------------------
--  Table structure for `{{%oauth_user}}`
-- ----------------------------
CREATE TABLE `{{%oauth_user}}` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增ID',
  `system_code` varchar(50) NOT NULL DEFAULT '' COMMENT '系统别名',
  `uuid` varchar(50) NOT NULL DEFAULT '' COMMENT '用户/系统标识',
  `app_key` text DEFAULT NULL COMMENT '公钥',
  `app_secret` text DEFAULT NULL COMMENT '私钥',
  `expire_ip` varchar(255) NOT NULL DEFAULT '' COMMENT '有效IP地址',
  `expire_begin_at` date NOT NULL DEFAULT '1000-01-01' COMMENT '生效日期',
  `expire_end_at` date NOT NULL DEFAULT '1000-01-01' COMMENT '失效日期',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_systemCode_uuid` (`system_code`, `uuid`),
  KEY `idx_uuid` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='oauth三方访问权限登记';



-- ----------------------------
--  Table structure for `{{%oauth_access_token}}`
-- ----------------------------
CREATE TABLE `{{%oauth_access_token}}` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增ID',
  `system_code` varchar(50) NOT NULL DEFAULT '' COMMENT '系统别名',
  `uuid` varchar(50) NOT NULL DEFAULT '' COMMENT '用户/系统标识',
  `access_token` varchar(255) NOT NULL DEFAULT '' COMMENT '访问token',
  `expire_at` datetime NOT NULL COMMENT '有效时间',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `idx_systemCode_uuid` (`system_code`, `uuid`),
  KEY `idx_uuid` (`uuid`),
  KEY `idx_expireAt` (`expire_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='oauth三方访问token记录';