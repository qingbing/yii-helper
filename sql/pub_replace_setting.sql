
-- ----------------------------
--  Table structure for `pub_replace_setting`
-- ----------------------------
CREATE TABLE IF NOT EXISTS `pub_replace_setting` (
  `code` varchar(100) NOT NULL COMMENT '替换配置标识符',
  `name` varchar(100) NOT NULL COMMENT '替换配置名称',
  `description` varchar(255) NOT NULL DEFAULT '' COMMENT '内容描述',
  `template` text DEFAULT NULL COMMENT '默认模板',
  `content` text DEFAULT NULL COMMENT '模板',
  `sort_order` tinyint(4) NOT NULL DEFAULT '0' COMMENT '排序',
  `is_open` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否开放，否时管理员不可操作（不可见）',
  `replace_fields` json COMMENT '替换字段集(JSON键值对),字段可以从模板中提取',
  PRIMARY KEY (`code`),
  UNIQUE KEY `uk_name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='网站中的内容配置';

insert into `pub_replace_setting` ( `code`, `name`, `description`, `template`, `content`, `sort_order`, `is_open`, `replace_fields`) values ( 'mail_active', '用户邮箱激活', '用户激活邮箱是发送的内容', '<div>\r\n    <p style=\"text-align:left;\">\r\n        尊敬的<span style=\"color:#096995;font-size:16px;font-weight:bold;\">{{login_nickname}}</span>：\r\n    </p>\r\n    <p style=\"text-indent:2em;text-align:left;\">\r\n        您好！\r\n    </p>\r\n    <p style=\"text-indent:2em;text-align:left;\">\r\n        感谢使用<a href=\"{{domain}}\" target=\"_blank\">{{site_name}}</a>，请点击下面的链接激活您的账户：\r\n    </p>\r\n    <p style=\"text-indent:2em;text-align:left;\">\r\n        <a href=\"{{active_link}}\" target=\"_blank\">{{active_link}}</a>\r\n    </p>\r\n    <p style=\"text-indent:2em;text-align:left;\">\r\n        以上链接有效期为{{expire_time}}，如果点击以上链接没有反应，请将该网址复制并粘贴到新的浏览器窗口中。\r\n    </p>\r\n    <p style=\"text-indent:2em;text-align:left;\">\r\n        如果您是误收到这封邮件，则可能是因为其他用户在尝试用您的邮箱进行用户注册，您可以进行如下操作：\r\n    </p>\r\n    <p style=\"text-indent:2em;text-align:left;\">\r\n        1、通过邮件修改在<a href=\"{{domain}}\" target=\"_blank\">{{site_name}}</a>中的密码，别人就无法再次登录，您可以继续用该邮箱账号在<a href=\"{{domain}}\" target=\"_blank\">{{site_name}}</a>中进行访问浏览。\r\n    </p>\r\n    <p style=\"text-indent:2em;text-align:left;\">\r\n        2、通过网站下方提供的邮箱地址联系我们，我们将尽快在网站中禁用该用户。\r\n    </p>\r\n    <p style=\"text-indent:2em;text-align:left;\">\r\n        最后，祝愿您的事业蒸蒸日上，一天更比一天好！\r\n    </p>\r\n    <p style=\"text-align:left;\">\r\n        此致\r\n    </p>\r\n    <p style=\"text-align:right;text-indent:2em;\">\r\n        {{company_name}}敬上\r\n    </p>\r\n    <p style=\"text-align:left;text-indent:2em;color:#f00;\">\r\n        温馨提示，该邮件为用户注册时系统自动发送，请勿回复。要了解您的账户或网站详情，请访问我们的网站：<a href=\"{{domain}}\" target=\"_blank\">{{site_name}}</a>\r\n    </p>\r\n</div>', null, '102', '1', '{\"domain\":\"网站域名\",\"active_link\":\"激活链接\",\"expire_time\":\"有效时间\"}');
insert into `pub_replace_setting` ( `code`, `name`, `description`, `template`, `content`, `sort_order`, `is_open`, `replace_fields`) values ( 'mail_findPassword', '找回密码内容', '用户通过邮箱找回密码的内容', '<div>\r\n	<p style=\"text-align:left;\">\r\n		尊敬的<span style=\"color:#096995;font-size:16px;font-weight:bold;\">{{nickname}}</span>：\r\n	</p>\r\n	<p style=\"text-indent:2em;text-align:left;\">\r\n		您好！\r\n	</p>\r\n	<p style=\"text-indent:2em;text-align:left;\">\r\n		感谢使用<a href=\"{{domain}}\" target=\"_blank\">{{site_name}}</a>，要重设您在<a href=\"{{domain}}\" target=\"_blank\">{{site_name}}</a>上的帐户{{email}}的密码，请点击以下链接重新设置：\r\n	</p>\r\n	<p style=\"text-indent:2em;text-align:left;\">\r\n		<a href=\"{{password_back_link}}\" target=\"_blank\">{{password_back_link}}</a> \r\n	</p>\r\n	<p style=\"text-indent:2em;text-align:left;\">\r\n		以上链接有效期为{{expire_time}}，如果点击以上链接没有反应，请将该网址复制并粘贴到新的浏览器窗口中。\r\n	</p>\r\n	<p style=\"text-indent:2em;text-align:left;\">\r\n		如果您是误收到这封邮件，则可能是因为其他用户在尝试用您的邮箱进行用户注册，您可以进行如下操作：\r\n	</p>\r\n	<p style=\"text-indent:2em;text-align:left;\">\r\n		1、通过邮件修改在<a href=\"{{domain}}\" target=\"_blank\">{{site_name}}</a>中的密码，别人就无法再次登录，您可以继续用该邮箱账号在<a href=\"{{domain}}\" target=\"_blank\">{{site_name}}</a>中进行访问浏览。\r\n	</p>\r\n	<p style=\"text-indent:2em;text-align:left;\">\r\n		2、通过网站下方提供的邮箱地址联系我们，我们将尽快在网站中禁用该用户。\r\n	</p>\r\n	<p style=\"text-indent:2em;text-align:left;\">\r\n		最后，祝愿您的事业蒸蒸日上，一天更比一天好！\r\n	</p>\r\n	<p style=\"text-align:left;\">\r\n		此致\r\n	</p>\r\n	<p style=\"text-align:right;text-indent:2em;\">\r\n		{{company_name}}敬上\r\n	</p>\r\n	<p style=\"text-align:left;text-indent:2em;color:#f00;\">\r\n		温馨提示，该邮件为用户注册时系统自动发送，请勿回复。要了解您的账户或网站详情，请访问我们的网站：<a href=\"{{domain}}\" target=\"_blank\">{{site_name}}</a> \r\n	</p>\r\n</div>', null, '103', '1', '{\"nickname\":\"用户昵称\",\"domain\":\"网站域名\",\"email\":\"邮箱账户\",\"password_back_link\":\"密码找回链接\",\"expire_time\":\"链接有效期\"}');
insert into `pub_replace_setting` ( `code`, `name`, `description`, `template`, `content`, `sort_order`, `is_open`, `replace_fields`) values ( 'mail_register', '用户注册通知', '用户注册时发送到用户邮箱的提示内容', '<div>\r\n	<p style=\"text-align:left;text-indent:0em;\">\r\n		尊敬的<span style=\"font-size:16px;color:#096995;font-weight:bold;\">{{nickname}}</span>：\r\n	</p>\r\n	<p style=\"text-indent:2em;text-align:left;\">\r\n		您好！\r\n	</p>\r\n	<p style=\"text-indent:2em;text-align:left;\">\r\n		您于{{now_time}}在<a href=\"{{domain}}\" target=\"_blank\">{{site_name}}</a>上已经成功注册，并成为了我们的注册用户。\r\n	</p>\r\n	<p style=\"text-indent:2em;text-align:left;\">\r\n		首先要感谢您对我们的支持，我们将竭诚为您服务。登录名为您的邮箱，该邮箱是您在<a href=\"{{domain}}\" target=\"_blank\">{{site_name}}</a>上的唯一标识，请妥善保管。\r\n	</p>\r\n	<p style=\"text-indent:2em;text-align:left;\">\r\n		如果是您自己的操作，建议您尽快激活您的账户，以便成为我们的激活用户。\r\n	</p>\r\n	<p style=\"text-indent:2em;text-align:left;\">\r\n		激活地址：<a href=\"{{active_link}}\" target=\"_blank\">{{active_link}}</a> \r\n	</p>\r\n	<p style=\"text-indent:2em;text-align:left;\">\r\n		以上链接有效期为{{expire_time}}，如果点击以上链接没有反应，请将该网址复制并粘贴到新的浏览器窗口中。\r\n	</p>\r\n	<p style=\"text-indent:2em;text-align:left;\">\r\n		如果您是误收到这封邮件，则可能是因为其他用户在尝试用您的邮箱进行用户注册，您可以进行如下操作：\r\n	</p>\r\n	<p style=\"text-indent:2em;text-align:left;\">\r\n		1、通过邮件修改在<a href=\"{{domain}}\" target=\"_blank\">{{site_name}}</a>中的密码，别人就无法再次登录，您可以继续用该邮箱账号在<a href=\"{{domain}}\" target=\"_blank\">{{site_name}}</a>中进行访问浏览。\r\n	</p>\r\n	<p style=\"text-indent:2em;text-align:left;\">\r\n		2、通过网站下方提供的邮箱地址联系我们，我们将尽快在网站中禁用该用户。\r\n	</p>\r\n	<p style=\"text-indent:2em;text-align:left;\">\r\n		最后，祝愿您的事业蒸蒸日上，一天更比一天好！\r\n	</p>\r\n	<p style=\"text-align:left;\">\r\n		此致\r\n	</p>\r\n	<p style=\"text-align:right;text-indent:2em;\">\r\n		{{company_name}}敬上\r\n	</p>\r\n	<p style=\"text-align:left;text-indent:2em;color:#f00;\">\r\n		温馨提示，该邮件为用户注册时系统自动发送，请勿回复。要了解您的账户或网站详情，请访问我们的网站：<a href=\"{{domain}}\" target=\"_blank\">{{site_name}}</a> \r\n	</p>\r\n</div>', null, '101', '1', '{\"nickname\":\"用户昵称\",\"domain\":\"网站域名\",\"active_link\":\"激活链接\"}');
insert into `pub_replace_setting` ( `code`, `name`, `description`, `template`, `content`, `sort_order`, `is_open`, `replace_fields`) values ( 'message_relationShipNotice', '管理员关联账户消息通知', '管理账户消息通知', '<p style=\"text-align:left;text-indent:0em;\">\r\n	尊敬的<span style=\"font-size:16px;color:#096995;font-weight:bold;\">{{nickname}}</span>：\r\n</p>\r\n<p style=\"text-indent:2em;text-align:left;\">\r\n	您好！\r\n</p>\r\n<p style=\"text-indent:2em;text-align:left;\">\r\n	后台管理员{{adminNickname}}正在请求和您建立关联关系！一旦建立关联，他将通过你的账号在后台发布一些内容！您是否同意建立关联关系？\r\n</p>\r\n<p style=\"text-indent:2em;text-align:left;\">\r\n	<a href=\"{{agree_link}}\" target=\"_blank\" class=\"btn btn-primary\" style=\"text-indent:initial\">同意</a> <a href=\"{{refuse_link}}\" target=\"_blank\" class=\"btn btn-warning\" style=\"text-indent:initial\">拒绝</a> \r\n</p>\r\n<p style=\"text-align:right;text-indent:2em;\">\r\n	该消息由系统后台发送\r\n</p>', '', '110', '1', '{\"nickname\":\"用户昵称\",\"adminNickname\":\"管理员昵称\",\"agree_link\":\"同意链接\",\"refuse_link\":\"拒绝链接\"}');
