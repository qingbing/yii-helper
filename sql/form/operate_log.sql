
-- ----------------------------
--  Data for form-key "program-logs-operate"
-- ----------------------------

-- pub_form_category
insert into `pub_form_category`
( `key`, `name`, `description`, `sort_order`, `is_setting`, `is_open`, `is_enable`)
values
( 'program-logs-operate', '程序员管理——操作日志', '程序员管理——操作日志', '4', '0', '0', '1');


-- pub_form_option
insert into `pub_form_option` ( `key`, `field`, `label`, `input_type`, `default`, `description`, `sort_order`, `is_enable`, `exts`, `rules`, `is_required`, `required_msg`)
values
( 'program-logs-operate', 'id', 'ID', 'view-text', '', '', '1', '1', '\"\"', '\"\"', '0', ''),
( 'program-logs-operate', 'trace_id', 'Trace-ID', 'view-text', '', '', '2', '1', '\"\"', '\"\"', '0', ''),
( 'program-logs-operate', 'system_alias', '所属系统', 'input-select', '', '', '3', '1', '\"\"', '\"\"', '0', ''),
( 'program-logs-operate', 'type', '操作类型', 'view-text', '', '', '4', '1', '\"\"', '\"\"', '0', ''),
( 'program-logs-operate', 'message', '访问消息', 'view-text', '', '', '5', '1', '\"\"', '\"\"', '0', ''),
( 'program-logs-operate', 'keyword', '关键字', 'view-text', '', '', '6', '1', '\"\"', '\"\"', '0', ''),
( 'program-logs-operate', 'uid', 'UID', 'view-text', '', '', '7', '1', '\"\"', '\"\"', '0', ''),
( 'program-logs-operate', 'nickname', '用户昵称', 'view-text', '', '', '8', '1', '\"\"', '\"\"', '0', ''),
( 'program-logs-operate', 'ip', 'IP', 'view-text', '', '', '9', '1', '\"\"', '\"\"', '0', ''),
( 'program-logs-operate', 'created_at', '创建时间', 'view-text', '', '', '10', '1', '\"\"', '\"\"', '0', ''),
( 'program-logs-operate', 'data', '操作数据', 'json-editor', '0', '', '11', '1', '\"\"', '\"\"', '0', '');

