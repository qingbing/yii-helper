
-- ----------------------------
--  Data of "pub_header"
-- ----------------------------

insert into `pub_header`
( `key`, `name`, `description`, `sort_order`, `is_open`)
values
( 'program-form-category', '程序员管理系统-表单管理', '程序员管理系统-表单管理', '3', '0'),
( 'program-form-options', '程序员管理系统-表单选项管理', '程序员管理系统-表单选项管理', '4', '0'),
( 'program-interface-system-manage', '程序员管理系统-接口系统管理', '程序员管理系统-接口系统管理', '5', '0'),
( 'program-interface-type-manage', '程序员管理系统-接口分类管理', '程序员管理系统-接口分类管理', '6', '0'),
( 'program-interface-manage', '程序员管理系统-接口管理', '程序员管理系统-接口管理', '7', '0'),
( 'program-replace-setting', '程序员管理系统-替换模版管理', '程序员管理系统-替换模版管理', '21', '0'),
( 'program-logs-operate', '程序员管理系统-操作日志', '程序员管理系统-操作日志', '101', '0'),
( 'program-logs-interface-route', '程序员管理系统-接口路由日志', '程序员管理系统-接口路由日志', '102', '0'),
( 'program-logs-interface-access', '程序员管理系统-接口访问日志', '程序员管理系统-接口访问日志', '103', '0');



-- ----------------------------
--  Data of "pub_header_option"
-- ----------------------------

insert into `pub_header_option`
( `key`, `field`, `label`, `width`, `fixed`, `default`, `align`, `is_tooltip`, `is_resizable`, `is_editable`, `component`, `options`, `params`, `description`, `sort_order`, `is_required`, `is_default`, `is_enable`, `operate_ip`, `operate_uid`)
values
( 'program-form-category', '_idx', '序号', '50', 'left', '', '', '0', '1', '0', '', '\"\"', '\"\"', '', '1', '1', '1', '1', '192.168.1.1', '100000000'),
( 'program-form-category', 'key', '表单标识', '240', 'left', '', 'left', '0', '1', '0', '', '\"\"', '\"\"', '', '2', '1', '1', '1', '192.168.1.1', '100000000'),
( 'program-form-category', 'name', '表单名称', '300', '', '', 'left', '1', '0', '0', '', '\"\"', '\"\"', '', '3', '1', '1', '1', '192.168.1.1', '100000000'),
( 'program-form-category', 'sort_order', '排序', '60', '', '', '', '0', '0', '0', '', '\"\"', '\"\"', '', '4', '0', '0', '1', '192.168.1.1', '100000000'),
( 'program-form-category', 'is_enable', '启用状态', '80', '', '', '', '0', '0', '0', '', '[\"禁用\", \"启用\"]', '\"\"', '', '5', '1', '1', '1', '192.168.1.1', '100000000'),
( 'program-form-category', 'is_open', '是否公开', '80', '', '', '', '0', '0', '0', '', '[\"否\", \"是\"]', '\"\"', '', '6', '1', '1', '1', '192.168.1.1', '100000000'),
( 'program-form-category', 'is_setting', '配置表单', '80', '', '', '', '0', '0', '0', '', '[\"否\", \"是\"]', '\"\"', '', '7', '1', '1', '1', '192.168.1.1', '100000000'),
( 'program-form-category', 'operate', '操作', '', 'right', '', 'left', '0', '0', '0', 'operate', '\"\"', '[]', '', '8', '1', '1', '1', '192.168.1.1', '100000000'),
( 'program-form-options', '_idx', '序号', '50', 'left', '', '', '0', '0', '0', '', '\"\"', '\"\"', '', '1', '0', '1', '1', '192.168.1.1', '100000000'),
( 'program-form-options', 'field', '字段', '100', 'left', '', 'left', '1', '0', '0', '', '\"\"', '\"\"', '', '2', '1', '1', '1', '192.168.1.1', '100000000'),
( 'program-form-options', 'label', '字段名', '150', '', '', 'left', '1', '0', '1', '', '\"\"', '{\"type\": \"text\"}', '', '3', '1', '1', '1', '192.168.1.1', '100000000'),
( 'program-form-options', 'input_type', '表单类型', '100', '', '', 'left', '0', '0', '0', '', '{\"ele-rate\": \"评分\", \"ele-color\": \"颜色\", \"view-text\": \"显示\", \"ele-slider\": \"滑块\", \"ele-switch\": \"开关按钮\", \"input-area\": \"文本域\", \"input-text\": \"文本框\", \"vue-editor\": \"富文本编辑\", \"date-picker\": \"日期组件\", \"input-radio\": \"单选组\", \"json-editor\": \"JSON编辑\", \"time-picker\": \"时间组件\", \"ele-cascader\": \"级联\", \"ele-uploader\": \"上传\", \"input-number\": \"文本数字\", \"input-select\": \"下拉框\", \"auto-complete\": \"建议输入\", \"input-checkbox\": \"复选组\", \"input-password\": \"密码框\"}', '[]', '', '4', '1', '1', '1', '192.168.1.1', '100000000'),
( 'program-form-options', 'sort_order', '排序', '80', '', '', '', '0', '0', '1', '', '\"\"', '{\"type\": \"text\"}', '', '5', '0', '1', '1', '192.168.1.1', '100000000'),
( 'program-form-options', 'default', '默认值', '100', '', '', '', '0', '0', '1', '', '\"\"', '{\"type\": \"text\"}', '', '6', '0', '1', '1', '192.168.1.1', '100000000'),
( 'program-form-options', 'is_required', '必填', '80', '', '', '', '0', '0', '1', '', '\"\"', '{\"type\": \"switch\"}', '', '7', '1', '1', '1', '192.168.1.1', '100000000'),
( 'program-form-options', 'is_enable', '是否开启', '80', '', '', '', '0', '0', '1', '', '\"\"', '{\"type\": \"switch\"}', '', '8', '1', '1', '1', '192.168.1.1', '100000000'),
( 'program-form-options', 'operate', '操作', '260', 'right', '', 'left', '0', '0', '0', 'operate', '\"\"', '[]', '', '9', '1', '1', '1', '192.168.1.1', '100000000');

