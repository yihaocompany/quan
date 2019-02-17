<?php
// +----------------------------------------------------------------------
// | YihaoCMS [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.tensent.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: molong <molong@tensent.cn> <http://www.tensent.cn>
// +----------------------------------------------------------------------

namespace app\common\validate;

/**
* 设置模型
*/
class Config extends Base{

	protected $rule = array(
		'name'  =>  'require|unique:config',
		'title' =>  'require',
	);

	protected $message = array(
		'name.require'  =>  '配置标识必须',
		'name.unique'   =>  '配置标识已经存在',
		'title'         =>  '配置名称必须',
	);

	protected $scene = array(
		'add'   => array('name', 'title'),
		'edit'  => array('title')
	);

}