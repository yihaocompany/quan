<?php
return array(
	'title'=> array('name' => 'title', 'title' => '标题', 'type' => 'text', 'length' => 200, 'extra' => '', 'remark' => '标题', 'is_show' => 1, 'is_must' => 1, 'value'=>''),
	'category_id' => array('name' => 'category_id', 'title' => '栏目', 'type' => 'bind', 'length' => 10, 'extra' => 'category', 'remark' => '栏目', 'is_show' => 1, 'is_must' => 1, 'value'=>'0'),
	'uid'    => array('name' => 'uid', 'title' => '用户UID', 'type' => 'num', 'length' => 11, 'extra' => '', 'remark' => '用户UID', 'is_show' => 0, 'is_must' => 1, 'value'=>'0'),
	'cover_id'    => array('name' => 'cover_id', 'title' => '内容封面', 'type' => 'image', 'length' => 10, 'extra' => '', 'remark' => '内容封面', 'is_show' => 1, 'is_must' => 0, 'value'=>''),
	'description' => array('name' => 'description', 'title' => '内容描述', 'type' => 'textarea', 'length' => '', 'extra' => '', 'remark' => '内容描述', 'is_show' => 1, 'is_must' => 0, 'value'=>''),
	'content' => array('name' => 'content', 'title' => '内容', 'type' => 'editor', 'length' => '', 'extra' => '', 'remark' => '内容', 'is_show' => 1, 'is_must' => 0, 'value'=>''),
	'status'      => array('name' => 'status', 'title' => '数据状态', 'type' => 'select', 'length' => 2, 'extra' => "-1:删除\r\n0:禁用\r\n1:正常\r\n2:待审核\r\n3:草稿", 'remark' => '数据状态', 'is_show' => 1, 'is_must' => 1, 'value'=>'1'),
	'is_top'      => array('name' => 'is_top', 'title' => '是否置顶', 'type' => 'bool', 'length' => 2, 'extra' => '', 'remark' => '是否置顶', 'is_show' => 0, 'is_must' => 1, 'value'=>'0'),
	'view' => array('name' => 'view', 'title' => '浏览数量', 'type' => 'num', 'length' => 11, 'extra' => '', 'remark' => '浏览数量', 'is_show' => 0, 'is_must' => 1, 'value'=>'0'),
	'update_time' => array('name' => 'update_time', 'title' => '更新时间', 'type' => 'datetime', 'length' => 11, 'extra' => '', 'remark' => '更新时间', 'is_show' => 0, 'is_must' => 1, 'value'=>'0'),
	'create_time' => array('name' => 'create_time', 'title' => '添加时间', 'type' => 'datetime', 'length' => 11, 'extra' => '', 'remark' => '添加时间', 'is_show' => 0, 'is_must' => 1, 'value'=>'0'),
);