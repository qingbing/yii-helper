
-- ----------------------------
--  Data for form-key "program-replace-setting"
-- ----------------------------

-- pub_form_category
insert into `pub_form_category`
( `key`, `name`, `description`, `sort_order`, `is_setting`, `is_open`, `is_enable`)
values
( 'program-replace-setting', '程序员管理——替换模版管理', '程序员管理——替换模版管理', '6', '0', '0', '1');


-- pub_form_option
insert into `pub_form_option`
( `key`, `field`, `label`, `input_type`, `default`, `description`, `sort_order`, `is_enable`, `exts`, `rules`, `is_required`, `required_msg`)
values
( 'program-replace-setting', 'code', '标识码', 'input-text', '', '', '1', '1', '\"\"', '\"\"', '1', ''),
( 'program-replace-setting', 'name', '配置名称', 'input-text', '', '', '2', '1', '\"\"', '\"\"', '1', ''),
( 'program-replace-setting', 'is_open', '是否开放', 'ele-switch', '', '', '3', '1', '\"\"', '\"\"', '1', ''),
( 'program-replace-setting', 'sort_order', '排序', 'input-number', '127', '', '4', '1', '\"\"', '\"\"', '1', ''),
( 'program-replace-setting', 'description', '描述', 'input-area', '', '', '5', '1', '\"\"', '\"\"', '1', ''),
( 'program-replace-setting', 'replace_fields', '替换字段', 'json-editor', '', '', '6', '1', '\"\"', '\"\"', '0', ''),
( 'program-replace-setting', 'template', '母版', 'vue-editor', '', '', '7', '1', '\"\"', '\"\"', '0', ''),
( 'program-replace-setting', 'content', '模版', 'vue-editor', '', '', '8', '1', '\"\"', '\"\"', '0', '');