insert into `pub_header_option`
( `key`, `field`, `label`, `width`, `fixed`, `default`, `align`, `is_tooltip`, `is_resizable`, `is_editable`, `component`, `options`, `params`, `description`, `sort_order`, `is_required`, `is_default`, `is_enable`, `operate_ip`, `operate_uid`)
values
( 'program-interface-system-manage', '_idx', '序号', '50', 'left', '', '', '0', '0', '0', '', '\"\"', '\"\"', '', '1', '0', '1', '1', '192.168.1.1', '100000000'),
( 'program-interface-system-manage', 'rule', '系统规则', '100', '', '', 'left', '1', '0', '0', '', '{\"inner\": \"当前系统\", \"outer\": \"外部系统\", \"transfer\": \"内部系统\"}', '\"\"', '', '2', '1', '1', '1', '192.168.1.1', '100000000'),
( 'program-interface-system-manage', 'id', 'ID', '60', '', '', 'left', '0', '0', '0', '', '\"\"', '\"\"', '', '3', '0', '0', '0', '192.168.1.1', '100000000'),
( 'program-interface-system-manage', 'system_alias', '系统别名', '100', '', '', 'left', '0', '0', '0', '', '\"\"', '\"\"', '', '4', '1', '1', '1', '192.168.1.1', '100000000'),
( 'program-interface-system-manage', 'system_name', '系统名称', '120', '', '', 'left', '0', '0', '0', '', '\"\"', '\"\"', '', '5', '1', '1', '1', '192.168.1.1', '100000000'),
( 'program-interface-system-manage', 'uri_prefix', 'URI前缀', '150', '', '', 'left', '1', '0', '0', '', '\"\"', '\"\"', '', '6', '0', '1', '1', '192.168.1.1', '100000000'),
( 'program-interface-system-manage', 'sort_order', '排序', '80', '', '', 'left', '0', '0', '0', '', '\"\"', '\"\"', '', '7', '1', '1', '1', '192.168.1.1', '100000000'),
( 'program-interface-system-manage', 'is_enable', '启用', '80', '', '', '', '0', '0', '1', '', '\"\"', '{\"type\": \"switch\"}', '', '8', '1', '1', '1', '192.168.1.1', '100000000'),
( 'program-interface-system-manage', 'is_allow_new_interface', '接受新接口', '80', '', '', '', '0', '0', '1', '', '\"\"', '{\"type\": \"switch\"}', '', '9', '1', '1', '1', '192.168.1.1', '100000000'),
( 'program-interface-system-manage', 'is_record_field', '记录新字段', '80', '', '', '', '0', '0', '1', '', '\"\"', '{\"type\": \"switch\"}', '', '10', '1', '1', '1', '192.168.1.1', '100000000'),
( 'program-interface-system-manage', 'is_open_access_log', '开启访问日志', '80', '', '', '', '0', '0', '1', '', '\"\"', '{\"type\": \"switch\"}', '', '11', '1', '1', '1', '192.168.1.1', '100000000'),
( 'program-interface-system-manage', 'is_open_validate', '开启接口校验', '80', '', '', '', '0', '0', '1', '', '\"\"', '{\"type\": \"switch\"}', '', '12', '1', '1', '1', '192.168.1.1', '100000000'),
( 'program-interface-system-manage', 'is_strict_validate', '开启严格校验', '80', '', '', '', '0', '0', '1', '', '\"\"', '{\"type\": \"switch\"}', '', '13', '1', '1', '1', '192.168.1.1', '100000000'),
( 'program-interface-system-manage', 'updated_at', '更新时间', '130', '', '', '', '0', '0', '0', '', '\"\"', '\"\"', '', '14', '0', '0', '0', '192.168.1.1', '100000000'),
( 'program-interface-system-manage', 'created_at', '创建时间', '130', '', '', '', '0', '0', '0', '', '\"\"', '\"\"', '', '15', '0', '0', '0', '192.168.1.1', '100000000'),
( 'program-interface-system-manage', 'operate', '操作', '260', 'right', '', 'left', '0', '0', '0', 'operate', '\"\"', '[]', '', '16', '1', '1', '1', '192.168.1.1', '100000000');

