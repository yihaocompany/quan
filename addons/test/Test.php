<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: yangweijie <yangweijiester@gmail.com> <code-tech.diandian.com>
// +----------------------------------------------------------------------

namespace addons\test;
use app\common\controller\Addons;

/**
 * 系统环境信息插件
 * @author thinkphp
 */

class Test extends Addons {

	public $info = array(
		'name'        => 'Test',
		'title'       => '测试',
		'description' => '用于显示一些服务器的信息',
		'status'      => 1,
		'author'      => 'molong',
		'version'     => '0.1',
	);

	public function AdminIndex(){
	}

	public function test(){
		
	}

	public function headertest(){
		
	}

	public function install(){
		return true;
	}


	public function uninstall(){
		return true;
	}
}