<?php
// +----------------------------------------------------------------------
// | YihaoCMS [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://diao.info All rights reserved.
// +----------------------------------------------------------------------
// | Author: molong <molong@tensent.cn> <http://diao.info>
// +----------------------------------------------------------------------

namespace app\common\validate;

/**
 * 设置模型
 */
class Attribute extends Base {

	protected $rule = array(
		'name'   => 'require|/^[a-zA-Z]\w{0,39}$/',
		'title'  => 'require',
		'type'   => 'require',
		'length' => 'requireIn:type,textarea,editor',
		'remark' => 'require',
	);

	protected $message = array(
		'length.requireIn' => '字段长度必须！',
		'length.integer'  => '字段必须为整形',
		'name.require'    => '字段名不能为空！',
		'title.require'   => '字段标题不能为空！',
		'type.require'    => '类型不能为空！',
		'remark.require'  => '描述不能为空！',
	);

	protected $scene = array(
		'add'  => 'name,title,type,remark,length,value',
		'edit' => 'name,title,type,remark,length,value',
	);
}