insert into `pub_header_option`
( `key`, `field`, `label`, `width`, `fixed`, `default`, `align`, `is_tooltip`, `is_resizable`, `is_editable`, `component`, `options`, `params`, `description`, `sort_order`, `is_required`, `is_default`, `is_enable`, `operate_ip`, `operate_uid`)
values
( 'program-interface-type-manage', '_idx', '序号', '50', 'left', '', '', '0', '1', '0', '', '\"\"', '\"\"', '', '1', '1', '1', '1', '192.168.1.1', '100000000'),
( 'program-interface-type-manage', 'id', 'ID', '50', 'left', '', 'left', '0', '1', '0', '', '\"\"', '\"\"', '', '2', '0', '0', '0', '192.168.1.1', '100000000'),
( 'program-interface-type-manage', 'type', '分类标识', '120', 'left', '', 'left', '0', '1', '0', '', '\"\"', '\"\"', '', '3', '1', '1', '1', '192.168.1.1', '100000000'),
( 'program-interface-type-manage', 'system_alias', '所属系统', '120', '', '', 'left', '0', '1', '0', '', '\"\"', '\"\"', '', '4', '1', '1', '1', '192.168.1.1', '100000000'),
( 'program-interface-type-manage', 'type_name', '分类名称', '150', '', '', 'left', '1', '0', '0', '', '\"\"', '\"\"', '', '5', '1', '1', '1', '192.168.1.1', '100000000'),
( 'program-interface-type-manage', 'created_at', '创建时间', '130', '', '', '', '0', '0', '0', '', '\"\"', '\"\"', '', '6', '0', '1', '1', '192.168.1.1', '100000000'),
( 'program-interface-type-manage', 'sort_order', '排序', '60', '', '', '', '0', '0', '0', '', '\"\"', '\"\"', '', '6', '0', '1', '1', '192.168.1.1', '100000000'),
( 'program-interface-type-manage', 'operate', '操作', '', 'right', '', 'left', '0', '0', '0', 'operate', '\"\"', '[]', '', '7', '1', '1', '1', '192.168.1.1', '100000000');

