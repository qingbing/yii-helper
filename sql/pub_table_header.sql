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
  `is_editable` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '当为编辑表格时，字段是否可在table中编辑',
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



-- ----------------------------
--  Data for header-key "program-header"
-- ----------------------------
-- pub_header
insert into `pub_header`
( `key`, `name`, `description`, `sort_order`, `is_open`)
values
( 'program-header', '程序员管理系统-表头管理', '程序员管理系统-表头管理', '127', '0');

-- pub_header_option
insert into `pub_header_option`
( `header_key`, `field`, `label`, `width`, `fixed`, `default`, `align`, `is_tooltip`, `is_resizable`, `is_editable`, `component`, `options`, `params`, `description`, `sort_order`, `is_required`, `is_default`, `is_enable`, `operate_ip`, `operate_uid`)
values
( 'program-header', '_idx', '序号', '50', 'left', '', '', '0', '1', '0', '', '\"\"', '\"\"', '', '1', '1', '1', '1', '192.168.1.1', '100000000'),
( 'program-header', 'key', '表头标识', '240', 'left', '', 'left', '0', '1', '0', '', '\"\"', '\"\"', '', '2', '1', '1', '1', '192.168.1.1', '100000000'),
( 'program-header', 'name', '表头名称', '300', '', '', 'left', '1', '0', '0', '', '\"\"', '\"\"', '', '3', '1', '1', '1', '192.168.1.1', '100000000'),
( 'program-header', 'sort_order', '排序', '60', '', '', '', '0', '0', '0', '', '\"\"', '\"\"', '', '4', '0', '1', '1', '192.168.1.1', '100000000'),
( 'program-header', 'is_open', '是否公开', '80', '', '', '', '0', '0', '0', '', '\"\"', '\"\"', '', '5', '0', '1', '1', '192.168.1.1', '100000000'),
( 'program-header', 'operate', '操作', '', 'right', '', 'left', '0', '0', '0', 'operate', '\"\"', '[]', '', '6', '1', '1', '1', '192.168.1.1', '100000000');


-- ----------------------------
--  Data for header-key "program-header-options"
-- ----------------------------
-- pub_header
insert into `pub_header`
( `key`, `name`, `description`, `sort_order`, `is_open`)
values
( 'program-header-options', '程序员管理系统-表头选项管理', '程序员管理系统-表头选项管理', '127', '0');

-- pub_header_option
insert into `pub_header_option`
( `header_key`, `field`, `label`, `width`, `fixed`, `default`, `align`, `is_tooltip`, `is_resizable`, `is_editable`, `component`, `options`, `params`, `description`, `sort_order`, `is_required`, `is_default`, `is_enable`, `operate_ip`, `operate_uid`)
 values
( 'program-header-options', '_idx', '序号', '50', 'left', '', '', '0', '0', '0', '', '\"\"', '\"\"', '', '1', '0', '1', '1', '192.168.1.1', '100000000'),
( 'program-header-options', 'field', '选项字段', '100', 'left', '', 'left', '0', '0', '0', '', '\"\"', '\"\"', '', '2', '1', '1', '1', '192.168.1.1', '100000000'),
( 'program-header-options', 'label', '选项名称', '150', '', '', 'left', '1', '0', '1', '', '\"\"', '{\"type\": \"text\"}', '', '3', '1', '1', '1', '192.168.1.1', '100000000'),
( 'program-header-options', 'sort_order', '排序', '80', '', '', '', '0', '0', '1', '', '\"\"', '{\"type\": \"text\"}', '', '4', '0', '1', '1', '192.168.1.1', '100000000'),
( 'program-header-options', 'width', '列宽度', '80', '', '', '', '0', '0', '1', '', '\"\"', '{\"type\": \"text\"}', '', '5', '0', '1', '1', '192.168.1.1', '100000000'),
( 'program-header-options', 'default', '默认值', '60', '', '', '', '0', '0', '1', '', '\"\"', '{\"type\": \"text\"}', '', '6', '0', '1', '1', '192.168.1.1', '100000000'),
( 'program-header-options', 'fixed', '固定方向', '80', '', '', '', '0', '0', '1', '', '\"\"', '{\"type\": \"select\", \"options\": {\"left\": \"靠左\", \"right\": \"靠右\"}}', '', '7', '0', '1', '1', '192.168.1.1', '100000000'),
( 'program-header-options', 'align', '对齐方式', '80', '', '', '', '0', '0', '1', '', '\"\"', '{\"type\": \"select\", \"options\": {\"left\": \"左对齐\", \"right\": \"右对齐\", \"center\": \"居中对齐\"}}', '', '8', '1', '1', '1', '192.168.1.1', '100000000'),
( 'program-header-options', 'is_required', '必填', '80', '', '', '', '0', '0', '1', '', '\"\"', '{\"type\": \"switch\"}', '', '9', '1', '1', '1', '192.168.1.1', '100000000'),
( 'program-header-options', 'is_default', '默认开启', '80', '', '', '', '0', '0', '1', '', '\"\"', '{\"type\": \"switch\"}', '', '10', '1', '1', '1', '192.168.1.1', '100000000'),
( 'program-header-options', 'is_enable', '是否开启', '80', '', '', '', '0', '0', '1', '', '\"\"', '{\"type\": \"switch\"}', '', '11', '1', '1', '1', '192.168.1.1', '100000000'),
( 'program-header-options', 'is_editable', '表格编辑', '80', '', '', '', '0', '0', '1', '', '\"\"', '{\"type\": \"switch\"}', '', '12', '0', '1', '1', '192.168.1.1', '100000000'),
( 'program-header-options', 'is_tooltip', '使用tooltip', '80', '', '', '', '0', '0', '1', '', '\"\"', '{\"type\": \"switch\"}', '', '13', '0', '1', '1', '192.168.1.1', '100000000'),
( 'program-header-options', 'is_resizable', '可拖动', '80', '', '', '', '0', '0', '1', '', '\"\"', '{\"type\": \"switch\"}', '', '14', '0', '0', '0', '192.168.1.1', '100000000'),
( 'program-header-options', 'operate', '操作', '260', 'right', '', 'left', '0', '0', '0', 'operate', '\"\"', '[]', '', '15', '1', '1', '1', '192.168.1.1', '100000000');
