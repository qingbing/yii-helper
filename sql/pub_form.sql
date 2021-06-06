-- ----------------------------
-- 实现逻辑
-- 1. 界面管理 pub_form_category 表，实现增删改查功能
-- 2. 界面管理 pub_form_option 表，实现增删改查功能
-- 3. 界面管理 pub_form_setting 表，实现配置表单修改功能
-- 4. 提供action(key)来对前端统一输出表单选项（表单类型）
-- 5. 提供工具tool(FormSetting::getInstance(key)->getValue())，对程序提供字段配置输出
-- ----------------------------

-- ----------------------------
--  Table structure for `pub_form_category`
-- ----------------------------
CREATE TABLE `pub_form_category` (
  `key` varchar(100) NOT NULL COMMENT '表单标志',
  `name` varchar(100) NOT NULL COMMENT '表单名称',
  `description` varchar(255) NOT NULL DEFAULT '' COMMENT '表单描述',
  `sort_order` tinyint(4) UNSIGNED NOT NULL DEFAULT '0' COMMENT '排序',
  `is_setting` tinyint(1) UNSIGNED NOT NULL DEFAULT '1' COMMENT '配置类型[0:搜集表单，1:配置项]',
  `is_open` tinyint(1) UNSIGNED NOT NULL DEFAULT '0' COMMENT '是否开放，否时管理员不可操作（不可见）',
  `is_enable` tinyint(1) UNSIGNED NOT NULL DEFAULT '1' COMMENT '启用状态',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
  PRIMARY KEY (`key`),
  KEY `idx_sortOrder` (`sort_order`),
  KEY `idx_isSetting` (`is_setting`),
  KEY `idx_isOpen` (`is_open`),
  KEY `idx_isEnable` (`is_enable`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='表单设置类别表';


-- ----------------------------
--  Table structure for `pub_form_option`
-- ----------------------------
CREATE TABLE `pub_form_option` (
  `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '自增ID',
  `key` varchar(100) NOT NULL COMMENT '表单分类',
  `field` varchar(100) NOT NULL COMMENT '字段',
  `label` varchar(100) NOT NULL COMMENT '字段名',
  `input_type` enum('view-text','input-text','input-password','input-area','input-number','input-radio','input-checkbox','input-select','ele-switch','ele-cascader','ele-slider','ele-rate','ele-color','ele-uploader','time-picker','date-picker','auto-complete','json-editor','vue-editor') NOT NULL DEFAULT 'input-text' COMMENT '表单类型',
  `default` varchar(100) NOT NULL DEFAULT '' COMMENT '默认值',
  `description` varchar(255) NOT NULL DEFAULT '' COMMENT '分类配置描述',
  `sort_order` tinyint(4) UNSIGNED NOT NULL DEFAULT '0' COMMENT '当前分类排序',
  `is_enable` tinyint(1) UNSIGNED NOT NULL DEFAULT '1' COMMENT '表单项目启用状态',
  `exts` json COMMENT '扩展信息',
  `rules` json COMMENT '验证规则',
  `is_required` tinyint(1) UNSIGNED NOT NULL DEFAULT '1' COMMENT '是否必填',
  `required_msg` varchar(200) NOT NULL DEFAULT '' COMMENT '必填时信息为空的提示',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_key_field` (`field`,`key`),
  UNIQUE KEY `uk_key_label` (`label`,`key`),
  KEY `idx_sortOrder` (`sort_order`),
  KEY `idx_isEnable` (`is_enable`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='表单配置项目';


-- ----------------------------
--  Table structure for `pub_form_setting`
-- ----------------------------
CREATE TABLE IF NOT EXISTS `pub_form_setting` (
  `key` varchar(100) NOT NULL COMMENT '表单分类（来自form_category）',
  `values` json COMMENT '表单配置项目值',
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='配置表单结果保存';
