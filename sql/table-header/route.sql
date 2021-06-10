
-- ----------------------------
--  Data for header-key "program-route-type"
-- ----------------------------

-- pub_header
insert into `pub_header`
( `key`, `name`, `description`, `sort_order`, `is_open`)
values
( 'program-route-type', '程序员管理系统-路由类型管理', '程序员管理系统-路由类型管理', '122', '0');

-- pub_header_option
insert into `pub_header_option`
( `header_key`, `field`, `label`, `width`, `fixed`, `default`, `align`, `is_tooltip`, `is_resizable`, `is_editable`, `component`, `options`, `params`, `description`, `sort_order`, `is_required`, `is_default`, `is_enable`, `operate_ip`, `operate_uid`)
values
( 'program-route-type', '_idx', '序号', '50', 'left', '', '', '0', '1', '0', '', '\"\"', '\"\"', '', '1', '1', '1', '1', '192.168.1.1', '100000000'),
( 'program-route-type', 'id', 'ID', '50', 'left', '', 'left', '0', '1', '0', '', '\"\"', '\"\"', '', '2', '0', '0', '0', '192.168.1.1', '100000000'),
( 'program-route-type', 'route_type', '分类标识', '120', 'left', '', 'left', '0', '1', '0', '', '\"\"', '\"\"', '', '3', '1', '1', '1', '192.168.1.1', '100000000'),
( 'program-route-type', 'system_alias', '所属系统', '120', '', '', 'left', '0', '1', '0', '', '\"\"', '\"\"', '', '4', '1', '1', '1', '192.168.1.1', '100000000'),
( 'program-route-type', 'type_name', '分类名称', '150', '', '', 'left', '1', '0', '0', '', '\"\"', '\"\"', '', '5', '1', '1', '1', '192.168.1.1', '100000000'),
( 'program-route-type', 'sort_order', '排序', '60', '', '', '', '0', '0', '0', '', '\"\"', '\"\"', '', '6', '0', '1', '1', '192.168.1.1', '100000000'),
( 'program-route-type', 'operate', '操作', '', 'right', '', 'left', '0', '0', '0', 'operate', '\"\"', '[]', '', '7', '1', '1', '1', '192.168.1.1', '100000000');



-- ----------------------------
--  Data for header-key "program-route-record"
-- ----------------------------

-- pub_header
insert into `pub_header`
( `key`, `name`, `description`, `sort_order`, `is_open`)
values
( 'program-route-record', '程序员管理系统-路由管理', '程序员管理系统-路由管理', '123', '0');

-- pub_header_option
insert into `pub_header_option`
( `header_key`, `field`, `label`, `width`, `fixed`, `default`, `align`, `is_tooltip`, `is_resizable`, `is_editable`, `component`, `options`, `params`, `description`, `sort_order`, `is_required`, `is_default`, `is_enable`, `operate_ip`, `operate_uid`)
values
( 'program-route-record', '_idx', '序号', '50', 'left', '', '', '0', '0', '0', '', '\"\"', '\"\"', '', '1', '0', '1', '1', '192.168.1.1', '100000000'),
( 'program-route-record', 'id', 'ID', '50', 'left', '', 'left', '0', '1', '0', '', '\"\"', '\"\"', '', '2', '1', '1', '1', '192.168.1.1', '100000000'),
( 'program-route-record', 'route', 'URL路由', '200', 'left', '', 'left', '1', '0', '0', '', '\"\"', '\"\"', '', '3', '1', '1', '1', '192.168.1.1', '100000000'),
( 'program-route-record', 'system_alias', '所属系统', '120', '', '', 'left', '0', '1', '0', '', '\"\"', '\"\"', '', '4', '1', '1', '1', '192.168.1.1', '100000000'),
( 'program-route-record', 'type_name', '路由分类', '120', '', '', 'left', '0', '1', '0', '', '\"\"', '\"\"', '', '5', '1', '1', '1', '192.168.1.1', '100000000'),
( 'program-route-record', 'sort_order', '排序', '80', '', '', '', '0', '0', '0', '', '\"\"', '\"\"', '', '6', '0', '1', '1', '192.168.1.1', '100000000'),
( 'program-route-record', 'access_times', '访问次数', '60', '', '', '', '0', '0', '0', '', '\"\"', '\"\"', '', '7', '0', '1', '1', '192.168.1.1', '100000000'),
( 'program-route-record', 'created_at', '创建时间', '130', '', '', '', '0', '0', '0', '', '\"\"', '\"\"', '', '8', '0', '0', '0', '192.168.1.1', '100000000'),
( 'program-route-record', 'is_operate', '操作类', '80', '', '', '', '0', '0', '1', '', '\"\"', '{\"type\": \"switch\"}', '', '9', '1', '1', '1', '192.168.1.1', '100000000'),
( 'program-route-record', 'is_logging', '记录日志', '80', '', '', '', '0', '0', '1', '', '\"\"', '{\"type\": \"switch\"}', '', '10', '1', '1', '1', '192.168.1.1', '100000000'),
( 'program-route-record', 'updated_at', '更新时间', '130', '', '', '', '0', '0', '0', '', '\"\"', '\"\"', '', '11', '0', '0', '0', '192.168.1.1', '100000000'),
( 'program-route-record', 'key_fields', '参数关键字', '', '', '', '', '0', '0', '0', '', '\"\"', '\"\"', '', '12', '0', '0', '0', '192.168.1.1', '100000000'),
( 'program-route-record', 'message', '日志提示消息', '', '', '', '', '0', '0', '0', '', '\"\"', '\"\"', '', '13', '0', '0', '0', '192.168.1.1', '100000000'),
( 'program-route-record', 'operate', '操作', '260', 'right', '', 'left', '0', '0', '0', 'operate', '\"\"', '[]', '', '14', '1', '1', '1', '192.168.1.1', '100000000');



