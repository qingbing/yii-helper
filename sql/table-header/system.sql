
-- ----------------------------
--  Data for header-key "program-system-manage"
-- ----------------------------

-- pub_header
insert into `pub_header`
( `key`, `name`, `description`, `sort_order`, `is_open`)
values
( 'program-system-manage', '程序员管理系统-系统管理', '程序员管理系统-系统管理', '119', '0');

-- pub_header_option
insert into `pub_header_option`
( `header_key`, `field`, `label`, `width`, `fixed`, `default`, `align`, `is_tooltip`, `is_resizable`, `is_editable`, `component`, `options`, `params`, `description`, `sort_order`, `is_required`, `is_default`, `is_enable`, `operate_ip`, `operate_uid`)
values
( 'program-system-manage', '_idx', '序号', '50', 'left', '', '', '0', '0', '0', '', '\"\"', '\"\"', '', '1', '0', '1', '1', '192.168.1.1', '100000000'),
( 'program-system-manage', 'id', 'ID', '60', '', '', 'left', '0', '0', '0', '', '\"\"', '\"\"', '', '2', '0', '0', '0', '192.168.1.1', '100000000'),
( 'program-system-manage', 'alias', '系统别名', '100', 'left', '', 'left', '0', '0', '0', '', '\"\"', '\"\"', '', '3', '1', '1', '1', '192.168.1.1', '100000000'),
( 'program-system-manage', 'name', '系统名称', '120', '', '', 'left', '0', '0', '0', '', '\"\"', '\"\"', '', '4', '1', '1', '1', '192.168.1.1', '100000000'),
( 'program-system-manage', 'uri_prefix', 'URI前缀', '150', '', '', 'left', '1', '0', '0', '', '\"\"', '\"\"', '', '5', '0', '1', '1', '192.168.1.1', '100000000'),
( 'program-system-manage', 'rule', '调用规则（备换select）', '100', '', '', 'left', '1', '0', '0', '', '\"\"', '\"\"', '', '6', '0', '0', '0', '192.168.1.1', '100000000'),
( 'program-system-manage', 'sort_order', '排序', '80', '', '', 'left', '0', '0', '0', '', '\"\"', '\"\"', '', '7', '1', '1', '1', '192.168.1.1', '100000000'),
( 'program-system-manage', 'is_enable', '启用', '80', '', '', '', '0', '0', '1', '', '\"\"', '{\"type\": \"switch\"}', '', '8', '1', '1', '1', '192.168.1.1', '100000000'),
( 'program-system-manage', 'is_continue', '未申明是否调用', '80', '', '', '', '0', '0', '1', '', '\"\"', '{\"type\": \"switch\"}', '', '9', '1', '1', '1', '192.168.1.1', '100000000'),
( 'program-system-manage', 'is_record_field', '是否记录新字段', '80', '', '', '', '0', '0', '1', '', '\"\"', '{\"type\": \"switch\"}', '', '10', '1', '1', '1', '192.168.1.1', '100000000'),
( 'program-system-manage', 'is_open_log', '开启接口日志', '80', '', '', '', '0', '0', '1', '', '\"\"', '{\"type\": \"switch\"}', '', '11', '1', '1', '1', '192.168.1.1', '100000000'),
( 'program-system-manage', 'updated_at', '更新时间', '130', '', '', '', '0', '0', '0', '', '\"\"', '\"\"', '', '12', '0', '0', '0', '192.168.1.1', '100000000'),
( 'program-system-manage', 'created_at', '创建时间', '130', '', '', '', '0', '0', '0', '', '\"\"', '\"\"', '', '13', '0', '0', '0', '192.168.1.1', '100000000'),
( 'program-system-manage', 'operate', '操作', '260', 'right', '', 'left', '0', '0', '0', 'operate', '\"\"', '[]', '', '14', '1', '1', '1', '192.168.1.1', '100000000');