

-- ----------------------------
--  Data of "pub_form_category"
-- ----------------------------

insert into `pub_form_category`
( `key`, `name`, `description`, `sort_order`, `is_setting`, `is_open`, `is_enable`)
values
( 'program-interface-system-manage', '程序员管理——接口系统管理', '程序员管理——接口系统管理', '5', '0', '0', '1'),
( 'program-interface-type-manage', '程序员管理——接口分类', '程序员管理——接口分类', '6', '0', '0', '1'),
( 'program-interface-manage', '程序员管理——接口管理', '程序员管理——接口管理', '7', '0', '0', '1'),
( 'program-replace-setting', '程序员管理——替换模版管理', '程序员管理——替换模版管理', '21', '0', '0', '1'),
( 'program-logs-operate', '程序员管理——操作日志', '程序员管理——操作日志', '101', '0', '0', '1'),
( 'program-logs-interface-route', '程序员管理——接口路由日志', '程序员管理——接口路由日志', '102', '0', '0', '1'),
( 'program-logs-interface-access', '程序员管理——接口访问日志', '程序员管理——接口访问日志', '103', '0', '0', '1');


-- ----------------------------
--  Data of "pub_form_option"
-- ----------------------------

insert into `pub_form_option`
( `key`, `field`, `label`, `input_type`, `default`, `description`, `sort_order`, `is_enable`, `exts`, `rules`, `is_required`, `required_msg`)
values
( 'program-interface-system-manage', 'id', 'ID', 'view-text', '', '', '1', '1', '\"\"', '\"\"', '0', ''),
( 'program-interface-system-manage', 'rule', '系统规则', 'input-select', '', '', '2', '1', '{\"options\": {\"inner\": \"当前系统\", \"outer\": \"外部系统\", \"transfer\": \"内部系统\"}}', '\"\"', '1', ''),
( 'program-interface-system-manage', 'system_alias', '系统别名', 'input-text', '', '', '3', '1', '\"\"', '\"\"', '1', ''),
( 'program-interface-system-manage', 'system_name', '系统名称', 'input-text', '', '', '4', '1', '\"\"', '\"\"', '1', ''),
( 'program-interface-system-manage', 'sort_order', '排序', 'input-number', '127', '', '5', '1', '\"\"', '\"\"', '1', ''),
( 'program-interface-system-manage', 'uri_prefix', 'URI前缀', 'input-text', '', '', '6', '1', '\"\"', '\"\"', '0', ''),
( 'program-interface-system-manage', 'is_enable', '启用状态', 'ele-switch', '', '', '7', '1', '\"\"', '\"\"', '0', ''),
( 'program-interface-system-manage', 'is_allow_new_interface', '接受新接口', 'ele-switch', '', '', '7', '1', '\"\"', '\"\"', '0', ''),
( 'program-interface-system-manage', 'is_record_field', '记录新字段', 'ele-switch', '', '', '7', '1', '\"\"', '\"\"', '0', ''),
( 'program-interface-system-manage', 'is_open_access_log', '开启访问日志', 'ele-switch', '', '', '7', '1', '\"\"', '\"\"', '0', ''),
( 'program-interface-system-manage', 'is_open_validate', '开启接口校验', 'ele-switch', '', '', '7', '1', '\"\"', '\"\"', '0', ''),
( 'program-interface-system-manage', 'is_strict_validate', '开启严格校验', 'ele-switch', '', '', '7', '1', '\"\"', '\"\"', '0', ''),
( 'program-interface-system-manage', 'description', '描述', 'input-area', '', '', '11', '1', '\"\"', '\"\"', '0', ''),
( 'program-interface-system-manage', 'ext', '扩展数据', 'json-editor', '', '', '12', '1', '\"\"', '\"\"', '0', ''),
( 'program-interface-system-manage', 'updated_at', '更新时间', 'view-text', '', '', '13', '1', '\"\"', '\"\"', '0', ''),
( 'program-interface-system-manage', 'created_at', '创建时间', 'view-text', '', '', '14', '1', '\"\"', '\"\"', '0', '');

