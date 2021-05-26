-- ----------------------------
-- 用途
--   表头管理：管理前端用 table 来列表的表头
--
-- 实现逻辑
-- 1. pub_header 表头类型，界面操作，一般由程序员来进行操作
-- 2. pub_header_option 表头选项，界面操作，由程序员添加，管理员可控制是否显示
-- 3. 提供action(header_key)来对前端统一输入表头选项
-- ----------------------------

-- ----------------------------
--  Table structure for `pub_header`
-- ----------------------------
CREATE TABLE `pub_header` (
  `key` varchar(100) NOT NULL COMMENT '表头标记',
  `name` varchar(100) NOT NULL COMMENT '表头标志别名',
  `description` varchar(255) NOT NULL DEFAULT '' COMMENT '表头描述',
  `sort_order` int(8) unsigned NOT NULL DEFAULT '1000' COMMENT '排序',
  `is_open` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否开放表头，否时管理员不可操作（不可见）',
  PRIMARY KEY `pk_key`(`key`),
  UNIQUE KEY `uk_name`(`name`),
  KEY `idx_sortOrder`(`sort_order`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='表头设置分类';

-- ----------------------------
--  Table structure for `pub_header_option`
-- ----------------------------
CREATE TABLE `pub_header_option` (
  -- 分类信息
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增ID',
  `header_key` varchar(100) NOT NULL COMMENT '所属表头标记',
  -- 页面需要信息
  `field` varchar(60) NOT NULL COMMENT '字段名',
  `label` varchar(50) NOT NULL COMMENT '显示名',
  `width` varchar(20) NOT NULL DEFAULT '' COMMENT '固定宽度',
  `fixed` varchar(20) NOT NULL DEFAULT '' COMMENT '列固定:[left,right,""]',
  `default` varchar(100) NOT NULL DEFAULT ' - ' COMMENT '默认值,当字段没有是返回，基本无用',
  `align` varchar(20) NOT NULL DEFAULT 'center' COMMENT '表格内容对齐方式:[center,left,right]',
  `is_tooltip` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '当内容过长被隐藏时显示 tooltip',
  `is_resizable` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '对应列是否可以通过拖动改变宽度',
  `component` varchar(60) NOT NULL DEFAULT '' COMMENT '使用组件',
  `options` json COMMENT '字段选项映射关系',
  `params` json COMMENT '参数内容',

  -- 辅助控制
  `description` varchar(255) NOT NULL DEFAULT '' COMMENT '描述',
  `sort_order` tinyint(4) unsigned NOT NULL DEFAULT '0' COMMENT '分类排序',
  `is_required` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否必选，为"是"时不能没取消',
  `is_default` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否默认开启',
  `is_enable` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否开启',
  `operate_ip` varchar(15) NOT NULL DEFAULT '' COMMENT '操作IP',
  `operate_uid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '操作UID',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_key_field` (`header_key`, `field`),
  UNIQUE KEY `uk_key_label` (`header_key`, `label`),
  KEY `idx_sortOrder`(`sort_order`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='表头配置选项';