-- ----------------------------
--  Data for header-key "program-logs-route"
-- ----------------------------

-- pub_header
insert into `pub_header`
( `key`, `name`, `description`, `sort_order`, `is_open`)
values
( 'program-logs-route', '程序员管理系统-路由日志', '程序员管理系统-路由日志', '121', '0');

-- pub_header_option
insert into `pub_header_option`
( `header_key`, `field`, `label`, `width`, `fixed`, `default`, `align`, `is_tooltip`, `is_resizable`, `is_editable`, `component`, `options`, `params`, `description`, `sort_order`, `is_required`, `is_default`, `is_enable`, `operate_ip`, `operate_uid`)
values
( 'program-logs-route', '_idx', '序号', '50', 'left', '', '', '0', '0', '0', '', '\"\"', '\"\"', '', '1', '0', '1', '1', '192.168.1.1', '100000000'),
( 'program-logs-route', 'id', 'ID', '50', 'left', '', 'left', '0', '1', '0', '', '\"\"', '\"\"', '', '2', '0', '0', '0', '192.168.1.1', '100000000'),
( 'program-logs-route', 'route', 'URL路由', '150', 'left', '', 'left', '1', '0', '0', '', '\"\"', '\"\"', '', '3', '1', '1', '1', '192.168.1.1', '100000000'),
( 'program-logs-route', 'system_alias', '所属系统', '120', '', '', 'left', '0', '1', '0', '', '\"\"', '\"\"', '', '4', '1', '1', '1', '192.168.1.1', '100000000'),
( 'program-logs-route', 'route_type', '路由分类', '120', '', '', 'left', '0', '1', '0', '', '\"\"', '\"\"', '', '5', '1', '1', '1', '192.168.1.1', '100000000'),
( 'program-logs-route', 'trace_id', '链路ID', '150', '', '', '', '0', '0', '0', '', '\"\"', '\"\"', '', '6', '1', '1', '1', '192.168.1.1', '100000000'),
( 'program-logs-route', 'is_success', '操作类', '80', '', '', '', '0', '0', '0', '', '[\"失败\", \"成功\"]', '\"\"', '', '7', '1', '1', '1', '192.168.1.1', '100000000'),
( 'program-logs-route', 'keyword', '关键字', '120', '', '', 'left', '1', '1', '0', '', '\"\"', '\"\"', '', '8', '0', '1', '1', '192.168.1.1', '100000000'),
( 'program-logs-route', 'message', '操作消息', '120', '', '', 'left', '1', '1', '0', '', '\"\"', '\"\"', '', '9', '0', '1', '1', '192.168.1.1', '100000000'),
( 'program-logs-route', 'ip', '操作IP', '120', '', '', 'left', '0', '1', '0', '', '\"\"', '\"\"', '', '10', '0', '1', '1', '192.168.1.1', '100000000'),
( 'program-logs-route', 'uid', '操作UID', '120', '', '', 'left', '0', '1', '0', '', '\"\"', '\"\"', '', '11', '0', '1', '1', '192.168.1.1', '100000000'),
( 'program-logs-route', 'created_at', '创建时间', '130', '', '', '', '0', '0', '0', '', '\"\"', '\"\"', '', '12', '0', '0', '0', '192.168.1.1', '100000000'),
( 'program-logs-route', 'operate', '操作', '260', 'right', '', 'left', '0', '0', '0', 'operate', '\"\"', '[]', '', '13', '1', '1', '1', '192.168.1.1', '100000000');
