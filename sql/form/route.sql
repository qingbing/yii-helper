
-- ----------------------------
--  Data for form-key "program-route-type"
-- ----------------------------

-- pub_form_category
insert into `pub_form_category`
( `key`, `name`, `description`, `sort_order`, `is_setting`, `is_open`, `is_enable`)
values
( 'program-route-type', '程序员管理——路由类型', '程序员管理——路由类型', '1', '0', '0', '1');

-- pub_form_option
insert into `pub_form_option`
( `key`, `field`, `label`, `input_type`, `default`, `description`, `sort_order`, `is_enable`, `exts`, `rules`, `is_required`, `required_msg`)
values
( 'program-route-type', 'id', 'ID', 'view-text', '', '', '1', '1', '\"\"', '\"\"', '0', ''),
( 'program-route-type', 'system_alias', '所属系统', 'input-select', '', '', '2', '1', '\"\"', '\"\"', '1', ''),
( 'program-route-type', 'route_type', '类型标记', 'input-text', '', '', '3', '1', '\"\"', '\"\"', '1', ''),
( 'program-route-type', 'type_name', '类型名称', 'input-text', '', '', '4', '1', '\"\"', '\"\"', '1', ''),
( 'program-route-type', 'sort_order', '排序', 'input-number', '0', '', '5', '1', '\"\"', '\"\"', '1', ''),
( 'program-route-type', 'description', '路由描述', 'input-area', '', '', '6', '1', '\"\"', '\"\"', '0', ''),
( 'program-route-type', 'created_at', '创建时间', 'view-text', '', '', '7', '1', '\"\"', '\"\"', '0', ''),
( 'program-route-type', 'updated_at', '更新时间', 'view-text', '', '', '8', '1', '\"\"', '\"\"', '0', '');



-- ----------------------------
--  Data for form-key "program-route-record"
-- ----------------------------

-- pub_form_category
insert into `pub_form_category`
( `key`, `name`, `description`, `sort_order`, `is_setting`, `is_open`, `is_enable`)
values
( 'program-route-record', '程序员管理——路由管理', '程序员管理——路由管理', '2', '0', '0', '1');

-- pub_form_option
insert into `pub_form_option`
( `key`, `field`, `label`, `input_type`, `default`, `description`, `sort_order`, `is_enable`, `exts`, `rules`, `is_required`, `required_msg`)
values
( 'program-route-record', 'id', 'ID', 'view-text', '', '', '1', '1', '\"\"', '\"\"', '0', ''),
( 'program-route-record', 'system_alias', '所属系统', 'input-select', '', '', '2', '1', '\"\"', '\"\"', '1', ''),
( 'program-route-record', 'route_type', '类型名称', 'input-select', '', '', '3', '1', '\"\"', '\"\"', '1', ''),
( 'program-route-record', 'route', '路由', 'input-text', '', '', '4', '1', '\"\"', '\"\"', '1', ''),
( 'program-route-record', 'sort_order', '排序', 'input-number', '0', '', '5', '1', '\"\"', '\"\"', '1', ''),
( 'program-route-record', 'is_operate', '操作路由', 'ele-switch', '', '', '6', '1', '\"\"', '\"\"', '0', ''),
( 'program-route-record', 'access_times', '访问次数', 'view-text', '', '', '7', '1', '\"\"', '\"\"', '0', ''),
( 'program-route-record', 'description', '路由描述', 'input-area', '', '', '8', '1', '\"\"', '\"\"', '0', ''),
( 'program-route-record', 'is_logging', '开启日志', 'ele-switch', '', '', '9', '1', '\"\"', '\"\"', '0', ''),
( 'program-route-record', 'logging_message', '路由消息', 'input-area', '', '', '10', '1', '\"\"', '\"\"', '0', ''),
( 'program-route-record', 'logging_key_fields', '参数关键字', 'input-area', '', '', '11', '1', '\"\"', '\"\"', '0', ''),
( 'program-route-record', 'is_mocking', '开启mock', 'ele-switch', '', '', '12', '1', '\"\"', '\"\"', '0', ''),
( 'program-route-record', 'mocking_response', 'mock响应', 'json-editor', '', '', '13', '1', '\"\"', '\"\"', '0', ''),
( 'program-route-record', 'created_at', '创建时间', 'view-text', '', '', '14', '1', '\"\"', '\"\"', '0', ''),
( 'program-route-record', 'updated_at', '更新时间', 'view-text', '', '', '15', '1', '\"\"', '\"\"', '0', '');



-- ----------------------------
--  Data for form-key "program-logs-route"
-- ----------------------------

-- pub_form_category
insert into `pub_form_category`
( `key`, `name`, `description`, `sort_order`, `is_setting`, `is_open`, `is_enable`)
values
( 'program-logs-route', '程序员管理——路由日志', '程序员管理——路由日志', '3', '0', '0', '1');

-- pub_form_option
insert into `pub_form_option`
( `key`, `field`, `label`, `input_type`, `default`, `description`, `sort_order`, `is_enable`, `exts`, `rules`, `is_required`, `required_msg`)
values
( 'program-logs-route', 'id', 'ID', 'view-text', '', '', '1', '1', '\"\"', '\"\"', '0', ''),
( 'program-logs-route', 'system_alias', '所属系统', 'input-select', '', '', '2', '1', '\"\"', '\"\"', '1', ''),
( 'program-logs-route', 'route_type', '类型名称', 'input-select', '', '', '3', '1', '\"\"', '\"\"', '1', ''),
( 'program-logs-route', 'route', '路由', 'view-text', '', '', '4', '1', '\"\"', '\"\"', '1', ''),
( 'program-logs-route', 'trace_id', 'Trace-ID', 'view-text', '', '', '5', '1', '\"\"', '\"\"', '0', ''),
( 'program-logs-route', 'is_success', '操作状态', 'input-select', '', '{\"options\":[\"否\",\"是\"]}', '6', '1', '{\"options\": [\"否\", \"是\"]}', '\"\"', '0', ''),
( 'program-logs-route', 'message', '访问消息', 'view-text', '', '', '7', '1', '\"\"', '\"\"', '0', ''),
( 'program-logs-route', 'access_times', '访问次数', 'view-text', '', '', '8', '1', '\"\"', '\"\"', '0', ''),
( 'program-logs-route', 'is_operate', '是否操作日志', 'input-select', '', '', '9', '1', '{\"options\": [\"否\", \"是\"]}', '\"\"', '0', ''),
( 'program-logs-route', 'ip', 'IP', 'view-text', '', '', '10', '1', '\"\"', '\"\"', '0', ''),
( 'program-logs-route', 'uid', 'UID', 'view-text', '', '', '11', '1', '\"\"', '\"\"', '0', ''),
( 'program-logs-route', 'keyword', '访问关键字', 'view-text', '', '', '12', '1', '\"\"', '\"\"', '0', ''),
( 'program-logs-route', 'input', 'request', 'json-editor', '0', '', '13', '1', '\"\"', '\"\"', '1', ''),
( 'program-logs-route', 'output', 'response', 'json-editor', '0', '', '14', '1', '\"\"', '\"\"', '1', ''),
( 'program-logs-route', 'exts', '扩展信息', 'json-editor', '', '', '15', '1', '\"\"', '\"\"', '0', ''),
( 'program-logs-route', 'created_at', '创建时间', 'view-text', '', '', '16', '1', '\"\"', '\"\"', '0', '');