insert into `pub_header_option`
( `key`, `field`, `label`, `width`, `fixed`, `default`, `align`, `is_tooltip`, `is_resizable`, `is_editable`, `component`, `options`, `params`, `description`, `sort_order`, `is_required`, `is_default`, `is_enable`, `operate_ip`, `operate_uid`)
values
( 'program-interface-manage', '_idx', '序号', '50', 'left', '', '', '0', '0', '0', '', '\"\"', '\"\"', '', '1', '0', '1', '1', '192.168.1.1', '100000000'),
( 'program-interface-manage', 'id', 'ID', '50', 'left', '', 'left', '0', '1', '0', '', '\"\"', '\"\"', '', '2', '0', '0', '0', '192.168.1.1', '100000000'),
( 'program-interface-manage', 'name', '接口名称', '200', '', '', 'left', '1', '0', '0', '', '\"\"', '\"\"', '', '3', '1', '1', '1', '192.168.1.1', '100000000'),
( 'program-interface-manage', 'uri_path', 'URI路径', '240', '', '', 'left', '1', '0', '0', '', '\"\"', '\"\"', '', '4', '1', '1', '1', '192.168.1.1', '100000000'),
( 'program-interface-manage', 'optionCount', '字段数量', '80', '', '', '', '1', '0', '0', '', '\"\"', '\"\"', '', '5', '1', '1', '1', '192.168.1.1', '100000000'),
( 'program-interface-manage', 'system_alias', '所属系统', '120', '', '', 'left', '0', '1', '0', '', '\"\"', '\"\"', '', '6', '1', '1', '1', '192.168.1.1', '100000000'),
( 'program-interface-manage', 'type', '接口分类', '120', '', '', 'left', '0', '1', '0', '', '\"\"', '\"\"', '', '7', '1', '1', '1', '192.168.1.1', '100000000'),
( 'program-interface-manage', 'record_field_type', '字段记录方式', '120', '', '', '', '0', '0', '1', '', '\"\"', '{\"type\": \"select\", \"options\": [\"随系统\", \"强制开启\", \"强制关闭\"]}', '', '8', '0', '1', '1', '192.168.1.1', '100000000'),
( 'program-interface-manage', 'access_log_type', '日志记录方式', '120', '', '', '', '0', '0', '1', '', '\"\"', '{\"type\": \"select\", \"options\": [\"随系统\", \"强制开启\", \"强制关闭\"]}', '', '9', '0', '1', '1', '192.168.1.1', '100000000'),
( 'program-interface-manage', 'validate_type', '接口校验方式', '120', '', '', '', '0', '0', '1', '', '\"\"', '{\"type\": \"select\", \"options\": [\"随系统\", \"强制开启\", \"强制关闭\"]}', '', '10', '0', '1', '1', '192.168.1.1', '100000000'),
( 'program-interface-manage', 'strict_validate_type', '开启严格校验', '120', '', '', '', '0', '0', '1', '', '\"\"', '{\"type\": \"select\", \"options\": [\"随系统\", \"强制开启\", \"强制关闭\"]}', '', '11', '0', '1', '1', '192.168.1.1', '100000000'),
( 'program-interface-manage', 'is_operate', '是否操作类', '80', '', '', '', '0', '0', '1', '', '\"\"', '{\"type\": \"switch\"}', '', '12', '1', '1', '1', '192.168.1.1', '100000000'),
( 'program-interface-manage', 'is_open_route_log', '开启路由日志', '80', '', '', '', '0', '0', '1', '', '\"\"', '{\"type\": \"switch\"}', '', '13', '1', '1', '1', '192.168.1.1', '100000000'),
( 'program-interface-manage', 'is_open_mocking', '开启mock', '80', '', '', '', '0', '0', '1', '', '\"\"', '{\"type\": \"switch\"}', '', '14', '1', '1', '1', '192.168.1.1', '100000000'),
( 'program-interface-manage', 'is_use_custom_mock', '自定义mock', '80', '', '', '', '0', '0', '1', '', '\"\"', '{\"type\": \"switch\"}', '', '15', '1', '1', '1', '192.168.1.1', '100000000'),
( 'program-interface-manage', 'operate', '操作', '260', 'right', '', 'left', '0', '0', '0', 'operate', '\"\"', '[]', '', '16', '1', '1', '1', '192.168.1.1', '100000000');

