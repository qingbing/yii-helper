
-- ----------------------------
--  Data for form-key "program-system-manage"
-- ----------------------------

-- pub_form_category
insert into `pub_form_category`
( `key`, `name`, `description`, `sort_order`, `is_setting`, `is_open`, `is_enable`)
values
( 'program-system-manage', '程序员管理——系统管理', '程序员管理——系统管理', '3', '0', '0', '1');


-- pub_form_option
insert into `pub_form_option`
( `key`, `field`, `label`, `input_type`, `default`, `description`, `sort_order`, `is_enable`, `exts`, `rules`, `is_required`, `required_msg`)
values
( 'program-system-manage', 'id', 'ID', 'view-text', '', '', '1', '1', '\"\"', '\"\"', '0', ''),
( 'program-system-manage', 'alias', '系统别名', 'input-text', '', '', '2', '1', '\"\"', '\"\"', '1', ''),
( 'program-system-manage', 'name', '系统名称', 'input-text', '', '', '3', '1', '\"\"', '\"\"', '1', ''),
( 'program-system-manage', 'uri_prefix', 'URI前缀', 'input-text', '', '', '4', '1', '\"\"', '\"\"', '1', ''),
( 'program-system-manage', 'sort_order', '排序', 'input-number', '127', '', '5', '1', '\"\"', '\"\"', '1', ''),
( 'program-system-manage', 'is_enable', '启用状态', 'ele-switch', '', '', '6', '1', '\"\"', '\"\"', '0', ''),
( 'program-system-manage', 'is_continue', '未申明是否调用', 'ele-switch', '', '', '7', '1', '\"\"', '\"\"', '0', ''),
( 'program-system-manage', 'is_record_field', '是否记录新字段', 'ele-switch', '', '', '8', '1', '\"\"', '\"\"', '0', ''),
( 'program-system-manage', 'is_open_log', '开启接口日志', 'ele-switch', '', '', '9', '1', '\"\"', '\"\"', '0', ''),
( 'program-system-manage', 'description', '描述', 'input-area', '', '', '10', '1', '\"\"', '\"\"', '1', ''),
( 'program-system-manage', 'ext', '扩展数据', 'json-editor', '', '', '11', '1', '\"\"', '\"\"', '0', ''),
( 'program-system-manage', 'rule', '调用规则（备换select）', 'input-text', '', '', '12', '1', '\"\"', '\"\"', '0', ''),
( 'program-system-manage', 'updated_at', '更新时间', 'view-text', '', '', '13', '1', '\"\"', '\"\"', '0', ''),
( 'program-system-manage', 'created_at', '创建时间', 'view-text', '', '', '14', '1', '\"\"', '\"\"', '0', '');