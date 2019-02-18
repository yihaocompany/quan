<?php
// +----------------------------------------------------------------------
// | YihaoCMS [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://diao.info All rights reserved.
// +----------------------------------------------------------------------
// | Author: molong <molong@tensent.cn> <http://diao.info>
// +----------------------------------------------------------------------

namespace app\admin\controller;
use app\common\controller\Admin;

/**
 * @title 自定义表单
 * @description 自定义表单
 */
class Form extends Admin {

	public function _initialize() {
		parent::_initialize();
		$this->model = model('Form');
		$this->Fattr  = model('FormAttr');
		//遍历属性列表
		foreach (get_attribute_type() as $key => $value) {
			$this->attr[$key] = $value[0];
		}
		$this->field     = $this->getField();
	}

	/**
	 * @title 表单列表
	 */
	public function index() {
		$map   = array();
		$order = "id desc";
		$list  = $this->model->where($map)->order($order)->paginate(25, false, array(
				'query'  => $this->request->param()
			));

		$data = array(
			'list' => $list,
			'page' => $list->render(),
		);
		$this->setMeta('自定义表单');
		$this->assign($data);
		return $this->fetch();
	}

	/**
	 * @title 添加表单
	 */
	public function add(\think\Request $request) {
		if ($this->request->isPost()) {
			$result = $this->model->validate('Form')->save($request->post());
			if (false !== $result) {
				return $this->success('添加成功！', url('admin/form/index'));
			} else {
				return $this->error($this->model->getError());
			}
		} else {
			$data = array(
				'keyList' => $this->model->addField,
			);
			$this->assign($data);
			$this->setMeta('添加表单');
			return $this->fetch('public/edit');
		}
	}

	/**
	 * @title 编辑表单
	 */
	public function edit(\think\Request $request) {
		if ($this->request->isPost()) {
			$result = $this->model->validate('Form')->save($request->post(), array('id' => $request->post('id')));
			if (false !== $result) {
				return $this->success('修改成功！', url('admin/form/index'));
			} else {
				return $this->error($this->model->getError());
			}
		} else {
			$info = $this->model->where('id', $request->param('id'))->find();
			$data = array(
				'info'    => $info,
				'keyList' => $this->model->editField,
			);
			$this->assign($data);
			$this->setMeta('编辑表单');
			return $this->fetch('public/edit');
		}
	}

	/**
	 * @title 删除表单
	 */
	public function del() {
		$id     = $this->getArrayParam('id');
		$result = false;
		if (false !== $result) {
			return $this->success('删除成功！');
		} else {
			return $this->error('删除失败！');
		}
	}

	/**
	 * @title       表单数据
	 * @description 表单数据
	 * @Author      molong
	 * @DateTime    2017-06-30
	 * @return      html        页面
	 */
	public function lists($form_id = '') {
		$form = $this->model->where('id', $form_id)->find();

		$list = M($form['name'], 'form')->order('id desc')->paginate(25);

		$data = array(
			'form_id'  => $form_id,
			'list'   => $list,
			'page'   => $list->render()
		);
		$this->assign($data);
		$this->setMeta('数据列表');
		return $this->fetch('list_'.$form['name']);
	}

	/**
	 * @title 数据详情
	 */
	public function detail($form_id = '', $id = ''){
		$form = $this->model->where('id', $form_id)->find();

		$info = M($form['name'], 'form')->where('id', $id)->find();

		$data = array(
			'info'   => $info
		);
		$this->assign($data);
		$this->setMeta('数据详情');
		return $this->fetch('detail_'.$form['name']);
	}

	/**
	 * @title 数据导出
	 */
	public function outxls($form_id = '') {
		$form = $this->model->where('id', $form_id)->find();

		$attr = $this->Fattr->where('form_id', $form_id)->where('is_show', 1)->select();
		foreach ($attr as $key => $value) {
			$title[$value['name']] = $value['title'];
		}

		$data[] = $title;
		$res = M($form['name'], 'form')->order('id desc')->select();

		foreach ($res as $key => $value) {
			$data[] = $value;
		}

		$out = new \com\Outxls($data, date('Y-m-d'));
		$out->out();
	}