insert into `pub_header_option`
( `key`, `field`, `label`, `width`, `fixed`, `default`, `align`, `is_tooltip`, `is_resizable`, `is_editable`, `component`, `options`, `params`, `description`, `sort_order`, `is_required`, `is_default`, `is_enable`, `operate_ip`, `operate_uid`)
values
( 'program-replace-setting', '_idx', '序号', '50', 'left', '', '', '0', '0', '0', '', '\"\"', '\"\"', '', '1', '0', '1', '1', '192.168.1.1', '100000000'),
( 'program-replace-setting', 'code', '标识码', '150', 'left', '', 'left', '0', '0', '0', '', '\"\"', '\"\"', '', '2', '1', '1', '1', '192.168.1.1', '100000000'),
( 'program-replace-setting', 'name', '配置名称', '150', '', '', 'left', '0', '0', '0', '', '\"\"', '\"\"', '', '3', '1', '1', '1', '192.168.1.1', '100000000'),
( 'program-replace-setting', 'sort_order', '排序', '80', '', '', 'left', '0', '0', '0', '', '\"\"', '\"\"', '', '4', '1', '1', '1', '192.168.1.1', '100000000'),
( 'program-replace-setting', 'is_open', '是否开放', '80', '', '', '', '0', '0', '1', '', '\"\"', '{\"type\": \"switch\"}', '', '5', '1', '1', '1', '192.168.1.1', '100000000'),
( 'program-replace-setting', 'operate', '操作', '', 'right', '', 'left', '0', '0', '0', 'operate', '\"\"', '[]', '', '6', '1', '1', '1', '192.168.1.1', '100000000');

insert into `pub_header_option`
( `key`, `field`, `label`, `width`, `fixed`, `default`, `align`, `is_tooltip`, `is_resizable`, `is_editable`, `component`, `options`, `params`, `description`, `sort_order`, `is_required`, `is_default`, `is_enable`, `operate_ip`, `operate_uid`)
values
( 'program-logs-operate', '_idx', '序号', '50', 'left', '', '', '0', '0', '0', '', '\"\"', '\"\"', '', '1', '0', '1', '1', '192.168.1.1', '100000000'),
( 'program-logs-operate', 'id', 'ID', '50', 'left', '', 'left', '0', '1', '0', '', '\"\"', '\"\"', '', '2', '1', '1', '1', '192.168.1.1', '100000000'),
( 'program-logs-operate', 'system_alias', '所属系统', '120', '', '', 'left', '0', '1', '0', '', '\"\"', '\"\"', '', '3', '1', '1', '1', '192.168.1.1', '100000000'),
( 'program-logs-operate', 'trace_id', '链路ID', '280', '', '', '', '1', '0', '0', '', '\"\"', '\"\"', '', '4', '0', '1', '1', '192.168.1.1', '100000000'),
( 'program-logs-operate', 'type', '操作类型', '120', '', '', 'left', '0', '1', '0', '', '\"\"', '\"\"', '', '5', '0', '1', '1', '192.168.1.1', '100000000'),
( 'program-logs-operate', 'keyword', '关键字', '100', '', '', 'left', '1', '1', '0', '', '\"\"', '\"\"', '', '6', '0', '1', '1', '192.168.1.1', '100000000'),
( 'program-logs-operate', 'message', '操作消息', '120', '', '', 'left', '1', '1', '0', '', '\"\"', '\"\"', '', '7', '0', '1', '1', '192.168.1.1', '100000000'),
( 'program-logs-operate', 'ip', '操作IP', '120', '', '', 'left', '1', '1', '0', '', '\"\"', '\"\"', '', '8', '0', '0', '1', '192.168.1.1', '100000000'),
( 'program-logs-operate', 'uid', '操作UID', '100', '', '', 'left', '0', '1', '0', '', '\"\"', '\"\"', '', '9', '0', '0', '1', '192.168.1.1', '100000000'),
( 'program-logs-operate', 'nickname', '昵称', '120', '', '', 'left', '1', '1', '0', '', '\"\"', '\"\"', '', '10', '0', '1', '1', '192.168.1.1', '100000000'),
( 'program-logs-operate', 'created_at', '创建时间', '130', '', '', '', '0', '0', '0', '', '\"\"', '\"\"', '', '11', '1', '1', '1', '192.168.1.1', '100000000'),
( 'program-logs-operate', 'operate', '操作', '260', 'right', '', 'left', '0', '0', '0', 'operate', '\"\"', '[]', '', '12', '1', '1', '1', '192.168.1.1', '100000000');