insert into `pub_form_option`
( `key`, `field`, `label`, `input_type`, `default`, `description`, `sort_order`, `is_enable`, `exts`, `rules`, `is_required`, `required_msg`)
values
( 'program-interface-type-manage', 'id', 'ID', 'view-text', '', '', '1', '1', '\"\"', '\"\"', '0', ''),
( 'program-interface-type-manage', 'system_alias', '所属系统', 'input-select', '', '', '2', '1', '\"\"', '\"\"', '1', ''),
( 'program-interface-type-manage', 'type', '类型标记', 'input-text', '', '', '3', '1', '\"\"', '\"\"', '1', ''),
( 'program-interface-type-manage', 'type_name', '类型名称', 'input-text', '', '', '4', '1', '\"\"', '\"\"', '1', ''),
( 'program-interface-type-manage', 'sort_order', '排序', 'input-number', '0', '', '5', '1', '\"\"', '\"\"', '1', ''),
( 'program-interface-type-manage', 'description', '路由描述', 'input-area', '', '', '6', '1', '\"\"', '\"\"', '0', ''),
( 'program-interface-type-manage', 'created_at', '创建时间', 'view-text', '', '', '7', '1', '\"\"', '\"\"', '0', ''),
( 'program-interface-type-manage', 'updated_at', '更新时间', 'view-text', '', '', '8', '1', '\"\"', '\"\"', '0', '');

insert into `pub_form_option`
( `key`, `field`, `label`, `input_type`, `default`, `description`, `sort_order`, `is_enable`, `exts`, `rules`, `is_required`, `required_msg`)
values
( 'program-interface-manage', 'id', 'ID', 'view-text', '', '', '1', '1', '\"\"', '\"\"', '0', ''),
( 'program-interface-manage', 'system_alias', '所属系统', 'input-select', '', '', '2', '1', '\"\"', '\"\"', '1', ''),
( 'program-interface-manage', 'type', '接口分类', 'input-select', '', '', '3', '1', '\"\"', '\"\"', '1', ''),
( 'program-interface-manage', 'name', '接口名称', 'input-text', '', '', '4', '1', '\"\"', '\"\"', '1', ''),
( 'program-interface-manage', 'alias', '接口别名', 'input-text', '', '', '5', '1', '\"\"', '\"\"', '1', ''),
( 'program-interface-manage', 'uri_path', '接口path', 'input-text', '', '', '6', '1', '\"\"', '\"\"', '1', ''),
( 'program-interface-manage', 'description', '描述', 'input-area', '', '', '7', '1', '\"\"', '\"\"', '0', ''),
( 'program-interface-manage', 'is_operate', '是否操作类', 'ele-switch', '', '', '8', '1', '\"\"', '\"\"', '0', ''),
( 'program-interface-manage', 'record_field_type', '字段记录方式', 'input-select', '', '', '9', '1', '{\"options\": [\"随系统\", \"强制开启\", \"强制关闭\"]}', '\"\"', '1', ''),
( 'program-interface-manage', 'access_log_type', '日志记录方式', 'input-select', '', '', '10', '1', '{\"options\": [\"随系统\", \"强制开启\", \"强制关闭\"]}', '\"\"', '1', ''),
( 'program-interface-manage', 'validate_type', '接口校验方式', 'input-select', '', '', '11', '1', '{\"options\": [\"随系统\", \"强制开启\", \"强制关闭\"]}', '\"\"', '1', ''),
( 'program-interface-manage', 'strict_validate_type', '开启严格校验', 'input-select', '', '', '12', '1', '{\"options\": [\"随系统\", \"强制开启\", \"强制关闭\"]}', '\"\"', '1', ''),
( 'program-interface-manage', 'is_open_mocking', '开启mock', 'ele-switch', '', '', '13', '1', '\"\"', '\"\"', '0', ''),
( 'program-interface-manage', 'is_use_custom_mock', '自定义mock', 'ele-switch', '', '', '14', '1', '\"\"', '\"\"', '0', ''),
( 'program-interface-manage', 'mock_response', '自定义mock响应', 'json-editor', '', '', '15', '1', '\"\"', '\"\"', '0', ''),
( 'program-interface-manage', 'is_open_route_log', '开启路由日志', 'ele-switch', '', '', '16', '1', '\"\"', '\"\"', '0', ''),
( 'program-interface-manage', 'route_log_message', '路由操作提示', 'input-text', '', '', '17', '1', '\"\"', '\"\"', '0', ''),
( 'program-interface-manage', 'route_log_key_fields', '路由参数关键字', 'input-area', '', '', '18', '1', '\"\"', '\"\"', '0', ''),
( 'program-interface-manage', 'created_at', '创建时间', 'view-text', '', '', '19', '1', '\"\"', '\"\"', '0', ''),
( 'program-interface-manage', 'updated_at', '更新时间', 'view-text', '', '', '20', '1', '\"\"', '\"\"', '0', '');

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