	/**
	 * @title 表单字段
	 */
	public function attr($form_id = '') {
		$map   = array();
		$order = "id desc";
		$list  = $this->Fattr->where($map)->order($order)->paginate(25);

		$data = array(
			'list'    => $list,
			'form_id' => $form_id,
			'page'    => $list->render(),
		);
		$this->setMeta('表单字段');
		$this->assign($data);
		return $this->fetch();
	}

	/**
	 * @title 添加表单字段
	 */
	public function addattr(){
		$form_id = $this->request->param('form_id', '');
		if (!$form_id) {
			return $this->error('非法操作！');
		}
		if ($this->request->isPost()) {
			$data = $this->request->post();
			$result = $this->Fattr->save($data);
			if (false !== $result) {
				return $this->success('添加成功！', url('admin/form/attr?form_id='.$form_id));
			}else{
				return $this->error($this->Fattr->getError());
			}
		}else{
			$info = array(
				'form_id'   => $form_id
			);
			$data = array(
				'info'   => $info,
				'keyList'   => $this->field
			);
			$this->assign($data);
			$this->setMeta('添加字段');
			return $this->fetch('public/edit');
		}
	}

	/**
	 * @title 编辑表单字段
	 */
	public function editattr(\think\Request $request){
		$param = $this->request->param();

		$form_id = isset($param['form_id']) ? $param['form_id'] : '';
		$id = isset($param['id']) ? $param['id'] : '';
		if (!$form_id || !$id) {
			return $this->error('非法操作！');
		}
		if ($this->request->isPost()) {
			$data = $request->post();
			$result = $this->Fattr->save($data, array('id'=>$data['id']));
			if (false !== $result) {
				return $this->success('修改成功！', url('admin/form/attr?form_id='.$form_id));
			}else{
				return $this->error($this->Fattr->getError());
			}
		}else{
			$info = $this->Fattr->where('id', $id)->find();
			$data = array(
				'info'      => $info,
				'keyList'   => $this->field
			);
			$this->assign($data);
			$this->setMeta('添加字段');
			return $this->fetch('public/edit');
		}
	}

	/**
	 * @title 删除表单字段
	 */
	public function delattr(\think\Request $request){
		$id = $request->param('id', 0);
		if (!$id) {
			return $this->error('非法操作！');
		}
		$result = $this->Fattr->where('id', $id)->delete();
		if (false !== $result) {
			return $this->success('添加成功！');
		}else{
			return $this->error($this->Fattr->getError());
		}
	}

	protected function getField(){
		return  array(
			array('name' => 'id', 'title' => 'id', 'help' => '', 'type' => 'hidden'),
			array('name' => 'form_id', 'title' => 'model_id', 'help' => '', 'type' => 'hidden'),
			array('name' => 'name', 'title' => '字段名', 'help' => '英文字母开头，长度不超过30', 'type' => 'text'),
			array('name' => 'title', 'title' => '字段标题', 'help' => '请输入字段标题，用于表单显示', 'type' => 'text'),
			array('name' => 'type', 'title' => '字段类型', 'help' => '用于表单中的展示方式', 'type' => 'select', 'option' => $this->attr, 'help' => ''),
			array('name' => 'length', 'title' => '字段长度', 'help' => '字段的长度值', 'type' => 'text'),
			array('name' => 'extra', 'title' => '参数', 'help' => '布尔、枚举、多选字段类型的定义数据', 'type' => 'textarea'),
			array('name' => 'value', 'title' => '默认值', 'help' => '字段的默认值', 'type' => 'text'),
			array('name' => 'remark', 'title' => '字段备注', 'help' => '用于表单中的提示', 'type' => 'text'),
			array('name' => 'is_show', 'title' => '是否显示', 'help' => '是否显示在表单中', 'type' => 'select', 'option' => array('1' => '始终显示', '2' => '新增显示', '3' => '编辑显示', '0' => '不显示'), 'value' => 1),
			array('name' => 'is_must', 'title' => '是否必填', 'help' => '用于自动验证', 'type' => 'select', 'option' => array('0' => '否', '1' => '是')),
		);
	}
}