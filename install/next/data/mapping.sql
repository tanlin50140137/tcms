 drop table if exists %103%login;
#777#
create table %103%login(
id int(10) unsigned not null auto_increment primary key comment '主键',
pid int(10) unsigned not null default 0 comment '二级用户',
pic varchar(255) not null default '' comment '头像',
userName char(20) unique not null default '' comment '用户名',
alias varchar(255) not null default '' comment '别名',
pwd char(32) not null default '' comment '密码',
email char(32) not null default '' comment '使用者邮箱',
tel char(12) not null default '' comment '手机',
loginIP char(15) not null default '' comment '本次登录IP',
loginTime int(10) not null default 0 comment '登录时间',
homepage varchar(255) not null default '' comment '主页',
abst varchar(255) not null default '' comment '摘要',
Template varchar(255) not null default '' comment '模板',
level tinyint(10) unsigned not null default 0 comment '用户级别',
state tinyint(10) unsigned not null default 0 comment '状态,正常＝0、审核=1、禁止=2',
fjrows int(10) unsigned not null default 0 comment '附件数',
rcprows int(10) unsigned not null default 0 comment '评论数',
pagerows int(10) unsigned not null default 0 comment '页面数',
artrows int(10) unsigned not null default 0 comment '文章数',
flag tinyint(10) unsigned not null default 0 comment '0=本站用户,1=购买模板用户',
key tel_key(tel),
key userName_key(userName),
key email_key(email),
key pwd_key(pwd)
)ENGINE=%104% DEFAULT CHARSET='%102%';
#777#
insert into %103%login(userName,pwd,email,loginIP,loginTime) values('%user%','%pwd%','','%ip%',%t%);
#777#
drop table if exists %103%article;
#777#
create table %103%article(
id int(10) unsigned not null auto_increment primary key comment '主键',
rcpid int(10) unsigned not null default 0 comment '关联评论',
author char(20) not null default '' comment '作者=用户名',
publitime int(11) unsigned not null default 0 comment '发布时间',
timerel int(11) unsigned not null default 0 comment '定时发布',
cipid tinyint(10) unsigned not null default 0 comment '关联各种文章分类',
columnid tinyint(10) unsigned not null default 0 comment '栏目ID，文章对应栏目',
state tinyint(10) unsigned not null default 0 comment '状态,0=公开,1=草稿,2=审核',
top tinyint(10) unsigned not null default 0 comment 'top,首页＝101、全局=102、分类=103',
nocomment enum('OFF','ON') not null default 'ON' comment '禁止评论,OFF=开启、ON=禁止评论',
templateid int(10) unsigned not null default 0 comment '模板id',
stage varchar(255) not null default '' comment '期数',
title varchar(255) not null default '' comment '标题',
alias varchar(255) not null default '' comment '别名',
keywords varchar(255) not null default '' comment '标签',
description text not null comment '摘要',
body MediumText not null comment '内容,大文本',
poslink varchar(255) not null default '' comment '静态化名称',
posflag tinyint(10) unsigned not null default 0 comment '0=动态，1=静态',
imgurl text not null comment '保存原图片路径',
Thumurl text not null comment '保存缩略图路径',
cover varchar(255) not null default '' comment '封面',
browse int(10) unsigned not null default 0 comment '浏览次数',
price varchar(255) not null default '' comment '售价',
orprice varchar(255) not null default '' comment '原价',
Sales varchar(255) not null default '' comment '销量',
chain varchar(255) not null default '' comment '使用外部链接',
sizetype tinyint(10) unsigned not null default 0 comment '类型',
flag tinyint(10) unsigned not null default 0 comment '标识，1=内容文章(投稿),2=外部文章(手记)',
key key_sizetype(sizetype),
key key_stage(stage),
key key_price(price),
key key_author(author),
key key_state(state),
key key_columnid(columnid),
key key_rcpid(rcpid),
key key_publitime(publitime),
key key_timerel(timerel),
key key_cipid(cipid),
key key_top(top),
key key_templateid(templateid),
key key_flag(flag),
key key_title(title)
)ENGINE=%104% DEFAULT CHARSET='%102%';
#777#
drop table if exists %103%classified;
#777#
create table %103%classified(
id int(10) unsigned not null auto_increment primary key comment '主键',
pid int(10) unsigned not null default 0 comment '子级分类',
classified varchar(255) not null default '' comment '分类名称',
clalias varchar(255) not null default '' comment '分类别称',
sort tinyint(10) unsigned not null default 0 comment '排序',
description varchar(255) not null default '' comment '摘要',
addmenu enum('OFF','ON') not null comment 'OFF=加入导行菜单、ON=禁止加入',
artrows int(10) unsigned not null default 0 comment '文章数',
templateid int(10) unsigned not null default 0 comment '模板id',
key key_classified(classified),
key key_templateid(templateid),
key key_pid(pid)
)ENGINE=%104% DEFAULT CHARSET='%102%';
#777#
drop table if exists %103%tag;
#777#
create table %103%tag(
id int(10) unsigned not null auto_increment primary key comment '主键',
keywords varchar(255) not null default '' comment '标签',
tagas varchar(255) not null default '' comment '标签别名',
templateid int(10) unsigned not null default 0 comment '模板id',
description varchar(255) not null default '' comment '摘要',
addmenu enum('OFF','ON') not null comment 'OFF=加入导行菜单、ON=禁止加入',
artrows int(10) unsigned not null default 0 comment '文章数',
key key_keywords(keywords),
key key_templateid(templateid)
)ENGINE=%104% DEFAULT CHARSET='%102%';
#777#
drop table if exists %103%template;
#777#
create table %103%template(
id int(10) unsigned not null auto_increment primary key comment '主键',
pid int(10) unsigned not null default 0 comment '下级分类',
userid int(10) unsigned not null default 0 comment 'userid',
name varchar(255) not null default '' comment '栏目名称',
module varchar(255) not null default '' comment '页面名称',
cover varchar(255) not null default '' comment '封面',
keywords varchar(255) not null default '' comment '关键字',
description varchar(255) not null default '' comment '摘要',
addmenu enum('OFF','ON') not null default 'OFF' comment 'OFF=加入导行菜单、ON=禁止加入',
forbidden enum('ON','OFF') not null default 'ON' comment 'OFF=禁止发布编辑、ON=可以发布',
artrows int(10) unsigned not null default 0 comment '文章数',
state tinyint(10) unsigned not null default 0 comment 'state,动态＝0、静态=1',
templateid int(10) unsigned not null default 0 comment '模板id',
sort int(10) unsigned not null default 0 comment '栏目排序',
key key_sort(sort),
key key_pid(pid),
key key_module(module),
key key_userid(userid),
key key_templateid(templateid)
)ENGINE=%104% DEFAULT CHARSET='%102%';
#777#
drop table if exists %103%setting;
#777#
create table %103%setting(
id int(10) unsigned not null auto_increment primary key comment '主键',
link varchar(255) not null default '' comment '网站地址',
title varchar(255) not null default '' comment '网站标题',
alias varchar(255) not null default '' comment '网站副标题',
keywords varchar(255) not null default '' comment '关键字',
description varchar(255) not null default '' comment '网站描述语',
copyright varchar(255) not null default '' comment '版权说明',
addmenu enum('OFF','ON') not null comment 'OFF=启用、ON=禁止',
key key_link(link),
key key_title(title),
key key_alias(alias)
)ENGINE=%104% DEFAULT CHARSET='%102%';
#777#
INSERT INTO `%103%setting` VALUES ('1', '%http%', '%title%', 'Good Luck To You!', '整站开发,设计,空间,域名,SEO,网站统计,备案', '描述网页或文章内容，有利于SEO搜引擎搜录', 'Copyright Your WebSite.Some Rights Reserved.', 'ON');
#777#
drop table if exists %103%theme;
#777#
create table %103%theme(
id int(10) unsigned not null auto_increment primary key comment '主键',
author varchar(255) not null default '' comment '作者',
homepage varchar(255) not null default '' comment '作者主页',
themename varchar(255) unique not null default '' comment '主题名称ID',
themeas varchar(255) not null default '' comment '主题别名',
style varchar(255) not null default '' comment '主题样式',
price varchar(255) not null default '' comment '主题定价',
description varchar(255) not null default '' comment '摘要',
addmenu enum('OFF','ON') not null default 'ON' comment 'OFF=启用、ON=禁止',
autoplug1 varchar(255) not null default '' comment '后台插件名称',
autoplug2 varchar(255) not null default '' comment '页面插件名称',
artrows int(10) unsigned not null default 0 comment '点击数',
themeimg text not null comment '主题图片信息',
publitime int(11) unsigned not null default 0 comment '发布时间',
sort tinyint(10) unsigned not null default 0 comment '排序',
xmlrpc varchar(255) not null default '' comment 'XML路径',
flag tinyint(10) unsigned not null default 0 comment '标识，0=主题，1=插件',
key key_author(author),
key key_themeas(themeas),
key key_style(style),
key key_autoplug1(autoplug1),
key key_autoplug2(autoplug2)
)ENGINE=%104% DEFAULT CHARSET='%102%';
#777#
INSERT INTO `%103%theme` VALUES ('2', 'TanLin', '#', 'comment', '文章评论插件', 'subject/plugin/comment/css', '0', '文章内容相关评论，拥有它，游客可以畅所欲言；相关设置安装插件后进后管理操作！', 'OFF', '', 'include', '0', 'subject/plugin/comment/logo/20170423203253.png', '1492950773', '0', 'subject/plugin/comment/xml-rpc.xml', '1');
#777#
INSERT INTO `%103%theme` VALUES ('3', 'TanLin', '#', 'static', '静态管理中心', '/subject/plugin/static/css', '0', '首页、列表页、文章内容页面，静态化，搜索引擎高效收录必备利器。', 'OFF', '', 'include', '0', '/subject/plugin/static/logo/20170423202033.jpg', '1492950033', '0', '/subject/plugin/static/xml-rpc.xml', '1');
#777#
INSERT INTO `%103%theme` VALUES ('4', 'TanLin', '#', 'databackup', '数据备份插件', 'subject/plugin/databackup/css', '0', '数据库备份、还原插件', 'OFF', '', 'include', '0', 'subject/plugin/databackup/logo/20170521193225.jpg', '1495366344', '0', 'subject/plugin/databackup/xml-rpc.xml', '1');
#777#
INSERT INTO `%103%theme` VALUES ('5', 'TanLin', '#', 'share', '内容分享插件', 'subject/plugin/share/css', '0', '分享链接，分享文章，分享图片', 'OFF', '', 'include', '0', 'subject/plugin/share/logo/20170522213118.png', '1495459878', '0', 'subject/plugin/share/xml-rpc.xml', '1');
#777#
drop table if exists %103%module;
#777#
create table %103%module(
id int(10) unsigned not null auto_increment primary key comment '主键',
modulename varchar(255) not null default '' comment '模块名称ID',
filename varchar(255) unique not null default '' comment '文件名称',
htmlid varchar(255) not null default '' comment 'HTML ID',
divorul varchar(255) not null default '' comment '类型',
body text not null comment '正文',
hiddenmenu enum('OFF','ON') not null default 'ON' comment '隐藏标题,OFF=隐藏、ON=不隐藏',
updatepro enum('OFF','ON') not null default 'ON' comment '禁止系统更新模块内容,OFF=禁止更新、ON=不禁止更新',
sort tinyint(10) unsigned not null default 0 comment '排序',
templateid int(10) unsigned not null default 0 comment '模板id',
flag tinyint(10) unsigned not null default 0 comment '标识,系统模块=0,自定义,新键＝1,',
key key_modulename(modulename),
key key_htmlid(htmlid),
key key_sort(sort),
key key_flag(flag)
)ENGINE=%104% DEFAULT CHARSET='%102%';
#777#
INSERT INTO `%103%module` VALUES ('1', '导航栏', 'Muen', 'MuenHtmlId', '2', '', 'ON', 'ON', '0', '0', '1');
#777#
INSERT INTO `%103%module` VALUES ('2', '友情链接', 'Link', 'LinkHtmlId', '2', '<li><a href=\"#\" target=\"_blank\">This_cms_system</a></li>', 'ON', 'ON', '0', '0', '1');
#777#
INSERT INTO `%103%module` VALUES ('3', '网站收藏', 'Website', 'WebsiteHtmlId', '2', '<li><a href=\"#\" target=\"_blank\">This_cms_system</a></li>', 'ON', 'ON', '0', '0', '1');
#777#
INSERT INTO `%103%module` VALUES ('4', '图标汇集', 'Misc', 'MiscHtmlId', '2', '<li><a href=\"#\"><img src=\"#\" alt=\"图标\"/></a></li>', 'ON', 'ON', '0', '0', '1');
#777#
INSERT INTO `%103%module` VALUES ('5', '网站分类', 'Categories', 'CategoriesHtmlId', '2', '', 'ON', 'ON', '0', '0', '4');
#777#
INSERT INTO `%103%module` VALUES ('6', '文章归档', 'Archive', 'ArchiveHtmlId', '2', '', 'ON', 'ON', '0', '0', '4');
#777#
INSERT INTO `%103%module` VALUES ('7', '作者列表', 'AuthorList', 'AuthorListHtmlId', '2', '', 'ON', 'ON', '0', '0', '4');
#777#
INSERT INTO `%103%module` VALUES ('8', '最近发表', 'Published', 'PublishedHtmlId', '2', '', 'ON', 'ON', '0', '0', '4');
#777#
INSERT INTO `%103%module` VALUES ('9', '标签列表', 'TagList', 'TagListHtmlId', '2', '', 'ON', 'ON', '0', '0', '4');
#777#
INSERT INTO `%103%module` VALUES ('10', '搜索', 'Search', 'SearchHtmlId', '1', '', 'OFF', 'ON', '0', '0', '4');
#777#
INSERT INTO `%103%module` VALUES ('11', '控制面板', 'Controlpanel', 'ControlpanelHtmlId', '1', '', 'ON', 'ON', '0', '0', '4');
#777#
INSERT INTO `%103%module` VALUES ('12', '站点信息', 'Siteinformation', 'SiteinformationHtmlId', '2', '', 'ON', 'ON', '0', '0', '4');
#777#
INSERT INTO `%103%module` VALUES ('13', '最新评论', 'Comments', 'CommentsHtmlId', '2', '', 'ON', 'ON', '0', '0', '4');
#777#
INSERT INTO `%103%module` VALUES ('14', '日历', 'Calendar', 'CalendarHtmlId', '1', '', 'OFF', 'ON', '0', '0', '4');
#777#
INSERT INTO `%103%module` VALUES ('15', '热门推荐', 'Hot', 'HotHTMLId', '2', '', 'OFF', 'ON', '0', '0', '4');
#777#
drop table if exists %103%storage;
#777#
create table %103%storage(
id int(10) unsigned not null auto_increment primary key comment '主键',
name varchar(255) unique not null default '' comment '名称',
body MediumText not null comment '存储正文',
flags varchar(255) not null default '' comment '标段名',
flag tinyint(10) unsigned not null default 0 comment '标识',
key key_flag(flag)
)ENGINE=%104% DEFAULT CHARSET='%102%';
#777#
INSERT INTO `%103%storage` VALUES ('1', 'sidebar', '<div class=\"widget widget_source_system widget_id_navbar ui-draggable ui-draggable-handle\" style=\"display: block;\">\r\n				<div class=\"widget-title\">\r\n				<img class=\"more-action\" width=\"16\" src=\"http://127.0.0.1/ThisCMSSystem/system/admin/images/brick.png\" alt=\"\">\r\n				日历\r\n				<span class=\"widget-action\">\r\n				<a href=\"index.php?act=ModuleEdtUp&amp;id=14\">\r\n				<img class=\"edit-action\" src=\"http://127.0.0.1/ThisCMSSystem/system/admin/images/brick_edit.png\" alt=\"编辑\" title=\"编辑\" width=\"16\">\r\n				</a>\r\n				</span>\r\n				</div>\r\n				<div class=\"funid\" style=\"display:none\">Calendar</div>\r\n				</div><div class=\"widget widget_source_system widget_id_navbar ui-draggable ui-draggable-handle\" style=\"display: block;\">\r\n				<div class=\"widget-title\">\r\n				<img class=\"more-action\" width=\"16\" src=\"http://127.0.0.1/ThisCMSSystem/system/admin/images/brick.png\" alt=\"\">\r\n				搜索\r\n				<span class=\"widget-action\">\r\n				<a href=\"index.php?act=ModuleEdtUp&amp;id=10\">\r\n				<img class=\"edit-action\" src=\"http://127.0.0.1/ThisCMSSystem/system/admin/images/brick_edit.png\" alt=\"编辑\" title=\"编辑\" width=\"16\">\r\n				</a>\r\n				</span>\r\n				</div>\r\n				<div class=\"funid\" style=\"display:none\">Search</div>\r\n				</div><div class=\"widget widget_source_system widget_id_navbar ui-draggable ui-draggable-handle\" style=\"display: block;\">\r\n				<div class=\"widget-title\">\r\n				<img class=\"more-action\" width=\"16\" src=\"http://127.0.0.1/ThisCMSSystem/system/admin/images/brick.png\" alt=\"\">\r\n				控制面板\r\n				<span class=\"widget-action\">\r\n				<a href=\"index.php?act=ModuleEdtUp&amp;id=11\">\r\n				<img class=\"edit-action\" src=\"http://127.0.0.1/ThisCMSSystem/system/admin/images/brick_edit.png\" alt=\"编辑\" title=\"编辑\" width=\"16\">\r\n				</a>\r\n				</span>\r\n				</div>\r\n				<div class=\"funid\" style=\"display:none\">Controlpanel</div>\r\n				</div><div class=\"widget widget_source_system widget_id_navbar ui-draggable ui-draggable-handle\" style=\"display: block;\">\r\n				<div class=\"widget-title\">\r\n				<img class=\"more-action\" width=\"16\" src=\"http://127.0.0.1/ThisCMSSystem/system/admin/images/brick.png\" alt=\"\">\r\n				图标汇集\r\n				<span class=\"widget-action\">\r\n				<a href=\"index.php?act=ModuleEdtUp&amp;id=4\">\r\n				<img class=\"edit-action\" src=\"http://127.0.0.1/ThisCMSSystem/system/admin/images/brick_edit.png\" alt=\"编辑\" title=\"编辑\" width=\"16\">\r\n				</a>\r\n				</span>\r\n				</div>\r\n				<div class=\"funid\" style=\"display:none\">Misc</div>\r\n				</div><div class=\"widget widget_source_system widget_id_navbar ui-draggable ui-draggable-handle\" style=\"display: block;\">\r\n				<div class=\"widget-title\">\r\n				<img class=\"more-action\" width=\"16\" src=\"http://127.0.0.1/ThisCMSSystem/system/admin/images/brick.png\" alt=\"\">\r\n				网站收藏\r\n				<span class=\"widget-action\">\r\n				<a href=\"index.php?act=ModuleEdtUp&amp;id=3\">\r\n				<img class=\"edit-action\" src=\"http://127.0.0.1/ThisCMSSystem/system/admin/images/brick_edit.png\" alt=\"编辑\" title=\"编辑\" width=\"16\">\r\n				</a>\r\n				</span>\r\n				</div>\r\n				<div class=\"funid\" style=\"display:none\">Website</div>\r\n				</div><div class=\"widget widget_source_system widget_id_navbar ui-draggable ui-draggable-handle\" style=\"display: block;\">\r\n				<div class=\"widget-title\">\r\n				<img class=\"more-action\" width=\"16\" src=\"http://127.0.0.1/ThisCMSSystem/system/admin/images/brick.png\" alt=\"\">\r\n				友情链接\r\n				<span class=\"widget-action\">\r\n				<a href=\"index.php?act=ModuleEdtUp&amp;id=2\">\r\n				<img class=\"edit-action\" src=\"http://127.0.0.1/ThisCMSSystem/system/admin/images/brick_edit.png\" alt=\"编辑\" title=\"编辑\" width=\"16\">\r\n				</a>\r\n				</span>\r\n				</div>\r\n				<div class=\"funid\" style=\"display:none\">Link</div>\r\n				</div>', 'Calendar|Search|Controlpanel|Misc|Website|Link|', '6');
#777#
INSERT INTO `%103%storage` VALUES ('2', 'sidebar2', '', '', '0');
#777#
INSERT INTO `%103%storage` VALUES ('3', 'sidebar3', '', '', '0');
#777#
INSERT INTO `%103%storage` VALUES ('4', 'sidebar4', '', '', '0');
#777#
INSERT INTO `%103%storage` VALUES ('5', 'sidebar5', '', '', '0');
#777#
drop table if exists %103%review;
#777#
create table %103%review(
id int(10) unsigned not null auto_increment primary key comment '主键',
pid int(10) unsigned not null default 0 comment '回复',
likes int(10) unsigned not null default 0 comment '点赞',
report int(10) unsigned not null default 0 comment '举报',
name varchar(255) not null default '' comment '名称',
tal varchar(255) not null default '' comment '手机',
email varchar(255) not null default '' comment '邮箱',
qq varchar(255) not null default '' comment 'QQ号',
body text not null comment '正文',
pic text not null comment '评论头像',
publitime int(11) unsigned not null default 0 comment '评论时间',
visitorip varchar(255) not null default '' comment '游客IP',
stopped tinyint(10) unsigned not null default 0 comment 'tackled,不拦截IP＝0、拦截IP=1',
templateid int(10) unsigned not null default 0 comment '模板id',
titleid int(10) unsigned not null default 0 comment '评论文章 ID',
vifiy enum('OFF','ON') not null default 'ON' comment 'OFF=开启验证码、ON=不使用验证码',
filter enum('OFF','ON') not null default 'ON' comment 'OFF=开启过滤器、ON=不使用过滤器',
state tinyint(10) unsigned not null default 0 comment 'state,不用审核＝0、审核=1',
flag tinyint(10) unsigned not null default 0 comment 'tackled,文章评论＝0、模板评论=1',
key key_visitorip(visitorip),
key key_name(name),
key key_tal(tal),
key key_email(email),
key key_qq(qq)
)ENGINE=%104% DEFAULT CHARSET='%102%';
#777#
drop table if exists %103%review_up;
#777#
CREATE TABLE `%103%review_up` (
  `id` int(10) unsigned NOT NULL auto_increment ,
  `vifiy` enum('OFF','ON') NOT NULL default 'ON' ,
  `filter` enum('OFF','ON') NOT NULL default 'ON' ,
  `blacklist` text NOT NULL ,
  `sensitivelist` text NOT NULL ,
  `ipfilterlist` text NOT NULL ,
  `stopped` enum('OFF','ON') NOT NULL default 'ON' ,
  `colsecomment` enum('OFF','ON') NOT NULL default 'OFF' ,
  `moderation` enum('OFF','ON') NOT NULL default 'ON' ,
  `malicious` enum('OFF','ON') NOT NULL default 'ON' ,
  `sort` enum('OFF','ON') NOT NULL default 'ON' ,
  `listtotal` tinyint(10) unsigned NOT NULL default '0' ,
  `rowstotal` tinyint(10) unsigned NOT NULL default '0' ,
  `talbox` tinyint(10) unsigned NOT NULL default '0' ,
  `qqbox` tinyint(10) unsigned NOT NULL default '0' ,
  `emailbox` tinyint(10) unsigned NOT NULL default '0' ,
  `searchmaxtotal` tinyint(10) unsigned NOT NULL default '0' ,
  `sitetimezone` varchar(255) NOT NULL default '' ,
  `weblanguage` varchar(255) NOT NULL default '' ,
  `filestyle` varchar(255) NOT NULL default '' ,
  `updatemaxsize` tinyint(10) unsigned NOT NULL default '0' ,
  `pagecache` enum('OFF','ON') NOT NULL default 'ON' ,
  `closesite` enum('OFF','ON') NOT NULL default 'OFF' ,
  `development` enum('OFF','ON') NOT NULL default 'ON' ,
  `pagcache` enum('OFF','ON') NOT NULL default 'ON' ,
  `setTime` varchar(255) NOT NULL default '',
  `datacache` enum('OFF','ON') NOT NULL default 'ON' ,
  `tbheight` tinyint(10) unsigned NOT NULL default '0' ,
  `tbwidth` tinyint(10) unsigned NOT NULL default '0',
  `thumbnail` enum('OFF','ON') NOT NULL default 'ON',
  PRIMARY KEY  (`id`)
) ENGINE=%104% AUTO_INCREMENT=2 DEFAULT CHARSET=%102%;
#777#
INSERT INTO `%103%review_up` VALUES ('1', 'ON', 'OFF', '(推广|群发|广告|解密|赌博|包青天|广告|阿凡提|发贴|顶贴|(针孔|隐形|隐蔽式)摄像|干扰|顶帖|发帖|消声|遥控|解码|窃听|身份证生成|拦截|复制|监听|定位|消声|作弊|扩散|侦探|追杀)(机|器|软件|设备|系统)|(求|换|有偿|买|卖|出售)(肾|器官|眼角膜|血)|肾源|(假|毕业)(证|文凭|发票|币)|(手榴|人|麻醉|霰)弹|治疗(肿瘤|乙肝|性病|红斑狼疮)|重亚硒酸钠|(粘氯|原砷)酸|麻醉乙醚|原藜芦碱A|永伏虫|蝇毒|罂粟|银氰化钾|氯胺酮|因毒(硫磷|磷)|异氰酸(甲酯|苯酯)|异硫氰酸烯丙酯|乙酰(亚砷酸铜|替硫脲)|乙烯甲醇|乙酸(亚铊|铊|三乙基锡|三甲基锡|甲氧基乙基汞|汞)|乙硼烷|乙醇腈|乙撑亚胺|乙撑氯醇|伊皮恩|海洛因|一氧(化汞|化二氟)|一氯(乙醛|丙酮)|氧氯化磷|氧化(亚铊|铊|汞|二丁基锡)|烟碱|亚硝酰乙氧|亚硝酸乙酯|亚硒酸氢钠|亚硒酸钠|亚硒酸镁|亚硒酸二钠|亚硒酸|亚砷酸(钠|钾|酐)|冰毒|预测答案|考前预测|押题|代写论文|(提供|司考|级|传送|考中|短信)答案|(待|代|带|替|助)考|(包|顺利|保)过|考后付款|无线耳机|考试作弊|考前密卷|漏题|中特|一肖|报码|(合|香港)彩|彩宝|3D轮盘|liuhecai|一码|(皇家|俄罗斯)轮盘|赌具|特码|盗(号|qq|密码)|盗取(密码|qq)|嗑药|帮招人|社会混|拜大哥|电警棒|帮人怀孕|征兵计划|切腹|VE视觉|电鸡|仿真手枪|做炸弹|走私|陪聊|h(图|漫|网)|开苞|找(男|女)|口淫|卖身|元一夜|(男|女)奴|双(筒|桶)|看JJ|做台|厕奴|骚女|嫩逼|一夜激情|乱伦|泡友|富(姐|婆)|(足|群|茹)交|阴户|性(服务|伴侣|伙伴|交)|有偿(捐献|服务)|(有|无)码|包养|(犬|兽|幼)交|根浴|援交|小口径|性(虐|爱|息)|刻章|摇头丸|监听王|昏药|侦探设备|性奴|透视眼(睛|镜)|拍肩神|(失忆|催情|迷(幻|昏|奸)?|安定)(药|片|香)|游戏机破解|隐形耳机|银行卡复制设备|一卡多号|信用卡套现|消防[灭火]?枪|香港生子|土炮|胎盘|手机魔卡|容弹量|枪模|铅弹|汽(枪|狗|走表器)|气枪|气狗|伟哥|纽扣摄像机|免电灯|卖QQ号码|麻醉药|康生丹|警徽|记号扑克|激光(汽|气)|红床|狗友|反雷达测速|短信投票业务|电子狗导航手机|弹(种|夹)|(追|讨)债|车用电子狗|避孕|办理(证件|文凭)|斑蝥|暗访包|SIM卡复制器|BB(枪|弹)|雷管|弓弩|(电|长)狗|导爆索|爆炸物|爆破|左棍|婊子|换妻|成人片|淫(靡|水|兽)|阴(毛|蒂|道|唇)|小穴|缩阴|少妇自拍|(三级|色情|激情|黄色|小)(片|电影|视频|交友|电话)|肉棒|(情|奸)杀|裸照|乱伦|口交|禁(网|片)|春宫图|SM用品|自动群发|私家侦探服务|生意宝|商务(快车|短信)|慧聪|供应发票|发票代开|短信群发|短信猫|点金商务|士的宁|士的年|六合(采|彩)|乐透码|彩票|百乐二呓|百家乐|黄页|出租|求购|留学咨询|外挂|淘宝|群发|货到付款|汽车配件|推广联盟|劳务派遣|网络(兼职|赚钱)|(证件|婚庆|翻译|搬家|追债|债务)公司|手机(游戏|窃听|监听|铃声|图片)|三唑仑|奇迹世界|工作服|论文|铃声|彩(信|铃|票)|显示屏|投影仪|虚拟主机|(域名|专业)注册|营销|服务器托管|网站建设|(google|百度)排名|数据恢复|医院|性病|不孕不育|乳腺病|尖锐湿疣|皮肤病|减肥|瘦|3P|人兽|代孕|打炮|找小姐|刻章|乱伦|中出|楼凤|卖淫|荡妇|群交|幼女|18禁|伦理电影|(催情|蒙汗|蒙汉|春)药|情趣用品|成人.+?(电影|用品)|激情(视频|电影|影院)|爽片|性感美女|交友|怀孕|裸聊|制服诱惑|丝袜|长腿|寂寞女子|免费电影|双色球|福彩|体彩|6合彩|时时彩|双色球|咨询热线|股票|荐股|开股|私服|枪|警棒|警服|麻醉|诚招加盟|诚信经营|杀手|(游戏|金)币|群发|注册.+?公司|公司注册|发票|代开|淘宝|返利|团购|培训|折扣|(打包|试验|打标|破碎|灌装|升降|干燥|烘干)机|条码|标签纸|升降平台|地源热泵|风机盘管|二手(车|电脑)|手表|加盟|名表|特卖|证书|聊天室|分销', '病毒|赌博|三级|色情|黄色|A片|a片|成人片|成人电影|淫|小穴|缩阴|麻醉药|避孕|18禁|卖淫|荡妇|不孕不育|6合彩|六合彩|双色球|免费电影|福彩|体彩|爽片|性感美女|时时彩|3P|代孕|打炮|找小姐|刻章|乱伦|减肥|杀手|催情|春药|成人用品|裸聊|代孕|私服|毒品|冰毒|他妈|你妈|操你妈|操他妈|你妈逼|诚信经营|诚招加盟|枪|开股|咨询热线|警服', '127.0.0.1', 'ON', 'ON', 'ON', 'ON', 'ON', '5', '35', '0', '0', '0', '15', 'Asia/Shanghai', 'zh-cn', 'jpg|gif|png|jpeg|bmp|psd|wmf|ico|rpm|deb|tar|gz|sit|7z|bz2|zip|rar|xml|xsl|svg|svgz|rtf|doc|docx|ppt|pptx|xls|xlsx|wps|chm|txt|pdf|mp3|mp4|avi|mpg|rm|ra|rmvb|mov|wmv|wma|swf|fla|torrent|apk|zba|gzba', '2', 'ON', 'OFF', 'ON', 'OFF', '30', 'ON', '130', '150', 'ON');
#777#
drop table if exists %103%fileupload;
#777#
create table %103%fileupload(
id int(10) unsigned not null auto_increment primary key comment '主键',
types varchar(255) not null default '' comment '文件类型',
filepath varchar(255) not null default '' comment '文件路径',
filename varchar(255) not null default '' comment '文件名',
filesize varchar(255) not null default '' comment '文件大小',
uptime varchar(255) not null default '' comment '上传时间',
username varchar(255) not null default '' comment '作者',
key key_filename(filename),
key key_uptime(uptime),
key key_username(username)
)ENGINE=%104% DEFAULT CHARSET='%102%';
#777#
drop table if exists %103%browse;
#777#
create table %103%browse(
id int(10) unsigned not null auto_increment primary key comment '主键',
browse int(10) unsigned not null default 0 comment '点击次数PV',
number int(10) unsigned not null default 0 comment '独立浏览次数',
visitorip varchar(255) not null default '' comment '独立IP',
publitime int(11) unsigned not null default 0 comment '浏览时段',
templateid int(10) unsigned not null default 0 comment '模板id',
flag tinyint(10) unsigned not null default 0 comment '模块标识',
key key_visitorip(visitorip),
key key_publitime(publitime),
key key_templateid(templateid)
)ENGINE=%104% DEFAULT CHARSET='%102%';
#777#
drop table if exists %103%orders;
#777#
create table %103%orders(
id int(10) unsigned not null auto_increment primary key comment '主键',
commodity varchar(255) not null default '' comment '商品名称',
ordernumber varchar(255) not null default '' comment '订单号',
money varchar(255) not null default '' comment '金额',
phone varchar(255) not null default '' comment '手机',
email varchar(255) not null default '' comment '邮箱',
publitime int(11) unsigned not null default 0 comment '时间',
address text not null comment '地址',
allinfor MediumText not null comment '所有订单信息，json格式',
userip varchar(255) not null default '' comment '游客IP',
templateid int(10) unsigned not null default 0 comment '模板id',
status tinyint(10) unsigned not null default 0 comment '订单状态',
back varchar(255) not null default '' comment '返回URL',
key key_commodity(commodity),
key key_ordernumber(ordernumber),
key key_phone(phone),
key key_email(email),
key key_publitime(publitime)
)ENGINE=%104% DEFAULT CHARSET='%102%';
#777#
drop table if exists %103%message;
#777#
create table %103%message(
id int(10) unsigned not null auto_increment primary key comment '主键',
pid int(10) unsigned not null default 0 comment '回复',
name varchar(255) not null default '' comment '姓名',
age varchar(255) not null default '' comment '年龄',
body text not null comment '正文',
phone varchar(255) not null default '' comment '手机',
email varchar(255) not null default '' comment '邮箱',
publitime int(11) unsigned not null default 0 comment '时间',
userip varchar(255) not null default '' comment '游客IP',
templateid int(10) unsigned not null default 0 comment '模板id',
status tinyint(10) unsigned not null default 0 comment '状态',
back varchar(255) not null default '' comment '返回URL',
key key_pid(pid),
key key_phone(phone),
key key_email(email),
key key_publitime(publitime)
)ENGINE=%104% DEFAULT CHARSET='%102%';
#777#
drop table if exists %103%apack;
#777#
create table %103%apack(
	id int(10) unsigned not null auto_increment primary key comment '主键',
	picapth varchar(255) not null default '' comment '头像地址',
	picname varchar(255) not null default '' comment '头像图片名称',
	picsize varchar(255) not null default '' comment '头像大小',
	key key_picname(picname)	
)ENGINE=%104% DEFAULT CHARSET='%102%';
#777#
INSERT INTO %103%apack(picname) VALUES('default/533e4c700001c60f02200220.jpg');
#777#
INSERT INTO %103%apack(picname) VALUES('default/533e4c700001c60f02200220.jpg');
#777#
INSERT INTO %103%apack(picname) VALUES('default/533e4c1500010baf02200220.jpg');
#777#
INSERT INTO %103%apack(picname) VALUES('default/533e4d3d0001ed7802000200.jpg');
#777#
INSERT INTO %103%apack(picname) VALUES('default/533e4fc800012f3002000200.jpg');
#777#
INSERT INTO %103%apack(picname) VALUES('default/533e51840001ca2502000200.jpg');
#777#
INSERT INTO %103%apack(picname) VALUES('default/5333a0aa000121d702000200.jpg');
#777#
INSERT INTO %103%apack(picname) VALUES('default/5333a0f60001eaa802200220.jpg');
#777#
INSERT INTO %103%apack(picname) VALUES('default/5333a2b70001a5a802000200.jpg');
#777#
INSERT INTO %103%apack(picname) VALUES('default/5333a08f0001700202000200.jpg');
#777#
INSERT INTO %103%apack(picname) VALUES('default/5333a0600001f9ed02000200.jpg');
#777#
INSERT INTO %103%apack(picname) VALUES('default/5333a0780001a6e702200220.jpg');
#777#
INSERT INTO %103%apack(picname) VALUES('default/54584cb50001e5b302200220.jpg');
#777#
INSERT INTO %103%apack(picname) VALUES('default/54584d9f0001043b02200220.jpg');
#777#
INSERT INTO %103%apack(picname) VALUES('default/54584d080001566902200220.jpg');
#777#
INSERT INTO %103%apack(picname) VALUES('default/54584dad0001dd7802200220.jpg');
#777#
INSERT INTO %103%apack(picname) VALUES('default/54584ef20001deba02200220.jpg');
#777#
INSERT INTO %103%apack(picname) VALUES('default/54584f3100019e9702200220.jpg');
#777#
INSERT INTO %103%apack(picname) VALUES('default/545847d40001cbef02200220.jpg');
#777#
INSERT INTO %103%apack(picname) VALUES('default/545861d500015cc602200220.jpg');
#777#
INSERT INTO %103%apack(picname) VALUES('default/545861f00001be3402200220.jpg');
#777#
INSERT INTO %103%apack(picname) VALUES('default/545865da00012e6402200220.jpg');
#777#
INSERT INTO %103%apack(picname) VALUES('default/5458625e000166a002190220.jpg');
#777#
INSERT INTO %103%apack(picname) VALUES('default/5458639d0001bed702200220.jpg');
#777#
INSERT INTO %103%apack(picname) VALUES('default/5458676e0001af7702200220.jpg');
#777#
INSERT INTO %103%apack(picname) VALUES('default/5458689e000115c602200220.jpg');
#777#
INSERT INTO %103%apack(picname) VALUES('default/545846580001fede02200220.jpg');
#777#
INSERT INTO %103%apack(picname) VALUES('default/545850200001359c02200220.jpg');
#777#
INSERT INTO %103%apack(picname) VALUES('default/545864190001966102200220.jpg');
#777#
INSERT INTO %103%apack(picname) VALUES('default/545867340001101702200220.jpg');
#777#
drop table if exists %103%area;
#777#
create table %103%area(
	id int(10) unsigned not null auto_increment primary key comment '主键',
	aid int(10) unsigned not null default 0 comment '编号',
	areaname varchar(255) not null default '' comment '区域名-全',
	areasmax varchar(255) not null default '' comment '简称-中',
	areasmin varchar(255) not null default '' comment '简称-小',
	sort int(10) unsigned not null default 0 comment '排序',
	state tinyint(10) unsigned not null default 0 comment '状态',
	key key_aid(aid),
	key key_sort(sort),
	key key_state(state)
)ENGINE=%104% DEFAULT CHARSET='%102%';
#777#
INSERT INTO %103%area(aid,areaname,areasmax,areasmin) VALUES(0,'全部','所有','全');
#777#
INSERT INTO %103%area(aid,areaname,areasmax,areasmin) VALUES(1,'北京市','北京','京');
#777#
INSERT INTO %103%area(aid,areaname,areasmax,areasmin) VALUES(2,'天津市','天津','津');
#777#
INSERT INTO %103%area(aid,areaname,areasmax,areasmin) VALUES(3,'重庆市','重庆','渝');
#777#
INSERT INTO %103%area(aid,areaname,areasmax,areasmin) VALUES(4,'上海市','上海','沪');
#777#
INSERT INTO %103%area(aid,areaname,areasmax,areasmin) VALUES(5,'湖北省','湖北','鄂');
#777#
INSERT INTO %103%area(aid,areaname,areasmax,areasmin) VALUES(6,'湖南省','湖南','湘');
#777#
INSERT INTO %103%area(aid,areaname,areasmax,areasmin) VALUES(7,'广东省','广东','粤');
#777#
INSERT INTO %103%area(aid,areaname,areasmax,areasmin) VALUES(8,'海南省','海南','琼');
#777#
INSERT INTO %103%area(aid,areaname,areasmax,areasmin) VALUES(9,'河北省','河北','冀');
#777#
INSERT INTO %103%area(aid,areaname,areasmax,areasmin) VALUES(10,'四川省','四川','川');
#777#
INSERT INTO %103%area(aid,areaname,areasmax,areasmin) VALUES(11,'山西省','山西','晋');
#777#
INSERT INTO %103%area(aid,areaname,areasmax,areasmin) VALUES(12,'贵州省','贵州','贵');
#777#
INSERT INTO %103%area(aid,areaname,areasmax,areasmin) VALUES(13,'辽宁省','辽宁','辽');
#777#
INSERT INTO %103%area(aid,areaname,areasmax,areasmin) VALUES(14,'云南省','云南','云');
#777#
INSERT INTO %103%area(aid,areaname,areasmax,areasmin) VALUES(15,'吉林省','吉林','吉');
#777#
INSERT INTO %103%area(aid,areaname,areasmax,areasmin) VALUES(16,'陕西省','陕西','陕');
#777#
INSERT INTO %103%area(aid,areaname,areasmax,areasmin) VALUES(17,'河南省','河南','豫');
#777#
INSERT INTO %103%area(aid,areaname,areasmax,areasmin) VALUES(18,'甘肃省','甘肃','甘');
#777#
INSERT INTO %103%area(aid,areaname,areasmax,areasmin) VALUES(19,'江苏省','江苏','苏');
#777#
INSERT INTO %103%area(aid,areaname,areasmax,areasmin) VALUES(20,'青海省','青海','青');
#777#
INSERT INTO %103%area(aid,areaname,areasmax,areasmin) VALUES(21,'浙江省','浙江','浙');
#777#
INSERT INTO %103%area(aid,areaname,areasmax,areasmin) VALUES(22,'安徽省','安徽','皖');
#777#
INSERT INTO %103%area(aid,areaname,areasmax,areasmin) VALUES(23,'江西省','江西','赣');
#777#
INSERT INTO %103%area(aid,areaname,areasmax,areasmin) VALUES(24,'山东省','山东','鲁');
#777#
INSERT INTO %103%area(aid,areaname,areasmax,areasmin) VALUES(25,'黑龙江省','黑龙江','黑');
#777#
INSERT INTO %103%area(aid,areaname,areasmax,areasmin) VALUES(26,'福建省','福建','闽');
#777#
INSERT INTO %103%area(aid,areaname,areasmax,areasmin) VALUES(27,'西藏自治区','西藏','藏');
#777#
INSERT INTO %103%area(aid,areaname,areasmax,areasmin) VALUES(28,'内蒙古自治区','内蒙','内');
#777#
INSERT INTO %103%area(aid,areaname,areasmax,areasmin) VALUES(29,'新疆维吾尔自治区','新疆','新');
#777#
INSERT INTO %103%area(aid,areaname,areasmax,areasmin) VALUES(30,'广西壮族自治区','广西','桂');
#777#
INSERT INTO %103%area(aid,areaname,areasmax,areasmin) VALUES(31,'宁夏回族自治区','宁夏','宁');
#777#
INSERT INTO %103%area(aid,areaname,areasmax,areasmin) VALUES(32,'香港特别行政区','香港','港');
#777#
INSERT INTO %103%area(aid,areaname,areasmax,areasmin) VALUES(33,'澳门特别行政区','澳门','澳');
#777#
INSERT INTO %103%area(aid,areaname,areasmax,areasmin) VALUES(34,'台湾省','台湾','台');