insert into `pub_form_option`
( `key`, `field`, `label`, `input_type`, `default`, `description`, `sort_order`, `is_enable`, `exts`, `rules`, `is_required`, `required_msg`)
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

insert into `pub_form_option`
( `key`, `field`, `label`, `input_type`, `default`, `description`, `sort_order`, `is_enable`, `exts`, `rules`, `is_required`, `required_msg`)
values
( 'program-logs-interface-route', 'interface_id', '接口ID', 'view-text', '', '', '1', '1', '\"\"', '\"\"', '0', ''),
( 'program-logs-interface-route', 'name', '接口名称', 'view-text', '', '', '2', '1', '\"\"', '\"\"', '0', ''),
( 'program-logs-interface-route', 'system_alias', '所属系统', 'input-select', '', '', '3', '1', '\"\"', '\"\"', '0', ''),
( 'program-logs-interface-route', 'type', '接口分类', 'input-select', '', '', '4', '1', '\"\"', '\"\"', '0', ''),
( 'program-logs-interface-route', 'is_operate', '是否操作类', 'input-select', '', '', '5', '1', '{\"options\": [\"否\", \"是\"]}', '\"\"', '0', ''),
( 'program-logs-interface-route', 'uri_path', '接口path', 'view-text', '', '', '6', '1', '\"\"', '\"\"', '0', ''),
( 'program-logs-interface-route', 'alias', '接口别名', 'view-text', '', '', '7', '1', '\"\"', '\"\"', '0', ''),
( 'program-logs-interface-route', 'method', '请求方式', 'view-text', '', '', '8', '1', '\"\"', '\"\"', '0', ''),
( 'program-logs-interface-route', 'id', 'ID', 'view-text', '', '', '9', '1', '\"\"', '\"\"', '0', ''),
( 'program-logs-interface-route', 'is_success', '是否成功', 'input-select', '', '', '10', '1', '{\"options\": [\"否\", \"是\"]}', '\"\"', '0', ''),
( 'program-logs-interface-route', 'trace_id', 'Trace-ID', 'view-text', '', '', '11', '1', '\"\"', '\"\"', '0', ''),
( 'program-logs-interface-route', 'use_time', '接口耗时', 'view-text', '', '', '12', '1', '\"\"', '\"\"', '0', ''),
( 'program-logs-interface-route', 'keyword', '关键字', 'view-text', '', '', '13', '1', '\"\"', '\"\"', '0', ''),
( 'program-logs-interface-route', 'message', '消息', 'view-text', '', '', '14', '1', '\"\"', '\"\"', '0', ''),
( 'program-logs-interface-route', 'input', '请求数据', 'json-editor', '0', '', '15', '1', '\"\"', '\"\"', '0', ''),
( 'program-logs-interface-route', 'output', '响应数据', 'json-editor', '0', '', '16', '1', '\"\"', '\"\"', '0', ''),
( 'program-logs-interface-route', 'exts', '扩展数据', 'json-editor', '0', '', '17', '1', '\"\"', '\"\"', '0', ''),
( 'program-logs-interface-route', 'uid', 'UID', 'view-text', '', '', '18', '1', '\"\"', '\"\"', '0', ''),
( 'program-logs-interface-route', 'ip', 'IP', 'view-text', '', '', '19', '1', '\"\"', '\"\"', '0', ''),
( 'program-logs-interface-route', 'created_at', '创建时间', 'view-text', '', '', '20', '1', '\"\"', '\"\"', '0', '');

