<?php
// +----------------------------------------------------------------------
// | YihaoCMS [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.tensent.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: molong <molong@tensent.cn> <http://www.tensent.cn>
// +----------------------------------------------------------------------

namespace app\admin\controller;
use app\common\controller\Admin;

/**
 * @title 客户端管理
 */
class Client extends Admin {

	public function _initialize() {
		parent::_initialize();
		$this->model = model('Client');
	}

	/**
	 * @title 客户端列表
	 */
	public function index(){
		$list = $this->model->paginate(25, false, array(
				'query'  => $this->request->param()
			));
		$data = array(
			'list'   => $list,
			'page'   => $list->render()
		);
		$this->assign($data);
		$this->setMeta('客户端列表');
		return $this->fetch();
	}
	
	/**
	 * @title 添加客户端
	 */
	public function add(\think\Request $request){
		if ($this->request->isPost()) {
			$data = $request->param();
			$result = $this->model->validate(true)->save($data);
			if (false !== $result) {
				return $this->success('成功添加', url('client/index'));
			}else{
				return $this->error($this->model->getError());
			}
		}else{
			$info['appid'] = rand_string(10, 1);     //八位数字appid
			$info['appsecret'] = rand_string(32);    //32位数字加字母秘钥
			$data = array(
				'info' => $info
			);
			$this->assign($data);
			$this->setMeta('添加客户端');
			return $this->fetch('add');
		}
	}
	
	/**
	 * @title 编辑客户端
	 */
	public function edit(\think\Request $request){
		if ($this->request->isPost()) {
			$data = $request->param();
			$result = $this->model->validate(true)->save($data, array('id'=>$request->param('id')));
			if (false !== $result) {
				return $this->success('修改添加', url('client/index'));
			}else{
				return $this->error($this->model->getError());
			}
		}else{
			$info = $this->model->where('id', $request->param('id'))->find();
			$data = array(
				'info'    => $info
			);
			$this->assign($data);
			$this->setMeta('编辑客户端');
			return $this->fetch('add');
		}
	}
	
	/**
	 * @title 删除客户端
	 */
	public function del(\think\Request $request){

	}
}