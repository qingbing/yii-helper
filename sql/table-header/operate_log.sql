
-- ----------------------------
--  Data for header-key "program-logs-operate"
-- ----------------------------

-- pub_header
insert into `pub_header`
( `key`, `name`, `description`, `sort_order`, `is_open`)
values
( 'program-logs-operate', '程序员管理系统-操作日志', '程序员管理系统-操作日志', '120', '0');

-- pub_header_option
insert into `pub_header_option`
( `header_key`, `field`, `label`, `width`, `fixed`, `default`, `align`, `is_tooltip`, `is_resizable`, `is_editable`, `component`, `options`, `params`, `description`, `sort_order`, `is_required`, `is_default`, `is_enable`, `operate_ip`, `operate_uid`)
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

