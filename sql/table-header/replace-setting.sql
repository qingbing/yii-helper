
-- ----------------------------
--  Data for header-key "program-replace-setting"
-- ----------------------------

-- pub_header
insert into `pub_header`
( `key`, `name`, `description`, `sort_order`, `is_open`)
values
( 'program-replace-setting', '程序员管理系统-替换模版管理', '程序员管理系统-替换模版管理', '118', '0');

-- pub_header_option
insert into `pub_header_option`
( `header_key`, `field`, `label`, `width`, `fixed`, `default`, `align`, `is_tooltip`, `is_resizable`, `is_editable`, `component`, `options`, `params`, `description`, `sort_order`, `is_required`, `is_default`, `is_enable`, `operate_ip`, `operate_uid`)
values
( 'program-replace-setting', '_idx', '序号', '50', 'left', '', '', '0', '0', '0', '', '\"\"', '\"\"', '', '1', '0', '1', '1', '192.168.1.1', '100000000'),
( 'program-replace-setting', 'code', '标识码', '150', 'left', '', 'left', '0', '0', '0', '', '\"\"', '\"\"', '', '2', '1', '1', '1', '192.168.1.1', '100000000'),
( 'program-replace-setting', 'name', '配置名称', '150', '', '', 'left', '0', '0', '0', '', '\"\"', '\"\"', '', '3', '1', '1', '1', '192.168.1.1', '100000000'),
( 'program-replace-setting', 'sort_order', '排序', '80', '', '', 'left', '0', '0', '0', '', '\"\"', '\"\"', '', '4', '1', '1', '1', '192.168.1.1', '100000000'),
( 'program-replace-setting', 'is_open', '是否开放', '80', '', '', '', '0', '0', '1', '', '\"\"', '{\"type\": \"switch\"}', '', '5', '1', '1', '1', '192.168.1.1', '100000000'),
( 'program-replace-setting', 'operate', '操作', '', 'right', '', 'left', '0', '0', '0', 'operate', '\"\"', '[]', '', '6', '1', '1', '1', '192.168.1.1', '100000000');