insert into `pub_header_option`
( `key`, `field`, `label`, `width`, `fixed`, `default`, `align`, `is_tooltip`, `is_resizable`, `is_editable`, `component`, `options`, `params`, `description`, `sort_order`, `is_required`, `is_default`, `is_enable`, `operate_ip`, `operate_uid`)
values
( 'program-logs-interface-route', '_idx', '序号', '50', 'left', '', '', '0', '0', '0', '', '\"\"', '\"\"', '', '1', '0', '1', '1', '192.168.1.1', '100000000'),
( 'program-logs-interface-route', 'uri_path', '接口path', '120', 'left', '', 'left', '0', '1', '0', '', '\"\"', '\"\"', '', '2', '0', '1', '1', '192.168.1.1', '100000000'),
( 'program-logs-interface-route', 'id', 'ID', '50', '', '', 'left', '0', '1', '0', '', '\"\"', '\"\"', '', '3', '1', '1', '1', '192.168.1.1', '100000000'),
( 'program-logs-interface-route', 'interface_id', '接口ID', '80', '', '', 'left', '0', '1', '0', '', '\"\"', '\"\"', '', '4', '1', '1', '1', '192.168.1.1', '100000000'),
( 'program-logs-interface-route', 'system_alias', '所属系统', '120', '', '', 'left', '0', '1', '0', '', '\"\"', '\"\"', '', '5', '1', '1', '1', '192.168.1.1', '100000000'),
( 'program-logs-interface-route', 'type', '接口分类', '120', '', '', 'left', '0', '1', '0', '', '\"\"', '\"\"', '', '6', '0', '1', '1', '192.168.1.1', '100000000'),
( 'program-logs-interface-route', 'name', '接口名称', '120', '', '', 'left', '0', '1', '0', '', '\"\"', '\"\"', '', '7', '0', '1', '1', '192.168.1.1', '100000000'),
( 'program-logs-interface-route', 'is_operate', '是否操作', '60', '', '', '', '1', '0', '0', '', '[\"否\", \"是\"]', '\"\"', '', '8', '0', '1', '1', '192.168.1.1', '100000000'),
( 'program-logs-interface-route', 'is_success', '是否成功', '60', '', '', '', '1', '0', '0', '', '[\"否\", \"是\"]', '\"\"', '', '9', '0', '1', '1', '192.168.1.1', '100000000'),
( 'program-logs-interface-route', 'use_time', '接口耗时', '60', '', '', '', '1', '0', '0', '', '\"\"', '\"\"', '', '10', '0', '1', '1', '192.168.1.1', '100000000'),
( 'program-logs-interface-route', 'trace_id', '链路ID', '280', '', '', '', '1', '0', '0', '', '\"\"', '\"\"', '', '11', '0', '1', '1', '192.168.1.1', '100000000'),
( 'program-logs-interface-route', 'keyword', '关键字', '100', '', '', 'left', '1', '1', '0', '', '\"\"', '\"\"', '', '12', '0', '1', '1', '192.168.1.1', '100000000'),
( 'program-logs-interface-route', 'method', '请求类型', '80', '', '', '', '1', '1', '0', '', '\"\"', '\"\"', '', '13', '0', '1', '1', '192.168.1.1', '100000000'),
( 'program-logs-interface-route', 'message', '操作消息', '120', '', '', 'left', '1', '1', '0', '', '\"\"', '\"\"', '', '14', '0', '1', '1', '192.168.1.1', '100000000'),
( 'program-logs-interface-route', 'ip', '操作IP', '120', '', '', 'left', '1', '1', '0', '', '\"\"', '\"\"', '', '15', '0', '0', '1', '192.168.1.1', '100000000'),
( 'program-logs-interface-route', 'uid', '操作UID', '100', '', '', 'left', '0', '1', '0', '', '\"\"', '\"\"', '', '16', '0', '0', '1', '192.168.1.1', '100000000'),
( 'program-logs-interface-route', 'created_at', '创建时间', '130', '', '', '', '0', '0', '0', '', '\"\"', '\"\"', '', '17', '1', '1', '1', '192.168.1.1', '100000000'),
( 'program-logs-interface-route', 'operate', '操作', '260', 'right', '', 'left', '0', '0', '0', 'operate', '\"\"', '[]', '', '18', '1', '1', '1', '192.168.1.1', '100000000');

