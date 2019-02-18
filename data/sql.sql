-- phpMyAdmin SQL Dump
-- version 4.8.0-dev
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: 2017-06-19 06:18:22
-- 服务器版本： 5.7.17
-- PHP Version: 7.1.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
SET time_zone = "+00:00";

--
-- Database: `YihaoCMS_www`
--

-- --------------------------------------------------------

--
-- 表的结构 `sent_action`
--

DROP TABLE IF EXISTS `sent_action`;
CREATE TABLE `sent_action` (
  `id` int(11) UNSIGNED NOT NULL COMMENT '主键',
  `name` char(30) NOT NULL DEFAULT '' COMMENT '行为唯一标识',
  `title` char(80) NOT NULL DEFAULT '' COMMENT '行为说明',
  `remark` char(140) NOT NULL DEFAULT '' COMMENT '行为描述',
  `rule` text COMMENT '行为规则',
  `log` text COMMENT '日志规则',
  `type` tinyint(2) UNSIGNED NOT NULL DEFAULT '1' COMMENT '类型',
  `status` tinyint(2) NOT NULL DEFAULT '0' COMMENT '状态',
  `update_time` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '修改时间'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='系统行为表' ROW_FORMAT=DYNAMIC;

--
-- 转存表中的数据 `sent_action`
--

INSERT INTO `sent_action` (`id`, `name`, `title`, `remark`, `rule`, `log`, `type`, `status`, `update_time`) VALUES
(1, 'user_login', '用户登录', '积分+10，每天一次', 'table:member|field:score|condition:uid={$self} AND status>-1|rule:score+10|cycle:24|max:1;', '[user|get_nickname]在[time|time_format]登录了后台', 1, 1, 1387181220),
(2, 'add_article', '发布文章', '积分+5，每天上限5次', 'table:member|field:score|condition:uid={$self}|rule:score+5|cycle:24|max:5', '', 2, 1, 1380173180),
(3, 'review', '评论', '评论积分+1，无限制', 'table:member|field:score|condition:uid={$self}|rule:score+1', '', 2, 1, 1383285646),
(4, 'add_document', '发表文档', '积分+10，每天上限5次', 'table:member|field:score|condition:uid={$self}|rule:score+10|cycle:24|max:5', '[user|get_nickname]在[time|time_format]发表了一篇文章。\r\n表[model]，记录编号[record]。', 2, 1, 1386139726),
(5, 'add_document_topic', '发表讨论', '积分+5，每天上限10次', 'table:member|field:score|condition:uid={$self}|rule:score+5|cycle:24|max:10', '', 2, 1, 1383285551),
(6, 'update_config', '更新配置', '新增或修改或删除配置', '', '', 1, 1, 1383294988),
(7, 'update_model', '更新模型', '新增或修改模型', '', '', 1, 1, 1383295057),
(8, 'update_attribute', '更新属性', '新增或更新或删除属性', '', '', 1, 1, 1383295963),
(9, 'update_channel', '更新导航', '新增或修改或删除导航', '', '', 1, 1, 1383296301),
(10, 'update_menu', '更新菜单', '新增或修改或删除菜单', '', '', 1, 1, 1383296392),
(11, 'update_category', '更新分类', '新增或修改或删除分类.', '', '', 1, 1, 1383296765);

-- --------------------------------------------------------

--
-- 表的结构 `sent_action_log`
--

DROP TABLE IF EXISTS `sent_action_log`;
CREATE TABLE `sent_action_log` (
  `id` int(10) UNSIGNED NOT NULL COMMENT '主键',
  `action_id` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '行为id',
  `user_id` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '执行用户id',
  `action_ip` bigint(20) NOT NULL COMMENT '执行行为者ip',
  `model` varchar(50) NOT NULL DEFAULT '' COMMENT '触发行为的表',
  `record_id` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '触发行为的数据id',
  `remark` varchar(255) NOT NULL DEFAULT '' COMMENT '日志备注',
  `status` tinyint(2) NOT NULL DEFAULT '1' COMMENT '状态',
  `create_time` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '执行行为的时间'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='行为日志表' ROW_FORMAT=FIXED;

-- --------------------------------------------------------

--
-- 表的结构 `sent_ad`
--

DROP TABLE IF EXISTS `sent_ad`;
CREATE TABLE `sent_ad` (
  `id` int(10) UNSIGNED NOT NULL COMMENT '主键',
  `place_id` int(11) DEFAULT NULL COMMENT '广告位ID',
  `title` varchar(150) DEFAULT NULL COMMENT '广告名称',
  `cover_id` int(11) DEFAULT NULL COMMENT '广告图片',
  `photolist` varchar(20) NOT NULL COMMENT '辅助图片',
  `url` varchar(150) DEFAULT NULL COMMENT '广告链接',
  `listurl` varchar(255) DEFAULT NULL COMMENT '辅助链接',
  `background` varchar(150) DEFAULT NULL COMMENT '广告背景',
  `content` text COMMENT '广告描述',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) NOT NULL DEFAULT '0' COMMENT '更新时间',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '广告位状态'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='广告表';

--
-- 转存表中的数据 `sent_ad`
--

INSERT INTO `sent_ad` (`id`, `place_id`, `title`, `cover_id`, `photolist`, `url`, `listurl`, `background`, `content`, `create_time`, `update_time`, `status`) VALUES
(1, 1, '电子商务系统设计与开发', 0, '', '', '', '', '<p>我们为客户提供专业的B2C、C2C、B2B、P2P等电子商务平台开发，同时紧扣企业运营，提供个性化的电子商务平台开发和运营管理平台。 </p>', 1440433466, 1440433466, 1),
(2, 1, '企业网站设计与开发', 0, '', '', '', '', '<p>我们为您提供更加专业的企业网站建设、企业文化提升以及企业形象包装服务，为您量身打造更加适合当前网络时代的需求服务，让您的公司更加贴近客户。</p>', 1440433531, 1440433531, 1),
(3, 1, 'WEB应用系统设计与开发', 14, '', '', '', '', '<p>我们为您的企业量身打造专业级别的企业OA系统、CRM系统等，为事、企业单位提供更好更高效的无纸化办公平台环境。</p>', 1440433554, 1462966106, 1),
(4, 1, '手机APP应用设计与开发', 0, '', 'http://diao.info', '', '', '<p>腾速科技有限公司专注于手机客户端软件开发，是省内移动客户端开发商的先行者，做到行业内领先地位。 </p>', 1440433571, 1459489811, 1);

-- --------------------------------------------------------

--
-- 表的结构 `sent_addons`
--

DROP TABLE IF EXISTS `sent_addons`;
CREATE TABLE `sent_addons` (
  `id` int(10) UNSIGNED NOT NULL COMMENT '主键',
  `name` varchar(40) NOT NULL COMMENT '插件名或标识',
  `title` varchar(20) NOT NULL DEFAULT '' COMMENT '中文名',
  `description` text COMMENT '插件描述',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态',
  `config` text COMMENT '配置',
  `author` varchar(40) DEFAULT '' COMMENT '作者',
  `version` varchar(20) DEFAULT '' COMMENT '版本号',
  `isinstall` int(10) DEFAULT '0' COMMENT '是否安装',
  `create_time` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '安装时间',
  `has_adminlist` tinyint(1) UNSIGNED NOT NULL DEFAULT '0' COMMENT '是否有后台列表'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='插件表';

--
-- 转存表中的数据 `sent_addons`
--

INSERT INTO `sent_addons` (`id`, `name`, `title`, `description`, `status`, `config`, `author`, `version`, `isinstall`, `create_time`, `has_adminlist`) VALUES
(1, 'Devteam', '开发团队信息', '开发团队成员信息', 1, '', 'molong', '0.1', 1, 0, 0),
(2, 'Sitestat', '站点统计信息', '统计站点的基础信息', 1, '', 'thinkphp', '0.2', 1, 0, 0),
(3, 'Systeminfo', '系统环境信息', '用于显示一些服务器的信息', 1, '', 'molong', '0.1', 1, 0, 0);

-- --------------------------------------------------------

--
-- 表的结构 `sent_ad_place`
--

DROP TABLE IF EXISTS `sent_ad_place`;
CREATE TABLE `sent_ad_place` (
  `id` int(10) UNSIGNED NOT NULL COMMENT '主键',
  `title` varchar(150) DEFAULT NULL COMMENT '广告位名称',
  `name` varchar(20) NOT NULL COMMENT '调用名称',
  `show_type` int(11) NOT NULL DEFAULT '5' COMMENT '广告位类型',
  `show_num` int(11) NOT NULL DEFAULT '5' COMMENT '显示条数',
  `start_time` int(11) NOT NULL DEFAULT '0' COMMENT '开始时间',
  `end_time` int(11) NOT NULL DEFAULT '0' COMMENT '结束时间',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) NOT NULL DEFAULT '0' COMMENT '更新时间',
  `template` varchar(150) DEFAULT NULL COMMENT '广告位模板',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '广告位状态'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='广告位表';

--
-- 转存表中的数据 `sent_ad_place`
--

INSERT INTO `sent_ad_place` (`id`, `title`, `name`, `show_type`, `show_num`, `start_time`, `end_time`, `create_time`, `update_time`, `template`, `status`) VALUES
(1, '首页幻灯片', 'banner', 1, 5, 0, 0, 1440433367, 1440433367, '', 1);

-- --------------------------------------------------------

--
-- 表的结构 `sent_attachment`
--

DROP TABLE IF EXISTS `sent_attachment`;
CREATE TABLE `sent_attachment` (
  `id` int(10) UNSIGNED NOT NULL,
  `uid` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '用户ID',
  `title` char(30) NOT NULL DEFAULT '' COMMENT '附件显示名',
  `type` tinyint(3) UNSIGNED NOT NULL DEFAULT '0' COMMENT '附件类型',
  `source` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '资源ID',
  `record_id` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '关联记录ID',
  `download` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '下载次数',
  `size` bigint(20) UNSIGNED NOT NULL DEFAULT '0' COMMENT '附件大小',
  `dir` int(12) UNSIGNED NOT NULL DEFAULT '0' COMMENT '上级目录ID',
  `sort` int(8) UNSIGNED NOT NULL DEFAULT '0' COMMENT '排序',
  `create_time` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '更新时间',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='附件表';

-- --------------------------------------------------------

--
-- 表的结构 `sent_attribute`
--

DROP TABLE IF EXISTS `sent_attribute`;
CREATE TABLE `sent_attribute` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(30) NOT NULL DEFAULT '' COMMENT '字段名',
  `title` varchar(100) NOT NULL DEFAULT '' COMMENT '字段注释',
  `length` varchar(100) NOT NULL DEFAULT '' COMMENT '字段定义',
  `type` varchar(20) NOT NULL DEFAULT '' COMMENT '数据类型',
  `value` varchar(100) NOT NULL DEFAULT '' COMMENT '字段默认值',
  `remark` varchar(100) NOT NULL DEFAULT '' COMMENT '备注',
  `extra` varchar(255) NOT NULL DEFAULT '' COMMENT '参数',
  `model_id` int(11) NOT NULL COMMENT '所属模型',
  `is_show` int(11) NOT NULL DEFAULT '1' COMMENT '是否显示',
  `is_must` int(11) NOT NULL DEFAULT '0' COMMENT '是否必填',
  `group_id` int(11) NOT NULL DEFAULT '1' COMMENT '分组',
  `sort` int(11) NOT NULL DEFAULT '0' COMMENT '排序',
  `status` tinyint(2) NOT NULL DEFAULT '0' COMMENT '状态',
  `update_time` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '更新时间',
  `create_time` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '创建时间',
  `validate_rule` varchar(255) NOT NULL DEFAULT '',
  `validate_time` tinyint(1) UNSIGNED NOT NULL DEFAULT '0',
  `error_info` varchar(100) NOT NULL DEFAULT '',
  `validate_type` varchar(25) NOT NULL DEFAULT '',
  `auto_rule` varchar(100) NOT NULL DEFAULT '',
  `auto_time` tinyint(1) UNSIGNED NOT NULL DEFAULT '0',
  `auto_type` varchar(25) NOT NULL DEFAULT ''
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='模型属性表';

-- --------------------------------------------------------

--
-- 表的结构 `sent_auth_extend`
--

DROP TABLE IF EXISTS `sent_auth_extend`;
CREATE TABLE `sent_auth_extend` (
  `group_id` mediumint(10) UNSIGNED NOT NULL COMMENT '用户id',
  `extend_id` mediumint(8) UNSIGNED NOT NULL COMMENT '扩展表中数据的id',
  `type` tinyint(1) UNSIGNED NOT NULL COMMENT '扩展类型标识 1:栏目分类权限;2:模型权限'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户组与分类的对应关系表';

-- --------------------------------------------------------

--
-- 表的结构 `sent_auth_group`
--

DROP TABLE IF EXISTS `sent_auth_group`;
CREATE TABLE `sent_auth_group` (
  `id` mediumint(8) UNSIGNED NOT NULL COMMENT '用户组id,自增主键',
  `module` varchar(20) NOT NULL DEFAULT '' COMMENT '用户组所属模块',
  `type` varchar(10) NOT NULL DEFAULT '' COMMENT '组类型',
  `title` char(20) NOT NULL DEFAULT '' COMMENT '用户组中文名称',
  `description` varchar(80) NOT NULL DEFAULT '' COMMENT '描述信息',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '用户组状态：为1正常，为0禁用,-1为删除',
  `rules` varchar(500) NOT NULL DEFAULT '' COMMENT '用户组拥有的规则id，多个规则 , 隔开'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `sent_auth_group`
--

INSERT INTO `sent_auth_group` (`id`, `module`, `type`, `title`, `description`, `status`, `rules`) VALUES
(1, 'admin', '1', '网站编辑', '网站编辑，针对内容进行管理', 1, '2,1'),
(2, 'admin', '1', '网站运营', '针对网站SEO进行管理', 1, '21,20,14,12,3,19,18,17,16,15,13,1');

-- --------------------------------------------------------

--
-- 表的结构 `sent_auth_group_access`
--

DROP TABLE IF EXISTS `sent_auth_group_access`;
CREATE TABLE `sent_auth_group_access` (
  `uid` int(10) UNSIGNED NOT NULL COMMENT '用户id',
  `group_id` mediumint(8) UNSIGNED NOT NULL COMMENT '用户组id'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `sent_auth_rule`
--

DROP TABLE IF EXISTS `sent_auth_rule`;
CREATE TABLE `sent_auth_rule` (
  `id` mediumint(8) UNSIGNED NOT NULL COMMENT '规则id,自增主键',
  `module` varchar(20) NOT NULL COMMENT '规则所属module',
  `type` tinyint(2) NOT NULL DEFAULT '1' COMMENT '1-url;2-主菜单',
  `name` char(80) NOT NULL DEFAULT '' COMMENT '规则唯一英文标识',
  `title` char(20) NOT NULL DEFAULT '' COMMENT '规则中文描述',
  `group` char(20) NOT NULL DEFAULT '' COMMENT '权限节点分组',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否有效(0:无效,1:有效)',
  `condition` varchar(300) NOT NULL DEFAULT '' COMMENT '规则附加条件'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `sent_auth_rule`
--

INSERT INTO `sent_auth_rule` (`id`, `module`, `type`, `name`, `title`, `group`, `status`, `condition`) VALUES
(1, 'admin', 2, 'admin/index/index', '后台首页', '首页管理', 1, ''),
(2, 'admin', 2, 'admin/form/index', '自定义表单', '运营管理', 1, ''),
(3, 'admin', 2, 'admin/addons/hooks', '钩子列表', '扩展管理', 1, ''),
(4, 'admin', 2, 'admin/addons/index', '插件列表', '扩展管理', 1, ''),
(5, 'admin', 2, 'admin/ad/index', '广告管理', '运营管理', 1, ''),
(6, 'admin', 2, 'admin/link/index', '友链管理', '运营管理', 1, ''),
(7, 'admin', 2, 'admin/action/log', '行为日志', '会员管理', 1, ''),
(8, 'admin', 2, 'admin/action/index', '行为列表', '会员管理', 1, ''),
(9, 'admin', 2, 'admin/group/access', '权限列表', '会员管理', 1, ''),
(10, 'admin', 2, 'admin/group/index', '用户组表', '会员管理', 1, ''),
(11, 'admin', 2, 'admin/user/index', '用户列表', '会员管理', 1, ''),
(12, 'admin', 2, 'admin/model/index', '模型管理', '内容管理', 1, ''),
(13, 'admin', 2, 'admin/category/index', '栏目管理', '内容管理', 1, ''),
(14, 'admin', 2, 'admin/seo/index', 'SEO设置', '系统管理', 1, ''),
(15, 'admin', 2, 'admin/database/index?type=import', '数据恢复', '系统管理', 1, ''),
(16, 'admin', 2, 'admin/database/index?type=export', '数据备份', '系统管理', 1, ''),
(17, 'admin', 2, 'admin/channel/index', '导航管理', '系统管理', 1, ''),
(18, 'admin', 2, 'admin/menu/index', '菜单管理', '系统管理', 1, ''),
(19, 'admin', 2, 'admin/config/group', '配置管理', '系统管理', 1, ''),
(20, 'admin', 2, 'admin/index/clear', '更新缓存', '首页管理', 1, ''),
(21, 'admin', 1, 'admin/config/add', '配置添加', '系统管理', 1, ''),
(22, 'admin', 2, 'admin/content/index', '内容列表', '内容管理', 1, ''),
(23, 'admin', 2, 'admin/content/add', '内容添加', '内容管理', 1, ''),
(24, 'admin', 1, 'admin/content/edit', '内容编辑', '内容管理', 1, ''),
(25, 'admin', 1, 'admin/content/del', '内容删除', '内容管理', 1, ''),
(26, 'admin', 1, 'admin/content/status', '内容设置状态', '内容管理', 1, ''),
(27, 'admin', 1, 'admin/category/add', '栏目添加', '内容管理', 1, ''),
(28, 'admin', 1, 'admin/category/edit', '栏目编辑', '内容管理', 1, ''),
(29, 'admin', 1, 'admin/category/editable', '栏目单字编辑', '内容管理', 1, ''),
(30, 'admin', 1, 'admin/category/remove', '栏目删除', '内容管理', 1, ''),
(31, 'admin', 1, 'admin/category/merge', '栏目合并', '内容管理', 1, ''),
(32, 'admin', 1, 'admin/category/move', '栏目移动', '内容管理', 1, ''),
(33, 'admin', 1, 'admin/category/status', '栏目状态', '内容管理', 1, '');

-- --------------------------------------------------------

--
-- 表的结构 `sent_category`
--

DROP TABLE IF EXISTS `sent_category`;
CREATE TABLE `sent_category` (
  `id` int(10) UNSIGNED NOT NULL COMMENT '分类ID',
  `name` varchar(30) NOT NULL COMMENT '标志',
  `title` varchar(50) NOT NULL COMMENT '标题',
  `pid` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '上级分类ID',
  `model_id` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '绑定模型ID',
  `sort` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '排序（同级有效）',
  `ismenu` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '生成导航返回的ID',
  `list_row` tinyint(3) UNSIGNED NOT NULL DEFAULT '10' COMMENT '列表每页行数',
  `meta_title` varchar(50) NOT NULL DEFAULT '' COMMENT 'SEO的网页标题',
  `keywords` varchar(255) NOT NULL DEFAULT '' COMMENT '关键字',
  `description` varchar(255) NOT NULL DEFAULT '' COMMENT '描述',
  `template_index` varchar(100) NOT NULL DEFAULT '' COMMENT '频道页模板',
  `template_lists` varchar(100) NOT NULL DEFAULT '' COMMENT '列表页模板',
  `template_detail` varchar(100) NOT NULL DEFAULT '' COMMENT '详情页模板',
  `template_edit` varchar(100) NOT NULL DEFAULT '' COMMENT '编辑页模板',
  `model` varchar(100) NOT NULL DEFAULT '' COMMENT '列表绑定模型',
  `model_sub` varchar(100) NOT NULL DEFAULT '' COMMENT '子文档绑定模型',
  `type` varchar(100) NOT NULL DEFAULT '' COMMENT '允许发布的内容类型',
  `link_id` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '外链',
  `allow_publish` tinyint(3) UNSIGNED NOT NULL DEFAULT '0' COMMENT '是否允许发布内容',
  `display` tinyint(3) UNSIGNED NOT NULL DEFAULT '0' COMMENT '可见性',
  `reply` tinyint(3) UNSIGNED NOT NULL DEFAULT '0' COMMENT '是否允许回复',
  `check` tinyint(3) UNSIGNED NOT NULL DEFAULT '0' COMMENT '发布的文章是否需要审核',
  `reply_model` varchar(100) NOT NULL DEFAULT '',
  `extend` text COMMENT '扩展设置',
  `create_time` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '更新时间',
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '数据状态',
  `icon` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '分类图标',
  `groups` varchar(255) NOT NULL DEFAULT '' COMMENT '分组定义'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='分类表';

--
-- 转存表中的数据 `sent_category`
--

INSERT INTO `sent_category` (`id`, `name`, `title`, `pid`, `ismenu`, `sort`, `list_row`, `meta_title`, `keywords`, `description`, `template_index`, `template_lists`, `template_detail`, `template_edit`, `model`, `model_sub`, `type`, `link_id`, `allow_publish`, `display`, `reply`, `check`, `reply_model`, `extend`, `create_time`, `update_time`, `status`, `icon`, `groups`) VALUES
(1, 'news', '新闻动态', 0, 1, 0, 10, '', '', '', '', '', '', '', '', '2', '2,1', 0, 0, 1, 0, 0, '1', 'null', 1379474947, 1463561497, 1, 0, ''),
(2, 'company_news', '国内新闻', 1, 0, 1, 10, '', '', '', '', '', '', '', '2,3', '2', '2,1,3', 0, 1, 1, 0, 1, '1', '', 1379475028, 1386839751, 1, 0, ''),
(3, '', '国外新闻', 1, 0, 2, 10, '', '', '', '', '', '', '', '', '', '', 0, 1, 1, 0, 0, '', '', 0, 0, 1, 0, '');

-- --------------------------------------------------------

--
-- 表的结构 `sent_channel`
--

DROP TABLE IF EXISTS `sent_channel`;
CREATE TABLE `sent_channel` (
  `id` int(10) UNSIGNED NOT NULL COMMENT '频道ID',
  `pid` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '上级频道ID',
  `title` char(30) NOT NULL COMMENT '频道标题',
  `url` char(100) NOT NULL COMMENT '频道连接',
  `type` int(2) NOT NULL DEFAULT '0' COMMENT '导航类型',
  `sort` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '导航排序',
  `icon` varchar(20) DEFAULT NULL COMMENT '图标',
  `color` varchar(20) DEFAULT NULL COMMENT '导航颜色',
  `band_color` varchar(20) DEFAULT NULL COMMENT '标识点颜色',
  `band_text` varchar(30) DEFAULT NULL COMMENT '标志点文字',
  `active` char(100) NOT NULL DEFAULT '' COMMENT '当前链接',
  `create_time` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '更新时间',
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '状态',
  `target` tinyint(2) UNSIGNED NOT NULL DEFAULT '0' COMMENT '新窗口打开'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `sent_channel`
--

INSERT INTO `sent_channel` (`id`, `pid`, `title`, `url`, `type`, `sort`, `icon`, `color`, `band_color`, `band_text`, `active`, `create_time`, `update_time`, `status`, `target`) VALUES
(1, 0, '网站首页', 'index/index/index', 0, 1, 'home', '', '', '', 'home', 1379475111, 1464490544, 1, 0),
(2, 0, '新闻资讯', 'article/list/1', 0, 1, 'article', '', '', '', 'article', 1379475111, 1464490544, 1, 0);

-- --------------------------------------------------------

--
-- 表的结构 `sent_config`
--

DROP TABLE IF EXISTS `sent_config`;
CREATE TABLE `sent_config` (
  `id` int(10) UNSIGNED NOT NULL COMMENT '配置ID',
  `name` varchar(30) NOT NULL DEFAULT '' COMMENT '配置名称',
  `type` varchar(10) NOT NULL DEFAULT 'text' COMMENT '配置类型',
  `title` varchar(50) NOT NULL DEFAULT '' COMMENT '配置说明',
  `group` tinyint(3) UNSIGNED NOT NULL DEFAULT '0' COMMENT '配置分组',
  `extra` varchar(255) NOT NULL DEFAULT '' COMMENT '配置值',
  `remark` varchar(100) NOT NULL DEFAULT '' COMMENT '配置说明',
  `icon` varchar(50) NOT NULL DEFAULT '' COMMENT '小图标',
  `create_time` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '更新时间',
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '状态',
  `value` text COMMENT '配置值',
  `sort` smallint(3) UNSIGNED NOT NULL DEFAULT '0' COMMENT '排序'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT '配置详情表';

--
-- 转存表中的数据 `sent_config`
--

INSERT INTO `sent_config` (`id`, `name`, `type`, `title`, `group`, `extra`, `remark`, `icon`, `create_time`, `update_time`, `status`, `value`, `sort`) VALUES
(1, 'config_group_list', 'textarea', '配置分组', 99, '', '', '', 1447305542, 1452323143, 1, '1:基本\r\n2:会员\r\n3:邮件\r\n4:微信\r\n99:系统', 0),
(2, 'hooks_type', 'textarea', '钩子的类型', 99, '', '类型 1-用于扩展显示内容，2-用于扩展业务处理', '', 1379313397, 1379313407, 1, '1:视图\r\n2:控制器', 6),
(3, 'auth_config', 'textarea', 'Auth配置', 99, '', '自定义Auth.class.php类配置', '', 1379409310, 1379409564, 1, 'AUTH_ON:1\r\nAUTH_TYPE:2', 8),
(5, 'data_backup_path', 'text', '数据库备份根路径', 99, '', '路径必须以 / 结尾', '', 1381482411, 1381482411, 1, './data/backup/', 5),
(6, 'data_backup_part_size', 'text', '数据库备份卷大小', 99, '', '该值用于限制压缩后的分卷最大长度。单位：B；建议设置20M', '', 1381482488, 1381729564, 1, '20971520', 7),
(7, 'data_backup_compress', 'bool', '数据库备份文件是否启用压缩', 99, '0:不压缩\r\n1:启用压缩', '压缩备份文件需要PHP环境支持gzopen,gzwrite函数', '', 1381713345, 1447306018, 1, '1', 9),
(8, 'data_backup_compress_level', 'select', '数据库备份文件压缩级别', 99, '1:普通\r\n4:一般\r\n9:最高', '数据库备份文件的压缩级别，该配置在开启压缩时生效', '', 1381713408, 1447305979, 1, '9', 10),
(9, 'develop_mode', 'bool', '开启开发者模式', 99, '0:关闭\r\n1:开启', '是否开启开发者模式', '', 1383105995, 1447305960, 1, '1', 11),
(10, 'allow_visit', 'textarea', '不受限控制器方法', 99, '', '', '', 1386644047, 1438075615, 1, '0:article/draftbox\r\n1:article/mydocument\r\n2:Category/tree\r\n3:Index/verify\r\n4:file/upload\r\n5:file/download\r\n6:user/updatePassword\r\n7:user/updateNickname\r\n8:user/submitPassword\r\n9:user/submitNickname\r\n10:file/uploadpicture', 0),
(11, 'deny_visit', 'textarea', '超管专限控制器方法', 99, '', '仅超级管理员可访问的控制器方法', '', 1386644141, 1438075628, 1, '0:Addons/addhook\r\n1:Addons/edithook\r\n2:Addons/delhook\r\n3:Addons/updateHook\r\n4:Admin/getMenus\r\n5:Admin/recordList\r\n6:AuthManager/updateRules\r\n7:AuthManager/tree', 0),
(12, 'admin_allow_ip', 'text', '后台允许访问IP', 99, '', '多个用逗号分隔，如果不配置表示不限制IP访问', '', 1387165454, 1452307198, 1, '', 12),
(13, 'app_debug', 'bool', '是否调试模式', 99, '0:关闭\r\n1:开启', '是否调试模式', '', 1387165685, 1481539829, 1, '0', 6),
(14, 'web_site_title', 'text', '网站标题', 1, '', '网站标题前台显示标题', '', 1378898976, 1379235274, 1, 'YihaoCMS网站管理系统', 0),
(15, 'web_site_url', 'text', '网站URL', 1, '', '网站网址', '', 1378898976, 1379235274, 1, 'http://diao.info', 1),
(16, 'web_site_description', 'textarea', '网站描述', 1, '', '网站搜索引擎描述', '', 1378898976, 1379235841, 1, 'YihaoCMS网站管理系统', 3),
(17, 'web_site_keyword', 'textarea', '网站关键字', 1, '', '网站搜索引擎关键字', '', 1378898976, 1381390100, 1, 'YihaoCMS网站管理系统,YihaoCMS', 6),
(18, 'web_site_close', 'bool', '关闭站点', 1, '0:否,1:是', '站点关闭后其他用户不能访问，管理员可以正常访问', '', 1378898976, 1447321395, 1, '0', 4),
(19, 'web_site_icp', 'text', '网站备案号', 1, '', '设置在网站底部显示的备案号，如“赣ICP备13006622号', '', 1378900335, 1379235859, 1, '赣ICP备13006622号', 7),
(20, 'open_mobile_site', 'bool', '开启手机站', 1, '0:关闭\r\n1:开启', '', '', 1440901307, 1440901543, 1, '0', 4),
(21, 'list_rows', 'num', '列表条数', 99, '', '', '', 1448937662, 1448937662, 1, '20', 10),
(22, 'user_allow_register', 'bool', '是否可注册', 2, '1:是\r\n0:否', '', '', 1449043544, 1449043586, 1, '1', 0),
(23, 'user_group_type', 'textarea', '会员分组类别', 2, '', '', '', 1449196835, 1449196835, 1, 'admin:系统管理员\r\nfront:会员等级', 1),
(24, 'config_type_list', 'textarea', '字段类型', 99, '', '', '', 1459136529, 1459136529, 1, 'text:单行文本:varchar\r\nstring:字符串:int\r\npassword:密码:varchar\r\ntextarea:文本框:text\r\nbool:布尔型:int\r\nselect:选择:varchar\r\nnum:数字:int\r\ndecimal:金额:decimal\r\ntags:标签:varchar\r\ndatetime:时间控件:int\r\ndate:日期控件:varchar\r\neditor:编辑器:text\r\nbind:模型绑定:int\r\nimage:图片上传:int\r\nimages:多图上传:varchar\r\nattach:文件上传:varchar', 0),
(25, 'document_position', 'textarea', '文档推荐位', 99, '', '', '', 1453449698, 1453449698, 1, '1:首页推荐\r\n2:列表推荐', 0),
(26, 'mail_host', 'text', 'smtp服务器的名称', 3, '', 'smtp服务器的名称', '', 1455690530, 1455690556, 1, 'smtp.163.com', 0),
(27, 'mail_smtpauth', 'select', '启用smtp认证', 3, '0:否,1:是', '启用smtp认证', '', 1455690641, 1455690689, 1, '1', 0),
(28, 'mail_username', 'text', '邮件发送用户名', 3, '', '邮件发送用户名', '', 1455690771, 1455690771, 1, '你的邮箱账号', 0),
(29, 'mail_password', 'text', '邮箱密码', 3, '', '邮箱密码，如果是qq邮箱，则填安全密码', '', 1455690802, 1455690802, 1, '你的邮箱密码', 0),
(30, 'mail_fromname', 'text', '发件人姓名', 3, '', '发件人姓名', '', 1455690838, 1455690838, 1, '发件人姓名', 0),
(31, 'mail_ishtml', 'select', '是否HTML格式邮件', 3, '0:否,1:是', '是否HTML格式邮件', '', 1455690888, 1455690888, 1, '1', 0),
(32, 'mail_charset', 'text', '邮件编码', 3, '', '设置发送邮件的编码', '', 1455690920, 1455690920, 1, 'UTF8', 0),
(33, 'wechat_name', 'text', '微信名称', 4, '', '填写微信名称', '', 1459136529, 1461898406, 1, '', 0),
(41, 'pc_themes', 'text', 'PC站模板', 0, '', '', '', 1480043043, 1480043043, 1, 'default', 0),
(42, 'mobile_themes', 'text', '手机站模板', 0, '', '', '', 1480043066, 1480043066, 1, '', 0),
(43, 'nav_type_list', 'textarea', '导航分类', 99, '', '', '', 1481539756, 1481539801, 1, '1:顶部\r\n2:底部', 1);

-- --------------------------------------------------------

--
-- 表的结构 `sent_client`
--

DROP TABLE IF EXISTS `sent_client`;
CREATE TABLE `sent_client` (
  `id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL COMMENT '客户端名称',
  `appid` varchar(32) NOT NULL COMMENT 'appid',
  `appsecret` varchar(32) NOT NULL COMMENT 'appsecret',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) NOT NULL DEFAULT '0' COMMENT '更新时间'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT '客户端信息表';

-- --------------------------------------------------------

--
-- 表的结构 `sent_district`
--

DROP TABLE IF EXISTS `sent_district`;
CREATE TABLE `sent_district` (
  `id` mediumint(8) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL DEFAULT '',
  `level` tinyint(4) UNSIGNED NOT NULL DEFAULT '0',
  `upid` mediumint(8) UNSIGNED NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='中国省市区乡镇数据表';

--
-- 转存表中的数据 `sent_district`
--

INSERT INTO `sent_district` (`id`, `name`, `level`, `upid`) VALUES
(1, '北京市', 1, 0),
(2, '天津市', 1, 0),
(3, '河北省', 1, 0),
(4, '山西省', 1, 0),
(5, '内蒙古自治区', 1, 0),
(6, '辽宁省', 1, 0),
(7, '吉林省', 1, 0),
(8, '黑龙江省', 1, 0),
(9, '上海市', 1, 0),
(10, '江苏省', 1, 0),
(11, '浙江省', 1, 0),
(12, '安徽省', 1, 0),
(13, '福建省', 1, 0),
(14, '江西省', 1, 0),
(15, '山东省', 1, 0),
(16, '河南省', 1, 0),
(17, '湖北省', 1, 0),
(18, '湖南省', 1, 0),
(19, '广东省', 1, 0),
(20, '广西壮族自治区', 1, 0),
(21, '海南省', 1, 0),
(22, '重庆市', 1, 0),
(23, '四川省', 1, 0),
(24, '贵州省', 1, 0),
(25, '云南省', 1, 0),
(26, '西藏自治区', 1, 0),
(27, '陕西省', 1, 0),
(28, '甘肃省', 1, 0),
(29, '青海省', 1, 0),
(30, '宁夏回族自治区', 1, 0),
(31, '新疆维吾尔自治区', 1, 0),
(32, '台湾省', 1, 0),
(33, '香港特别行政区', 1, 0),
(34, '澳门特别行政区', 1, 0),
(35, '海外', 1, 0),
(36, '其他', 1, 0),
(37, '东城区', 2, 1),
(38, '西城区', 2, 1),
(39, '崇文区', 2, 1),
(40, '宣武区', 2, 1),
(41, '朝阳区', 2, 1),
(42, '丰台区', 2, 1),
(43, '石景山区', 2, 1),
(44, '海淀区', 2, 1),
(45, '门头沟区', 2, 1),
(46, '房山区', 2, 1),
(47, '通州区', 2, 1),
(48, '顺义区', 2, 1),
(49, '昌平区', 2, 1),
(50, '大兴区', 2, 1),
(51, '怀柔区', 2, 1),
(52, '平谷区', 2, 1),
(53, '密云县', 2, 1),
(54, '延庆县', 2, 1),
(55, '和平区', 2, 2),
(56, '河东区', 2, 2),
(57, '河西区', 2, 2),
(58, '南开区', 2, 2),
(59, '河北区', 2, 2),
(60, '红桥区', 2, 2),
(61, '塘沽区', 2, 2),
(62, '汉沽区', 2, 2),
(63, '大港区', 2, 2),
(64, '东丽区', 2, 2),
(65, '西青区', 2, 2),
(66, '津南区', 2, 2),
(67, '北辰区', 2, 2),
(68, '武清区', 2, 2),
(69, '宝坻区', 2, 2),
(70, '宁河县', 2, 2),
(71, '静海县', 2, 2),
(72, '蓟县', 2, 2),
(73, '石家庄市', 2, 3),
(74, '唐山市', 2, 3),
(75, '秦皇岛市', 2, 3),
(76, '邯郸市', 2, 3),
(77, '邢台市', 2, 3),
(78, '保定市', 2, 3),
(79, '张家口市', 2, 3),
(80, '承德市', 2, 3),
(81, '衡水市', 2, 3),
(82, '廊坊市', 2, 3),
(83, '沧州市', 2, 3),
(84, '太原市', 2, 4),
(85, '大同市', 2, 4),
(86, '阳泉市', 2, 4),
(87, '长治市', 2, 4),
(88, '晋城市', 2, 4),
(89, '朔州市', 2, 4),
(90, '晋中市', 2, 4),
(91, '运城市', 2, 4),
(92, '忻州市', 2, 4),
(93, '临汾市', 2, 4),
(94, '吕梁市', 2, 4),
(95, '呼和浩特市', 2, 5),
(96, '包头市', 2, 5),
(97, '乌海市', 2, 5),
(98, '赤峰市', 2, 5),
(99, '通辽市', 2, 5),
(100, '鄂尔多斯市', 2, 5),
(101, '呼伦贝尔市', 2, 5),
(102, '巴彦淖尔市', 2, 5),
(103, '乌兰察布市', 2, 5),
(104, '兴安盟', 2, 5),
(105, '锡林郭勒盟', 2, 5),
(106, '阿拉善盟', 2, 5),
(107, '沈阳市', 2, 6),
(108, '大连市', 2, 6),
(109, '鞍山市', 2, 6),
(110, '抚顺市', 2, 6),
(111, '本溪市', 2, 6),
(112, '丹东市', 2, 6),
(113, '锦州市', 2, 6),
(114, '营口市', 2, 6),
(115, '阜新市', 2, 6),
(116, '辽阳市', 2, 6),
(117, '盘锦市', 2, 6),
(118, '铁岭市', 2, 6),
(119, '朝阳市', 2, 6),
(120, '葫芦岛市', 2, 6),
(121, '长春市', 2, 7),
(122, '吉林市', 2, 7),
(123, '四平市', 2, 7),
(124, '辽源市', 2, 7),
(125, '通化市', 2, 7),
(126, '白山市', 2, 7),
(127, '松原市', 2, 7),
(128, '白城市', 2, 7),
(129, '延边朝鲜族自治州', 2, 7),
(130, '哈尔滨市', 2, 8),
(131, '齐齐哈尔市', 2, 8),
(132, '鸡西市', 2, 8),
(133, '鹤岗市', 2, 8),
(134, '双鸭山市', 2, 8),
(135, '大庆市', 2, 8),
(136, '伊春市', 2, 8),
(137, '佳木斯市', 2, 8),
(138, '七台河市', 2, 8),
(139, '牡丹江市', 2, 8),
(140, '黑河市', 2, 8),
(141, '绥化市', 2, 8),
(142, '大兴安岭地区', 2, 8),
(143, '黄浦区', 2, 9),
(144, '卢湾区', 2, 9),
(145, '徐汇区', 2, 9),
(146, '长宁区', 2, 9),
(147, '静安区', 2, 9),
(148, '普陀区', 2, 9),
(149, '闸北区', 2, 9),
(150, '虹口区', 2, 9),
(151, '杨浦区', 2, 9),
(152, '闵行区', 2, 9),
(153, '宝山区', 2, 9),
(154, '嘉定区', 2, 9),
(155, '浦东新区', 2, 9),
(156, '金山区', 2, 9),
(157, '松江区', 2, 9),
(158, '青浦区', 2, 9),
(159, '南汇区', 2, 9),
(160, '奉贤区', 2, 9),
(161, '崇明县', 2, 9),
(162, '南京市', 2, 10),
(163, '无锡市', 2, 10),
(164, '徐州市', 2, 10),
(165, '常州市', 2, 10),
(166, '苏州市', 2, 10),
(167, '南通市', 2, 10),
(168, '连云港市', 2, 10),
(169, '淮安市', 2, 10),
(170, '盐城市', 2, 10),
(171, '扬州市', 2, 10),
(172, '镇江市', 2, 10),
(173, '泰州市', 2, 10),
(174, '宿迁市', 2, 10),
(175, '杭州市', 2, 11),
(176, '宁波市', 2, 11),
(177, '温州市', 2, 11),
(178, '嘉兴市', 2, 11),
(179, '湖州市', 2, 11),
(180, '绍兴市', 2, 11),
(181, '舟山市', 2, 11),
(182, '衢州市', 2, 11),
(183, '金华市', 2, 11),
(184, '台州市', 2, 11),
(185, '丽水市', 2, 11),
(186, '合肥市', 2, 12),
(187, '芜湖市', 2, 12),
(188, '蚌埠市', 2, 12),
(189, '淮南市', 2, 12),
(190, '马鞍山市', 2, 12),
(191, '淮北市', 2, 12),
(192, '铜陵市', 2, 12),
(193, '安庆市', 2, 12),
(194, '黄山市', 2, 12),
(195, '滁州市', 2, 12),
(196, '阜阳市', 2, 12),
(197, '宿州市', 2, 12),
(198, '巢湖市', 2, 12),
(199, '六安市', 2, 12),
(200, '亳州市', 2, 12),
(201, '池州市', 2, 12),
(202, '宣城市', 2, 12),
(203, '福州市', 2, 13),
(204, '厦门市', 2, 13),
(205, '莆田市', 2, 13),
(206, '三明市', 2, 13),
(207, '泉州市', 2, 13),
(208, '漳州市', 2, 13),
(209, '南平市', 2, 13),
(210, '龙岩市', 2, 13),
(211, '宁德市', 2, 13),
(212, '南昌市', 2, 14),
(213, '景德镇市', 2, 14),
(214, '萍乡市', 2, 14),
(215, '九江市', 2, 14),
(216, '新余市', 2, 14),
(217, '鹰潭市', 2, 14),
(218, '赣州市', 2, 14),
(219, '吉安市', 2, 14),
(220, '宜春市', 2, 14),
(221, '抚州市', 2, 14),
(222, '上饶市', 2, 14),
(223, '济南市', 2, 15),
(224, '青岛市', 2, 15),
(225, '淄博市', 2, 15),
(226, '枣庄市', 2, 15),
(227, '东营市', 2, 15),
(228, '烟台市', 2, 15),
(229, '潍坊市', 2, 15),
(230, '济宁市', 2, 15),
(231, '泰安市', 2, 15),
(232, '威海市', 2, 15),
(233, '日照市', 2, 15),
(234, '莱芜市', 2, 15),
(235, '临沂市', 2, 15),
(236, '德州市', 2, 15),
(237, '聊城市', 2, 15),
(238, '滨州市', 2, 15),
(239, '菏泽市', 2, 15),
(240, '郑州市', 2, 16),
(241, '开封市', 2, 16),
(242, '洛阳市', 2, 16),
(243, '平顶山市', 2, 16),
(244, '安阳市', 2, 16),
(245, '鹤壁市', 2, 16),
(246, '新乡市', 2, 16),
(247, '焦作市', 2, 16),
(248, '濮阳市', 2, 16),
(249, '许昌市', 2, 16),
(250, '漯河市', 2, 16),
(251, '三门峡市', 2, 16),
(252, '南阳市', 2, 16),
(253, '商丘市', 2, 16),
(254, '信阳市', 2, 16),
(255, '周口市', 2, 16),
(256, '驻马店市', 2, 16),
(257, '济源市', 2, 16),
(258, '武汉市', 2, 17),
(259, '黄石市', 2, 17),
(260, '十堰市', 2, 17),
(261, '宜昌市', 2, 17),
(262, '襄樊市', 2, 17),
(263, '鄂州市', 2, 17),
(264, '荆门市', 2, 17),
(265, '孝感市', 2, 17),
(266, '荆州市', 2, 17),
(267, '黄冈市', 2, 17),
(268, '咸宁市', 2, 17),
(269, '随州市', 2, 17),
(270, '恩施土家族苗族自治州', 2, 17),
(271, '仙桃市', 2, 17),
(272, '潜江市', 2, 17),
(273, '天门市', 2, 17),
(274, '神农架林区', 2, 17),
(275, '长沙市', 2, 18),
(276, '株洲市', 2, 18),
(277, '湘潭市', 2, 18),
(278, '衡阳市', 2, 18),
(279, '邵阳市', 2, 18),
(280, '岳阳市', 2, 18),
(281, '常德市', 2, 18),
(282, '张家界市', 2, 18),
(283, '益阳市', 2, 18),
(284, '郴州市', 2, 18),
(285, '永州市', 2, 18),
(286, '怀化市', 2, 18),
(287, '娄底市', 2, 18),
(288, '湘西土家族苗族自治州', 2, 18),
(289, '广州市', 2, 19),
(290, '韶关市', 2, 19),
(291, '深圳市', 2, 19),
(292, '珠海市', 2, 19),
(293, '汕头市', 2, 19),
(294, '佛山市', 2, 19),
(295, '江门市', 2, 19),
(296, '湛江市', 2, 19),
(297, '茂名市', 2, 19),
(298, '肇庆市', 2, 19),
(299, '惠州市', 2, 19),
(300, '梅州市', 2, 19),
(301, '汕尾市', 2, 19),
(302, '河源市', 2, 19),
(303, '阳江市', 2, 19),
(304, '清远市', 2, 19),
(305, '东莞市', 2, 19),
(306, '中山市', 2, 19),
(307, '潮州市', 2, 19),
(308, '揭阳市', 2, 19),
(309, '云浮市', 2, 19),
(310, '南宁市', 2, 20),
(311, '柳州市', 2, 20),
(312, '桂林市', 2, 20),
(313, '梧州市', 2, 20),
(314, '北海市', 2, 20),
(315, '防城港市', 2, 20),
(316, '钦州市', 2, 20),
(317, '贵港市', 2, 20),
(318, '玉林市', 2, 20),
(319, '百色市', 2, 20),
(320, '贺州市', 2, 20),
(321, '河池市', 2, 20),
(322, '来宾市', 2, 20),
(323, '崇左市', 2, 20),
(324, '海口市', 2, 21),
(325, '三亚市', 2, 21),
(326, '五指山市', 2, 21),
(327, '琼海市', 2, 21),
(328, '儋州市', 2, 21),
(329, '文昌市', 2, 21),
(330, '万宁市', 2, 21),
(331, '东方市', 2, 21),
(332, '定安县', 2, 21),
(333, '屯昌县', 2, 21),
(334, '澄迈县', 2, 21),
(335, '临高县', 2, 21),
(336, '白沙黎族自治县', 2, 21),
(337, '昌江黎族自治县', 2, 21),
(338, '乐东黎族自治县', 2, 21),
(339, '陵水黎族自治县', 2, 21),
(340, '保亭黎族苗族自治县', 2, 21),
(341, '琼中黎族苗族自治县', 2, 21),
(342, '西沙群岛', 2, 21),
(343, '南沙群岛', 2, 21),
(344, '中沙群岛的岛礁及其海域', 2, 21),
(345, '万州区', 2, 22),
(346, '涪陵区', 2, 22),
(347, '渝中区', 2, 22),
(348, '大渡口区', 2, 22),
(349, '江北区', 2, 22),
(350, '沙坪坝区', 2, 22),
(351, '九龙坡区', 2, 22),
(352, '南岸区', 2, 22),
(353, '北碚区', 2, 22),
(354, '双桥区', 2, 22),
(355, '万盛区', 2, 22),
(356, '渝北区', 2, 22),
(357, '巴南区', 2, 22),
(358, '黔江区', 2, 22),
(359, '长寿区', 2, 22),
(360, '綦江县', 2, 22),
(361, '潼南县', 2, 22),
(362, '铜梁县', 2, 22),
(363, '大足县', 2, 22),
(364, '荣昌县', 2, 22),
(365, '璧山县', 2, 22),
(366, '梁平县', 2, 22),
(367, '城口县', 2, 22),
(368, '丰都县', 2, 22),
(369, '垫江县', 2, 22),
(370, '武隆县', 2, 22),
(371, '忠县', 2, 22),
(372, '开县', 2, 22),
(373, '云阳县', 2, 22),
(374, '奉节县', 2, 22),
(375, '巫山县', 2, 22),
(376, '巫溪县', 2, 22),
(377, '石柱土家族自治县', 2, 22),
(378, '秀山土家族苗族自治县', 2, 22),
(379, '酉阳土家族苗族自治县', 2, 22),
(380, '彭水苗族土家族自治县', 2, 22),
(381, '江津市', 2, 22),
(382, '合川市', 2, 22),
(383, '永川市', 2, 22),
(384, '南川市', 2, 22),
(385, '成都市', 2, 23),
(386, '自贡市', 2, 23),
(387, '攀枝花市', 2, 23),
(388, '泸州市', 2, 23),
(389, '德阳市', 2, 23),
(390, '绵阳市', 2, 23),
(391, '广元市', 2, 23),
(392, '遂宁市', 2, 23),
(393, '内江市', 2, 23),
(394, '乐山市', 2, 23),
(395, '南充市', 2, 23),
(396, '眉山市', 2, 23),
(397, '宜宾市', 2, 23),
(398, '广安市', 2, 23),
(399, '达州市', 2, 23),
(400, '雅安市', 2, 23),
(401, '巴中市', 2, 23),
(402, '资阳市', 2, 23),
(403, '阿坝藏族羌族自治州', 2, 23),
(404, '甘孜藏族自治州', 2, 23),
(405, '凉山彝族自治州', 2, 23),
(406, '贵阳市', 2, 24),
(407, '六盘水市', 2, 24),
(408, '遵义市', 2, 24),
(409, '安顺市', 2, 24),
(410, '铜仁地区', 2, 24),
(411, '黔西南布依族苗族自治州', 2, 24),
(412, '毕节地区', 2, 24),
(413, '黔东南苗族侗族自治州', 2, 24),
(414, '黔南布依族苗族自治州', 2, 24),
(415, '昆明市', 2, 25),
(416, '曲靖市', 2, 25),
(417, '玉溪市', 2, 25),
(418, '保山市', 2, 25),
(419, '昭通市', 2, 25),
(420, '丽江市', 2, 25),
(421, '思茅市', 2, 25),
(422, '临沧市', 2, 25),
(423, '楚雄彝族自治州', 2, 25),
(424, '红河哈尼族彝族自治州', 2, 25),
(425, '文山壮族苗族自治州', 2, 25),
(426, '西双版纳傣族自治州', 2, 25),
(427, '大理白族自治州', 2, 25),
(428, '德宏傣族景颇族自治州', 2, 25),
(429, '怒江傈僳族自治州', 2, 25),
(430, '迪庆藏族自治州', 2, 25),
(431, '拉萨市', 2, 26),
(432, '昌都地区', 2, 26),
(433, '山南地区', 2, 26),
(434, '日喀则地区', 2, 26),
(435, '那曲地区', 2, 26),
(436, '阿里地区', 2, 26),
(437, '林芝地区', 2, 26),
(438, '西安市', 2, 27),
(439, '铜川市', 2, 27),
(440, '宝鸡市', 2, 27),
(441, '咸阳市', 2, 27),
(442, '渭南市', 2, 27),
(443, '延安市', 2, 27),
(444, '汉中市', 2, 27),
(445, '榆林市', 2, 27),
(446, '安康市', 2, 27),
(447, '商洛市', 2, 27),
(448, '兰州市', 2, 28),
(449, '嘉峪关市', 2, 28),
(450, '金昌市', 2, 28),
(451, '白银市', 2, 28),
(452, '天水市', 2, 28),
(453, '武威市', 2, 28),
(454, '张掖市', 2, 28),
(455, '平凉市', 2, 28),
(456, '酒泉市', 2, 28),
(457, '庆阳市', 2, 28),
(458, '定西市', 2, 28),
(459, '陇南市', 2, 28),
(460, '临夏回族自治州', 2, 28),
(461, '甘南藏族自治州', 2, 28),
(462, '西宁市', 2, 29),
(463, '海东地区', 2, 29),
(464, '海北藏族自治州', 2, 29),
(465, '黄南藏族自治州', 2, 29),
(466, '海南藏族自治州', 2, 29),
(467, '果洛藏族自治州', 2, 29),
(468, '玉树藏族自治州', 2, 29),
(469, '海西蒙古族藏族自治州', 2, 29),
(470, '银川市', 2, 30),
(471, '石嘴山市', 2, 30),
(472, '吴忠市', 2, 30),
(473, '固原市', 2, 30),
(474, '中卫市', 2, 30),
(475, '乌鲁木齐市', 2, 31),
(476, '克拉玛依市', 2, 31),
(477, '吐鲁番地区', 2, 31),
(478, '哈密地区', 2, 31),
(479, '昌吉回族自治州', 2, 31),
(480, '博尔塔拉蒙古自治州', 2, 31),
(481, '巴音郭楞蒙古自治州', 2, 31),
(482, '阿克苏地区', 2, 31),
(483, '克孜勒苏柯尔克孜自治州', 2, 31),
(484, '喀什地区', 2, 31),
(485, '和田地区', 2, 31),
(486, '伊犁哈萨克自治州', 2, 31),
(487, '塔城地区', 2, 31),
(488, '阿勒泰地区', 2, 31),
(489, '石河子市', 2, 31),
(490, '阿拉尔市', 2, 31),
(491, '图木舒克市', 2, 31),
(492, '五家渠市', 2, 31),
(493, '台北市', 2, 32),
(494, '高雄市', 2, 32),
(495, '基隆市', 2, 32),
(496, '台中市', 2, 32),
(497, '台南市', 2, 32),
(498, '新竹市', 2, 32),
(499, '嘉义市', 2, 32),
(500, '台北县', 2, 32),
(501, '宜兰县', 2, 32),
(502, '桃园县', 2, 32),
(503, '新竹县', 2, 32),
(504, '苗栗县', 2, 32),
(505, '台中县', 2, 32),
(506, '彰化县', 2, 32),
(507, '南投县', 2, 32),
(508, '云林县', 2, 32),
(509, '嘉义县', 2, 32),
(510, '台南县', 2, 32),
(511, '高雄县', 2, 32),
(512, '屏东县', 2, 32),
(513, '澎湖县', 2, 32),
(514, '台东县', 2, 32),
(515, '花莲县', 2, 32),
(516, '中西区', 2, 33),
(517, '东区', 2, 33),
(518, '九龙城区', 2, 33),
(519, '观塘区', 2, 33),
(520, '南区', 2, 33),
(521, '深水埗区', 2, 33),
(522, '黄大仙区', 2, 33),
(523, '湾仔区', 2, 33);

-- --------------------------------------------------------

--
-- 表的结构 `sent_file`
--

DROP TABLE IF EXISTS `sent_file`;
CREATE TABLE `sent_file` (
  `id` int(10) UNSIGNED NOT NULL COMMENT '文件ID',
  `name` varchar(300) NOT NULL DEFAULT '' COMMENT '原始文件名',
  `savename` varchar(100) NOT NULL DEFAULT '' COMMENT '保存名称',
  `savepath` varchar(100) NOT NULL DEFAULT '' COMMENT '文件保存路径',
  `ext` char(5) NOT NULL DEFAULT '' COMMENT '文件后缀',
  `mime` char(40) NOT NULL DEFAULT '' COMMENT '文件mime类型',
  `size` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '文件大小',
  `md5` char(32) NOT NULL DEFAULT '' COMMENT '文件md5',
  `sha1` char(40) NOT NULL DEFAULT '' COMMENT '文件 sha1编码',
  `location` tinyint(3) UNSIGNED NOT NULL DEFAULT '0' COMMENT '文件保存位置',
  `url` varchar(255) NOT NULL DEFAULT '' COMMENT '远程地址',
  `create_time` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '上传时间'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='文件表';

-- --------------------------------------------------------

--
-- 表的结构 `sent_form`
--

DROP TABLE IF EXISTS `sent_form`;
CREATE TABLE `sent_form` (
  `id` int(10) UNSIGNED NOT NULL COMMENT '文件ID',
  `title` varchar(200) NOT NULL DEFAULT '' COMMENT '表单名称',
  `name` varchar(100) NOT NULL DEFAULT '' COMMENT '表单标识',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态',
  `create_time` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '更新时间'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='自定义表单';

-- --------------------------------------------------------
--
-- 表的结构 `sent_form_attr`
--

DROP TABLE IF EXISTS `sent_form_attr`;
CREATE TABLE `sent_form_attr` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(30) NOT NULL DEFAULT '' COMMENT '字段名',
  `title` varchar(100) NOT NULL DEFAULT '' COMMENT '字段注释',
  `length` varchar(100) NOT NULL DEFAULT '' COMMENT '字段定义',
  `type` varchar(20) NOT NULL DEFAULT '' COMMENT '数据类型',
  `value` varchar(100) NOT NULL DEFAULT '' COMMENT '字段默认值',
  `remark` varchar(100) NOT NULL DEFAULT '' COMMENT '备注',
  `extra` varchar(255) NOT NULL DEFAULT '' COMMENT '参数',
  `form_id` int(11) NOT NULL COMMENT '所属模型',
  `is_show` int(11) NOT NULL DEFAULT '1' COMMENT '是否显示',
  `is_must` int(11) NOT NULL DEFAULT '0' COMMENT '是否必填',
  `group_id` int(11) NOT NULL DEFAULT '1' COMMENT '分组',
  `sort` int(11) NOT NULL DEFAULT '0' COMMENT '排序',
  `status` tinyint(2) NOT NULL DEFAULT '0' COMMENT '状态',
  `update_time` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '更新时间',
  `create_time` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '创建时间',
  `validate_rule` varchar(255) NOT NULL DEFAULT '',
  `validate_time` tinyint(1) UNSIGNED NOT NULL DEFAULT '0',
  `error_info` varchar(100) NOT NULL DEFAULT '',
  `validate_type` varchar(25) NOT NULL DEFAULT '',
  `auto_rule` varchar(100) NOT NULL DEFAULT '',
  `auto_time` tinyint(1) UNSIGNED NOT NULL DEFAULT '0',
  `auto_type` varchar(25) NOT NULL DEFAULT ''
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='模型属性表';

-- --------------------------------------------------------
--
-- 表的结构 `sent_hooks`
--

DROP TABLE IF EXISTS `sent_hooks`;
CREATE TABLE `sent_hooks` (
  `id` int(10) UNSIGNED NOT NULL COMMENT '主键',
  `name` varchar(40) NOT NULL DEFAULT '' COMMENT '钩子名称',
  `description` text COMMENT '描述',
  `type` tinyint(1) UNSIGNED NOT NULL DEFAULT '1' COMMENT '类型',
  `update_time` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '更新时间',
  `addons` varchar(255) NOT NULL DEFAULT '' COMMENT '钩子挂载的插件 ''，''分割',
  `status` tinyint(1) UNSIGNED NOT NULL DEFAULT '1'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `sent_hooks`
--

INSERT INTO `sent_hooks` (`id`, `name`, `description`, `type`, `update_time`, `addons`, `status`) VALUES
(1, 'pageHeader', '页面header钩子，一般用于加载插件CSS文件和代码', 1, 0, '', 1),
(2, 'pageFooter', '页面footer钩子，一般用于加载插件JS文件和JS代码', 1, 0, '', 1),
(3, 'AdminIndex', '首页小格子个性化显示', 1, 1382596073, 'Sitestat,Devteam,Systeminfo', 1);

-- --------------------------------------------------------

--
-- 表的结构 `sent_link`
--

DROP TABLE IF EXISTS `sent_link`;
CREATE TABLE `sent_link` (
  `id` int(5) NOT NULL COMMENT '标识ID',
  `ftype` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0:友情链接 1:合作单位',
  `title` varchar(30) NOT NULL DEFAULT '' COMMENT '标题',
  `url` varchar(150) NOT NULL DEFAULT '' COMMENT '链接地址',
  `cover_id` int(11) NOT NULL DEFAULT '0' COMMENT '封面图片ID',
  `descrip` varchar(255) NOT NULL DEFAULT '' COMMENT '备注信息',
  `sort` int(10) NOT NULL DEFAULT '0' COMMENT '排序',
  `hits` tinyint(7) NOT NULL DEFAULT '0' COMMENT '点击率',
  `update_time` int(10) NOT NULL DEFAULT '0' COMMENT '更新时间',
  `uid` int(7) NOT NULL DEFAULT '0' COMMENT '用户ID ',
  `status` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `sent_link`
--

INSERT INTO `sent_link` (`id`, `ftype`, `title`, `url`, `cover_id`, `descrip`, `sort`, `hits`, `update_time`, `uid`, `status`) VALUES
(1, 1, '腾速科技', 'http://diao.info', 0, '', 0, 0, 1462496026, 0, 1);

-- --------------------------------------------------------

--
-- 表的结构 `sent_member`
--

DROP TABLE IF EXISTS `sent_member`;
CREATE TABLE `sent_member` (
  `uid` int(10) UNSIGNED NOT NULL COMMENT '用户ID',
  `username` varchar(32) NOT NULL DEFAULT '' COMMENT '用户名',
  `password` varchar(64) NOT NULL DEFAULT '' COMMENT '用户密码',
  `nickname` char(16) NOT NULL DEFAULT '' COMMENT '昵称',
  `email` varchar(100) DEFAULT NULL COMMENT '邮箱地址',
  `mobile` varchar(20) DEFAULT NULL COMMENT '手机号码',
  `sex` tinyint(3) UNSIGNED NOT NULL DEFAULT '0' COMMENT '性别',
  `birthday` date NOT NULL DEFAULT '1917-01-01' COMMENT '生日',
  `qq` char(10) NOT NULL DEFAULT '' COMMENT 'qq号',
  `score` mediumint(8) NOT NULL DEFAULT '0' COMMENT '用户积分',
  `signature` text COMMENT '用户签名',
  `pos_province` int(11) DEFAULT '0' COMMENT '用户所在省份',
  `pos_city` int(11) DEFAULT '0' COMMENT '用户所在城市',
  `pos_district` int(11) DEFAULT '0' COMMENT '用户所在县城',
  `pos_community` int(11) DEFAULT '0' COMMENT '用户所在区域',
  `salt` varchar(255) NOT NULL DEFAULT '' COMMENT '密码盐值',
  `login` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '登录次数',
  `reg_ip` bigint(20) NOT NULL DEFAULT '0' COMMENT '注册IP',
  `reg_time` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '注册时间',
  `last_login_ip` bigint(20) NOT NULL DEFAULT '0' COMMENT '最后登录IP',
  `last_login_time` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '最后登录时间',
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '会员状态'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='会员表';

-- --------------------------------------------------------

--
-- 表的结构 `sent_member_extend`
--

DROP TABLE IF EXISTS `sent_member_extend`;
CREATE TABLE `sent_member_extend` (
  `uid` int(11) NOT NULL COMMENT '用户UID',
  `education` int(10) DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `sent_member_extend_group`
--

DROP TABLE IF EXISTS `sent_member_extend_group`;
CREATE TABLE `sent_member_extend_group` (
  `id` int(11) NOT NULL COMMENT '自增主键',
  `name` varchar(50) NOT NULL COMMENT '分组数据表',
  `profile_name` varchar(25) NOT NULL COMMENT '扩展分组名称',
  `createTime` int(11) NOT NULL COMMENT '创建时间',
  `sort` int(11) NOT NULL COMMENT '排序',
  `visiable` tinyint(4) NOT NULL DEFAULT '1' COMMENT '是否可见，1可见，0不可见',
  `status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '字段状态'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `sent_member_extend_group`
--

INSERT INTO `sent_member_extend_group` (`id`, `name`, `profile_name`, `createTime`, `sort`, `visiable`, `status`) VALUES
(1, 'member_extend', '个人资料', 1403847366, 0, 1, 1);

-- --------------------------------------------------------

--
-- 表的结构 `sent_member_extend_setting`
--

DROP TABLE IF EXISTS `sent_member_extend_setting`;
CREATE TABLE `sent_member_extend_setting` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(30) NOT NULL DEFAULT '' COMMENT '字段名',
  `title` varchar(100) NOT NULL DEFAULT '' COMMENT '字段注释',
  `length` varchar(100) NOT NULL DEFAULT '' COMMENT '字段定义',
  `type` varchar(20) NOT NULL DEFAULT '' COMMENT '数据类型',
  `value` varchar(100) NOT NULL DEFAULT '' COMMENT '字段默认值',
  `remark` varchar(100) NOT NULL DEFAULT '' COMMENT '备注',
  `is_show` tinyint(1) UNSIGNED NOT NULL DEFAULT '1' COMMENT '是否显示',
  `extra` varchar(255) NOT NULL DEFAULT '' COMMENT '参数',
  `is_must` tinyint(1) UNSIGNED NOT NULL DEFAULT '0' COMMENT '是否必填',
  `status` tinyint(2) NOT NULL DEFAULT '0' COMMENT '状态',
  `update_time` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '更新时间',
  `create_time` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '创建时间'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='会员字段表';

--
-- 转存表中的数据 `sent_member_extend_setting`
--

INSERT INTO `sent_member_extend_setting` (`id`, `name`, `title`, `length`, `type`, `value`, `remark`, `is_show`, `extra`, `is_must`, `status`, `update_time`, `create_time`) VALUES
(1, 'education', '学历', '10', 'select', '', '', 1, '1:小学\r\n2:初中\r\n3:高中', 0, 1, 1455930923, 1455930787);

-- --------------------------------------------------------

--
-- 表的结构 `sent_menu`
--

DROP TABLE IF EXISTS `sent_menu`;
CREATE TABLE `sent_menu` (
  `id` int(10) UNSIGNED NOT NULL COMMENT '文档ID',
  `title` varchar(50) NOT NULL DEFAULT '' COMMENT '标题',
  `type` varchar(10) NOT NULL DEFAULT 'admin' COMMENT '菜单类别（admin后台，user会员中心）',
  `icon` varchar(20) NOT NULL DEFAULT '' COMMENT '分类图标',
  `pid` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '上级分类ID',
  `sort` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '排序（同级有效）',
  `url` char(255) NOT NULL DEFAULT '' COMMENT '链接地址',
  `hide` tinyint(1) UNSIGNED NOT NULL DEFAULT '0' COMMENT '是否隐藏',
  `tip` varchar(255) NOT NULL DEFAULT '' COMMENT '提示',
  `group` varchar(50) DEFAULT '' COMMENT '分组',
  `is_dev` tinyint(1) UNSIGNED NOT NULL DEFAULT '0' COMMENT '是否仅开发者模式可见',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `sent_menu`
--

INSERT INTO `sent_menu` (`id`, `title`, `type`, `icon`, `pid`, `sort`, `url`, `hide`, `tip`, `group`, `is_dev`, `status`) VALUES
(1, '首页', 'admin', 'home', 0, 0, 'admin/index/index', 0, '', '', 0, 0),
(2, '系统', 'admin', 'laptop', 0, 11, 'admin/config/group', 0, '', '', 0, 0),
(3, '内容', 'admin', 'list', 0, 22, 'admin/category/index', 0, '', '', 0, 0),
(4, '会员', 'admin', 'user', 0, 33, 'admin/user/index', 0, '', '', 0, 0),
(5, '运营', 'admin', 'th', 0, 44, 'admin/link/index', 0, '', '', 0, 0),
(6, '扩展', 'admin', 'tags', 0, 55, 'admin/addons/index', 0, '', '', 0, 0),
(21, '友链管理', 'admin', 'link', 5, 0, 'admin/link/index', 0, '', '运营管理', 0, 0),
(7, '更新缓存', 'admin', 'refresh', 1, 0, 'admin/index/clear', 0, '', '后台首页', 0, 0),
(8, '配置管理', 'admin', 'cog', 2, 0, 'admin/config/group', 0, '', '系统配置', 0, 0),
(9, '菜单管理', 'admin', 'book', 2, 0, 'admin/menu/index', 0, '', '系统配置', 0, 0),
(10, '导航管理', 'admin', 'map-marker', 2, 0, 'admin/channel/index', 0, '', '系统配置', 0, 0),
(11, '数据备份', 'admin', 'exchange', 2, 0, 'admin/database/index?type=export', 0, '', '数据库管理', 0, 0),
(12, '数据恢复', 'admin', 'table', 2, 0, 'admin/database/index?type=import', 0, '', '数据库管理', 0, 0),
(13, 'SEO设置', 'admin', 'anchor', 2, 0, 'admin/seo/index', 0, '', '优化设置', 0, 0),
(14, '栏目管理', 'admin', 'list-ol', 3, 0, 'admin/category/index', 0, '', '内容配置', 0, 0),
(15, '模型管理', 'admin', 'th-list', 3, 0, 'admin/model/index', 0, '', '内容配置', 0, 0),
(16, '用户列表', 'admin', 'user', 4, 0, 'admin/user/index', 0, '', '用户管理', 0, 0),
(17, '用户组表', 'admin', 'users', 4, 0, 'admin/group/index', 0, '', '用户管理', 0, 0),
(18, '权限列表', 'admin', 'paw', 4, 0, 'admin/group/access', 0, '', '用户管理', 0, 0),
(19, '行为列表', 'admin', 'file-text', 4, 0, 'admin/action/index', 0, '', '行为管理', 0, 0),
(20, '行为日志', 'admin', 'clipboard', 4, 0, 'admin/action/log', 0, '', '行为管理', 0, 0),
(22, '广告管理', 'admin', 'cc', 5, 0, 'admin/ad/index', 0, '', '运营管理', 0, 0),
(23, '插件列表', 'admin', 'usb', 6, 0, 'admin/addons/index', 0, '', '插件管理', 0, 0),
(24, '钩子列表', 'admin', 'code', 6, 0, 'admin/addons/hooks', 0, '', '插件管理', 0, 0),
(25, '自定义表单', 'admin', 'object-group', 5, 0, 'admin/form/index', 0, '', '运营管理', 0, 0),
(26, '伪静态规则', 'admin', 'magnet', 2, 0, 'admin/seo/rewrite', 0, '', '优化设置', 0, 0),
(27, '主题管理', 'admin', 'heartbeat', 2, 0, 'admin/config/themes', 0, '', '系统配置', 0, 0),
(28, '接口', 'admin', 'database', 0, 66, 'admin/client/index', 0, '', '', 0, 0),
(29, '客户端列表', 'admin', 'inbox', 28, 0, 'admin/client/index', 0, '', '客户端管理', 0, 0);

-- --------------------------------------------------------

--
-- 表的结构 `sent_model`
--

DROP TABLE IF EXISTS `sent_model`;
CREATE TABLE `sent_model` (
  `id` int(10) UNSIGNED NOT NULL COMMENT '模型ID',
  `name` char(30) NOT NULL DEFAULT '' COMMENT '模型标识',
  `title` char(30) NOT NULL DEFAULT '' COMMENT '模型名称',
  `icon` varchar(20) NOT NULL COMMENT '模型图标',
  `is_doc` int(2) NOT NULL DEFAULT '1' COMMENT '是否为文档模型',
  `attribute_group` varchar(255) NOT NULL DEFAULT '1:基础' COMMENT '字段分组',
  `list_grid` text COMMENT '列表定义',
  `list_row` smallint(2) UNSIGNED NOT NULL DEFAULT '25' COMMENT '列表数据长度',
  `search_key` varchar(50) DEFAULT '' COMMENT '默认搜索字段',
  `search_list` varchar(255) DEFAULT '' COMMENT '高级搜索的字段',
  `template_list` varchar(255) DEFAULT '' COMMENT '列表显示模板',
  `template_add` varchar(255) DEFAULT '' COMMENT '新增模板',
  `template_edit` varchar(255) DEFAULT '' COMMENT '编辑模板',
  `status` tinyint(3) UNSIGNED NOT NULL DEFAULT '0' COMMENT '状态',
  `create_time` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '更新时间'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='文档模型表';

-- --------------------------------------------------------

--
-- 表的结构 `sent_picture`
--

DROP TABLE IF EXISTS `sent_picture`;
CREATE TABLE `sent_picture` (
  `id` int(10) UNSIGNED NOT NULL COMMENT '主键id自增',
  `path` varchar(255) NOT NULL DEFAULT '' COMMENT '路径',
  `url` varchar(255) NOT NULL DEFAULT '' COMMENT '图片链接',
  `md5` char(32) NOT NULL DEFAULT '' COMMENT '文件md5',
  `sha1` char(40) NOT NULL DEFAULT '' COMMENT '文件 sha1编码',
  `status` tinyint(2) NOT NULL DEFAULT '0' COMMENT '状态',
  `create_time` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '创建时间'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `sent_rewrite`
--

DROP TABLE IF EXISTS `sent_rewrite`;
CREATE TABLE `sent_rewrite` (
  `id` int(10) UNSIGNED NOT NULL COMMENT '主键id自增',
  `rule` varchar(255) NOT NULL DEFAULT '' COMMENT '规则',
  `url` varchar(255) NOT NULL DEFAULT '' COMMENT 'url',
  `create_time` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '更新时间'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='伪静态表';

-- --------------------------------------------------------

--
-- 表的结构 `sent_seo_rule`
--

DROP TABLE IF EXISTS `sent_seo_rule`;
CREATE TABLE `sent_seo_rule` (
  `id` int(11) UNSIGNED NOT NULL COMMENT '唯一标识',
  `title` text NOT NULL COMMENT '规则标题',
  `app` varchar(40) DEFAULT NULL,
  `controller` varchar(40) DEFAULT NULL,
  `action` varchar(40) DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '状态',
  `seo_title` text NOT NULL COMMENT 'SEO标题',
  `seo_keywords` text NOT NULL COMMENT 'SEO关键词',
  `seo_description` text NOT NULL COMMENT 'SEO描述',
  `sort` int(11) NOT NULL DEFAULT '0' COMMENT '排序'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `sent_seo_rule`
--

INSERT INTO `sent_seo_rule` (`id`, `title`, `app`, `controller`, `action`, `status`, `seo_title`, `seo_keywords`, `seo_description`, `sort`) VALUES
(1, '整站标题', '', '', '', 1, 'YihaoCMS网站管理系统', 'YihaoCMS网站管理系统', 'YihaoCMS网站管理系统', 7);

-- --------------------------------------------------------

--
-- 表的结构 `sent_sync_login`
--

DROP TABLE IF EXISTS `sent_sync_login`;
CREATE TABLE `sent_sync_login` (
  `uid` int(11) NOT NULL,
  `openid` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL,
  `access_token` varchar(255) NOT NULL,
  `refresh_token` varchar(255) NOT NULL,
  `status` tinyint(4) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `sent_action`
--
ALTER TABLE `sent_action`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sent_action_log`
--
ALTER TABLE `sent_action_log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `action_ip_ix` (`action_ip`),
  ADD KEY `action_id_ix` (`action_id`),
  ADD KEY `user_id_ix` (`user_id`);

--
-- Indexes for table `sent_ad`
--
ALTER TABLE `sent_ad`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sent_addons`
--
ALTER TABLE `sent_addons`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sent_ad_place`
--
ALTER TABLE `sent_ad_place`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sent_attachment`
--
ALTER TABLE `sent_attachment`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_record_status` (`record_id`,`status`);

--
-- Indexes for table `sent_attribute`
--
ALTER TABLE `sent_attribute`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sent_auth_extend`
--
ALTER TABLE `sent_auth_extend`
  ADD UNIQUE KEY `group_extend_type` (`group_id`,`extend_id`,`type`),
  ADD KEY `uid` (`group_id`),
  ADD KEY `group_id` (`extend_id`);

--
-- Indexes for table `sent_auth_group`
--
ALTER TABLE `sent_auth_group`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sent_auth_group_access`
--
ALTER TABLE `sent_auth_group_access`
  ADD UNIQUE KEY `uid_group_id` (`uid`,`group_id`),
  ADD KEY `uid` (`uid`),
  ADD KEY `group_id` (`group_id`);

--
-- Indexes for table `sent_auth_rule`
--
ALTER TABLE `sent_auth_rule`
  ADD PRIMARY KEY (`id`),
  ADD KEY `module` (`module`,`status`,`type`);

--
-- Indexes for table `sent_category`
--
ALTER TABLE `sent_category`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pid` (`pid`);

--
-- Indexes for table `sent_channel`
--
ALTER TABLE `sent_channel`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pid` (`pid`);

--
-- Indexes for table `sent_config`
--
ALTER TABLE `sent_config`
  ADD PRIMARY KEY (`id`),
  ADD KEY `type` (`type`),
  ADD KEY `group` (`group`);
  
--
-- Indexes for table `sent_client`
--
ALTER TABLE `sent_client`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`);

--
-- Indexes for table `sent_district`
--
ALTER TABLE `sent_district`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sent_file`
--
ALTER TABLE `sent_file`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sent_form`
--
ALTER TABLE `sent_form`
  ADD PRIMARY KEY (`id`);
--
-- Indexes for table `sent_attribute`
--
ALTER TABLE `sent_form_attr`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sent_hooks`
--
ALTER TABLE `sent_hooks`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `sent_link`
--
ALTER TABLE `sent_link`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sent_member`
--
ALTER TABLE `sent_member`
  ADD PRIMARY KEY (`uid`),
  ADD KEY `status` (`status`);

--
-- Indexes for table `sent_member_extend`
--
ALTER TABLE `sent_member_extend`
  ADD PRIMARY KEY (`uid`);

--
-- Indexes for table `sent_member_extend_group`
--
ALTER TABLE `sent_member_extend_group`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sent_member_extend_setting`
--
ALTER TABLE `sent_member_extend_setting`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sent_menu`
--
ALTER TABLE `sent_menu`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pid` (`pid`),
  ADD KEY `status` (`status`);

--
-- Indexes for table `sent_model`
--
ALTER TABLE `sent_model`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sent_picture`
--
ALTER TABLE `sent_picture`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sent_rewrite`
--
ALTER TABLE `sent_rewrite`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sent_seo_rule`
--
ALTER TABLE `sent_seo_rule`
  ADD PRIMARY KEY (`id`);

--
-- 在导出的表使用AUTO_INCREMENT
--

--
-- 使用表AUTO_INCREMENT `sent_action`
--
ALTER TABLE `sent_action`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键', AUTO_INCREMENT=13;
--
-- 使用表AUTO_INCREMENT `sent_action_log`
--
ALTER TABLE `sent_action_log`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键', AUTO_INCREMENT=1;
--
-- 使用表AUTO_INCREMENT `sent_ad`
--
ALTER TABLE `sent_ad`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键', AUTO_INCREMENT=6;
--
-- 使用表AUTO_INCREMENT `sent_addons`
--
ALTER TABLE `sent_addons`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键', AUTO_INCREMENT=4;
--
-- 使用表AUTO_INCREMENT `sent_ad_place`
--
ALTER TABLE `sent_ad_place`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键', AUTO_INCREMENT=3;
--
-- 使用表AUTO_INCREMENT `sent_attachment`
--
ALTER TABLE `sent_attachment`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- 使用表AUTO_INCREMENT `sent_attribute`
--
ALTER TABLE `sent_attribute`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
--
-- 使用表AUTO_INCREMENT `sent_auth_group`
--
ALTER TABLE `sent_auth_group`
  MODIFY `id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '用户组id,自增主键', AUTO_INCREMENT=5;
--
-- 使用表AUTO_INCREMENT `sent_auth_rule`
--
ALTER TABLE `sent_auth_rule`
  MODIFY `id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '规则id,自增主键', AUTO_INCREMENT=34;
--
-- 使用表AUTO_INCREMENT `sent_category`
--
ALTER TABLE `sent_category`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '分类ID', AUTO_INCREMENT=8;
--
-- 使用表AUTO_INCREMENT `sent_channel`
--
ALTER TABLE `sent_channel`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '频道ID', AUTO_INCREMENT=21;
--
-- 使用表AUTO_INCREMENT `sent_config`
--
ALTER TABLE `sent_config`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '配置ID', AUTO_INCREMENT=44;
--
-- 使用表AUTO_INCREMENT `sent_client`
--
ALTER TABLE `sent_client`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
--
-- 使用表AUTO_INCREMENT `sent_district`
--
ALTER TABLE `sent_district`
  MODIFY `id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45052;
--
-- 使用表AUTO_INCREMENT `sent_file`
--
ALTER TABLE `sent_file`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '文件ID';
--
-- 使用表AUTO_INCREMENT `sent_form`
--
ALTER TABLE `sent_form`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '标识ID';
--
-- 使用表AUTO_INCREMENT `sent_attribute`
--
ALTER TABLE `sent_form_attr`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
--
-- 使用表AUTO_INCREMENT `sent_hooks`
--
ALTER TABLE `sent_hooks`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键', AUTO_INCREMENT=18;
--
-- 使用表AUTO_INCREMENT `sent_link`
--
ALTER TABLE `sent_link`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT COMMENT '标识ID', AUTO_INCREMENT=21;
--
-- 使用表AUTO_INCREMENT `sent_member`
--
ALTER TABLE `sent_member`
  MODIFY `uid` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '用户ID', AUTO_INCREMENT=1;
--
-- 使用表AUTO_INCREMENT `sent_member_extend_group`
--
ALTER TABLE `sent_member_extend_group`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增主键', AUTO_INCREMENT=2;
--
-- 使用表AUTO_INCREMENT `sent_member_extend_setting`
--
ALTER TABLE `sent_member_extend_setting`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- 使用表AUTO_INCREMENT `sent_menu`
--
ALTER TABLE `sent_menu`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '文档ID', AUTO_INCREMENT=28;
--
-- 使用表AUTO_INCREMENT `sent_model`
--
ALTER TABLE `sent_model`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '模型ID', AUTO_INCREMENT=1;
--
-- 使用表AUTO_INCREMENT `sent_picture`
--
ALTER TABLE `sent_picture`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键id自增';
--
-- 使用表AUTO_INCREMENT `sent_rewrite`
--
ALTER TABLE `sent_rewrite`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键id自增';
--
-- 使用表AUTO_INCREMENT `sent_seo_rule`
--
ALTER TABLE `sent_seo_rule`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '唯一标识', AUTO_INCREMENT=4;
