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
class Form extends Base {
	protected $rule = array(
		'title'   => 'require',
		'name'   => 'require|checkTable|unique:form|/^[a-zA-Z]\w{0,39}$/',
	);
	
	protected $message = array(
		'title.require'   => '字段标题不能为空！',
		'name.checkTable' => '数据库中有此表',
	);
	
	protected $scene = array(
		'add'   => 'title, name',
		'edit'   => 'title'
	);

	protected function checkTable($value, $rule, $data){
		$tablename = 'form_' . strtolower($value);
		$db = new \com\Datatable();
		if (!$db->CheckTable($tablename)) {
			return true;
		}else{
			return false;
		}
	}
}