insert into `pub_header_option`
( `key`, `field`, `label`, `width`, `fixed`, `default`, `align`, `is_tooltip`, `is_resizable`, `is_editable`, `component`, `options`, `params`, `description`, `sort_order`, `is_required`, `is_default`, `is_enable`, `operate_ip`, `operate_uid`)
values
( 'program-logs-interface-access', '_idx', '序号', '50', 'left', '', '', '0', '0', '0', '', '\"\"', '\"\"', '', '1', '0', '1', '1', '192.168.1.1', '100000000'),
( 'program-logs-interface-access', 'uri_path', '接口path', '120', 'left', '', 'left', '0', '1', '0', '', '\"\"', '\"\"', '', '2', '0', '1', '1', '192.168.1.1', '100000000'),
( 'program-logs-interface-access', 'id', 'ID', '50', '', '', 'left', '0', '1', '0', '', '\"\"', '\"\"', '', '3', '1', '1', '1', '192.168.1.1', '100000000'),
( 'program-logs-interface-access', 'interface_id', '接口ID', '80', '', '', 'left', '0', '1', '0', '', '\"\"', '\"\"', '', '4', '1', '1', '1', '192.168.1.1', '100000000'),
( 'program-logs-interface-access', 'system_alias', '所属系统', '120', '', '', 'left', '0', '1', '0', '', '\"\"', '\"\"', '', '5', '1', '1', '1', '192.168.1.1', '100000000'),
( 'program-logs-interface-access', 'type', '接口分类', '120', '', '', 'left', '0', '1', '0', '', '\"\"', '\"\"', '', '6', '0', '1', '1', '192.168.1.1', '100000000'),
( 'program-logs-interface-access', 'name', '接口名称', '120', '', '', 'left', '0', '1', '0', '', '\"\"', '\"\"', '', '7', '0', '1', '1', '192.168.1.1', '100000000'),
( 'program-logs-interface-access', 'is_operate', '是否操作', '60', '', '', '', '1', '0', '0', '', '[\"否\", \"是\"]', '\"\"', '', '8', '0', '1', '1', '192.168.1.1', '100000000'),
( 'program-logs-interface-access', 'is_success', '是否成功', '60', '', '', '', '1', '0', '0', '', '[\"否\", \"是\"]', '\"\"', '', '9', '0', '1', '1', '192.168.1.1', '100000000'),
( 'program-logs-interface-access', 'is_intercept', '是否中断', '60', '', '', '', '1', '0', '0', '', '[\"否\", \"是\"]', '\"\"', '', '10', '0', '1', '1', '192.168.1.1', '100000000'),
( 'program-logs-interface-access', 'response_code', '响应码', '60', '', '', '', '1', '0', '0', '', '\"\"', '\"\"', '', '11', '0', '1', '1', '192.168.1.1', '100000000'),
( 'program-logs-interface-access', 'response_time', '响应时间', '60', '', '', '', '1', '0', '0', '', '\"\"', '\"\"', '', '12', '0', '1', '1', '192.168.1.1', '100000000'),
( 'program-logs-interface-access', 'use_time', '接口耗时', '60', '', '', '', '1', '0', '0', '', '\"\"', '\"\"', '', '13', '0', '1', '1', '192.168.1.1', '100000000'),
( 'program-logs-interface-access', 'trace_id', '链路ID', '280', '', '', '', '1', '0', '0', '', '\"\"', '\"\"', '', '14', '0', '1', '1', '192.168.1.1', '100000000'),
( 'program-logs-interface-access', 'keyword', '关键字', '100', '', '', 'left', '1', '1', '0', '', '\"\"', '\"\"', '', '15', '0', '1', '1', '192.168.1.1', '100000000'),
( 'program-logs-interface-access', 'method', '请求类型', '80', '', '', '', '1', '1', '0', '', '\"\"', '\"\"', '', '16', '0', '1', '1', '192.168.1.1', '100000000'),
( 'program-logs-interface-access', 'ip', '操作IP', '120', '', '', 'left', '1', '1', '0', '', '\"\"', '\"\"', '', '17', '0', '0', '1', '192.168.1.1', '100000000'),
( 'program-logs-interface-access', 'uid', '操作UID', '100', '', '', 'left', '0', '1', '0', '', '\"\"', '\"\"', '', '18', '0', '0', '1', '192.168.1.1', '100000000'),
( 'program-logs-interface-access', 'created_at', '创建时间', '130', '', '', '', '0', '0', '0', '', '\"\"', '\"\"', '', '19', '1', '1', '1', '192.168.1.1', '100000000'),
( 'program-logs-interface-access', 'operate', '操作', '260', 'right', '', 'left', '0', '0', '0', 'operate', '\"\"', '[]', '', '20', '1', '1', '1', '192.168.1.1', '100000000');