
-- ----------------------------
--  Data for header-key "program-form-category"
-- ----------------------------

-- pub_header
insert into `pub_header`
( `key`, `name`, `description`, `sort_order`, `is_open`)
values
( 'program-form-category', '程序员管理系统-表单管理', '程序员管理系统-表单管理', '124', '0');

-- pub_header_option
insert into `pub_header_option`
( `header_key`, `field`, `label`, `width`, `fixed`, `default`, `align`, `is_tooltip`, `is_resizable`, `is_editable`, `component`, `options`, `params`, `description`, `sort_order`, `is_required`, `is_default`, `is_enable`, `operate_ip`, `operate_uid`)
values
( 'program-form-category', '_idx', '序号', '50', 'left', '', '', '0', '1', '0', '', '\"\"', '\"\"', '', '1', '1', '1', '1', '192.168.1.1', '100000000'),
( 'program-form-category', 'key', '表单标识', '240', 'left', '', 'left', '0', '1', '0', '', '\"\"', '\"\"', '', '2', '1', '1', '1', '192.168.1.1', '100000000'),
( 'program-form-category', 'name', '表单名称', '300', '', '', 'left', '1', '0', '0', '', '\"\"', '\"\"', '', '3', '1', '1', '1', '192.168.1.1', '100000000'),
( 'program-form-category', 'sort_order', '排序', '60', '', '', '', '0', '0', '0', '', '\"\"', '\"\"', '', '4', '0', '0', '1', '192.168.1.1', '100000000'),
( 'program-form-category', 'is_enable', '启用状态', '80', '', '', '', '0', '0', '0', '', '[\"禁用\", \"启用\"]', '\"\"', '', '5', '1', '1', '1', '192.168.1.1', '100000000'),
( 'program-form-category', 'is_open', '是否公开', '80', '', '', '', '0', '0', '0', '', '[\"否\", \"是\"]', '\"\"', '', '6', '1', '1', '1', '192.168.1.1', '100000000'),
( 'program-form-category', 'is_setting', '配置表单', '80', '', '', '', '0', '0', '0', '', '[\"否\", \"是\"]', '\"\"', '', '7', '1', '1', '1', '192.168.1.1', '100000000'),
( 'program-form-category', 'operate', '操作', '', 'right', '', 'left', '0', '0', '0', 'operate', '\"\"', '[]', '', '8', '1', '1', '1', '192.168.1.1', '100000000');


-- ----------------------------
--  Data for header-key "program-form-options"
-- ----------------------------

-- pub_header
insert into `pub_header`
( `key`, `name`, `description`, `sort_order`, `is_open`)
values
( 'program-form-options', '程序员管理系统-表单选项管理', '程序员管理系统-表单选项管理', '125', '0');

-- pub_header_option
insert into `pub_header_option`
( `header_key`, `field`, `label`, `width`, `fixed`, `default`, `align`, `is_tooltip`, `is_resizable`, `is_editable`, `component`, `options`, `params`, `description`, `sort_order`, `is_required`, `is_default`, `is_enable`, `operate_ip`, `operate_uid`)
values
( 'program-form-options', '_idx', '序号', '50', 'left', '', '', '0', '0', '0', '', '\"\"', '\"\"', '', '1', '0', '1', '1', '192.168.1.1', '100000000'),
( 'program-form-options', 'field', '字段', '100', 'left', '', 'left', '1', '0', '0', '', '\"\"', '\"\"', '', '2', '1', '1', '1', '192.168.1.1', '100000000'),
( 'program-form-options', 'label', '字段名', '150', '', '', 'left', '1', '0', '1', '', '\"\"', '{\"type\": \"text\"}', '', '3', '1', '1', '1', '192.168.1.1', '100000000'),
( 'program-form-options', 'input_type', '表单类型', '100', '', '', 'left', '0', '0', '0', '', '{\"ele-rate\": \"评分\", \"ele-color\": \"颜色\", \"view-text\": \"显示\", \"ele-slider\": \"滑块\", \"ele-switch\": \"开关按钮\", \"input-area\": \"文本域\", \"input-text\": \"文本框\", \"vue-editor\": \"富文本编辑\", \"date-picker\": \"日期组件\", \"input-radio\": \"单选组\", \"json-editor\": \"JSON编辑\", \"time-picker\": \"时间组件\", \"ele-cascader\": \"级联\", \"ele-uploader\": \"上传\", \"input-number\": \"文本数字\", \"input-select\": \"下拉框\", \"auto-complete\": \"建议输入\", \"input-checkbox\": \"复选组\", \"input-password\": \"密码框\"}', '[]', '', '4', '1', '1', '1', '192.168.1.1', '100000000'),
( 'program-form-options', 'sort_order', '排序', '80', '', '', '', '0', '0', '1', '', '\"\"', '{\"type\": \"text\"}', '', '5', '0', '1', '1', '192.168.1.1', '100000000'),
( 'program-form-options', 'default', '默认值', '60', '', '', '', '0', '0', '1', '', '\"\"', '{\"type\": \"text\"}', '', '6', '0', '1', '1', '192.168.1.1', '100000000'),
( 'program-form-options', 'is_required', '必填', '80', '', '', '', '0', '0', '1', '', '\"\"', '{\"type\": \"switch\"}', '', '7', '1', '1', '1', '192.168.1.1', '100000000'),
( 'program-form-options', 'is_enable', '是否开启', '80', '', '', '', '0', '0', '1', '', '\"\"', '{\"type\": \"switch\"}', '', '8', '1', '1', '1', '192.168.1.1', '100000000'),
( 'program-form-options', 'operate', '操作', '260', 'right', '', 'left', '0', '0', '0', 'operate', '\"\"', '[]', '', '9', '1', '1', '1', '192.168.1.1', '100000000');