insert into `pub_form_option`
( `key`, `field`, `label`, `input_type`, `default`, `description`, `sort_order`, `is_enable`, `exts`, `rules`, `is_required`, `required_msg`)
values
( 'program-logs-interface-access', 'interface_id', '接口ID', 'view-text', '', '', '1', '1', '\"\"', '\"\"', '0', ''),
( 'program-logs-interface-access', 'name', '接口名称', 'view-text', '', '', '2', '1', '\"\"', '\"\"', '0', ''),
( 'program-logs-interface-access', 'system_alias', '所属系统', 'input-select', '', '', '3', '1', '\"\"', '\"\"', '0', ''),
( 'program-logs-interface-access', 'type', '接口分类', 'input-select', '', '', '4', '1', '\"\"', '\"\"', '0', ''),
( 'program-logs-interface-access', 'is_operate', '是否操作类', 'input-select', '', '', '5', '1', '{\"options\": [\"否\", \"是\"]}', '\"\"', '0', ''),
( 'program-logs-interface-access', 'uri_path', '接口path', 'view-text', '', '', '6', '1', '\"\"', '\"\"', '0', ''),
( 'program-logs-interface-access', 'alias', '接口别名', 'view-text', '', '', '7', '1', '\"\"', '\"\"', '0', ''),
( 'program-logs-interface-access', 'method', '请求方式', 'view-text', '', '', '8', '1', '\"\"', '\"\"', '0', ''),
( 'program-logs-interface-access', 'id', 'ID', 'view-text', '', '', '9', '1', '\"\"', '\"\"', '0', ''),
( 'program-logs-interface-access', 'is_success', '是否成功', 'input-select', '', '', '10', '1', '{\"options\": [\"否\", \"是\"]}', '\"\"', '0', ''),
( 'program-logs-interface-access', 'is_intercept', '是否中断', 'input-select', '', '', '11', '1', '{\"options\": [\"否\", \"是\"]}', '\"\"', '0', ''),
( 'program-logs-interface-access', 'trace_id', 'Trace-ID', 'view-text', '', '', '12', '1', '\"\"', '\"\"', '0', ''),
( 'program-logs-interface-access', 'use_time', '接口耗时', 'view-text', '', '', '13', '1', '\"\"', '\"\"', '0', ''),
( 'program-logs-interface-access', 'response_time', '响应耗时', 'view-text', '', '', '14', '1', '\"\"', '\"\"', '0', ''),
( 'program-logs-interface-access', 'response_code', '响应码', 'view-text', '', '', '15', '1', '\"\"', '\"\"', '0', ''),
( 'program-logs-interface-access', 'response_data', '响应数据', 'json-editor', '0', '', '16', '1', '\"\"', '\"\"', '0', ''),
( 'program-logs-interface-access', 'message', '消息', 'view-text', '', '', '17', '1', '\"\"', '\"\"', '0', ''),
( 'program-logs-interface-access', 'exts', '扩展数据', 'json-editor', '0', '', '18', '1', '\"\"', '\"\"', '0', ''),
( 'program-logs-interface-access', 'uid', 'UID', 'view-text', '', '', '19', '1', '\"\"', '\"\"', '0', ''),
( 'program-logs-interface-access', 'ip', 'IP', 'view-text', '', '', '20', '1', '\"\"', '\"\"', '0', ''),
( 'program-logs-interface-access', 'created_at', '创建时间', 'view-text', '', '', '21', '1', '\"\"', '\"\"', '0', '');

