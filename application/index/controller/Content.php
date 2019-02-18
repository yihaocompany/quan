<?php
// +----------------------------------------------------------------------
// | YihaoCMS [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://diao.info All rights reserved.
// +----------------------------------------------------------------------
// | Author: molong <molong@tensent.cn> <http://diao.info>
// +----------------------------------------------------------------------

namespace app\index\controller;
use app\common\controller\Front;

class Content extends Front {

	protected $beforeActionList = array(
		'setModel' => array('except' => 'category'),
	);

	//模块频道首页
	public function index() {
		$id   = input('id', '', 'trim,intval');
		$name = input('name', '', 'trim');
		if ($name) {
			$id = db('Category')->where(array('name' => $name))->getField('id');
		}

		if ($id) {
			$cate = $this->getCategory($id);

			//获得当前栏目的所有子栏目
			$ids = get_category_child($id);
		}else{
			$cate = array();
			$ids = '';
		}

		$data = array(
			'category'   => $cate,
			'child_cate' => $ids,
		);
		if (isset($cate['template_index']) && $cate['template_index']) {
			$teamplate = 'content/' . $this->modelInfo['name'] . '/' . $cate['template_index'];
		} else {
			$teamplate = 'content/' . $this->modelInfo['name'] . '/index';
		}
		$this->assign($data);
		$this->setSeo($this->modelInfo['title']);
		return $this->fetch($teamplate);
	}

	//模块列表页
	public function lists($id = '', $name = '') {
		if ($name) {
			$id = db('Category')->where(array('name' => $name))->getField('id');
		}

		if (!$id) {
			return $this->error("无此栏目！");
		}

		$cate = $this->getCategory($id);
		$map = array();
		$attr = db('Attribute')->where('model_id', $this->modelInfo['id'])->column('name');
		if (in_array('category_id', $attr)) {
			$ids                = get_category_child($id);
			$map['category_id'] = array('IN', $ids);
		}
		if (in_array('status', $attr)) {
			$map['status']      = array('GT', 0);
		}
		if (in_array('is_top', $attr)) {
			$order = "is_top desc,id desc";
		}else{
			$order = "id desc";
		}

		$list = $this->model->where($map)->order($order)->paginate($cate['list_row'] ? $cate['list_row'] : 15);

		$data = array(
			'list' => $list,
			'cate' => $cate,
			'page' => $list->render(),
		);

		if ($cate['template_lists']) {
			$teamplate = 'content/' . $this->modelInfo['name'] . '/' . $cate['template_lists'];
		} else {
			$teamplate = 'content/' . $this->modelInfo['name'] . '/list';
		}
		$this->setSeo($cate['title']);
		$this->assign($data);
		return $this->fetch($teamplate);
	}

	public function category() {
		$id = $this->request->param('id');
		if (!$id) {
			return $this->error("非法操作");
		}
		$cate = $this->getCategory($id);

		$category = get_category_child($id);
		$map      = array(
			'category_id' => array('IN', $category),
		);

		$order = "id desc";
		$list  = $this->model->where($map)->order($order)->paginate(15);

		$data = array(
			'list' => $list,
			'cate' => $cate,
			'page' => $list->render(),
		);
		if ($cate['template_lists']) {
			$teamplate = 'content/' . $cate['template_lists'];
		} else {
			$teamplate = 'content/list';
		}
		$this->setSeo($cate['title']);
		$this->assign($data);
		return $this->fetch($teamplate);
	}

	//模块内容详情页
	public function detail($id = '', $name = '') {
		//当为文章模型时
		$info = $this->model->find($id);

		if (empty($info)) {
			return $this->error("无此内容！");
		}

		$cate = array();
		if (isset($info['category_id'])) {
			$cate = $this->getCategory($info['category_id']);
		}

		$data = array(
			'info' => $info,
			'cate' => $cate
		);
		if (isset($info['template_detail']) && $info['template_detail']) {
			$teamplate = 'content/' . $this->modelInfo['name'] . '/' . $info['template_detail'];
		} else {
			$teamplate = 'content/' . $this->modelInfo['name'] . '/detail';
		}
		$this->assign($data);
		$title       = isset($info['title']) ? $info['title'] : '';
		$tags        = isset($info['tags']) ? $info['tags'] : '';
		$description = isset($info['description']) ? $info['description'] : '';
		$this->setSeo($title, $tags, $description);
		return $this->fetch($teamplate);
	}

	protected function getCategory($id) {
		$data = db('Category')->find($id);
		return $data;
	}

	protected function setModel() {
		$model_name = $this->request->param('model');
		$model_id   = $this->request->param('model_id');
		$row        = db('Model')->select();
		foreach ($row as $key => $value) {
			$name_list[$value['name']] = $value;
			$id_list[$value['id']]     = $value;
		}

		if (empty($name_list[$model_name]) && empty($id_list[$model_id])) {
			return $this->error("无此模型！");
		} else {
			$this->modelInfo = $model_name ? $name_list[$model_name] : $id_list[$model_id];
			$this->model = M($this->modelInfo['name']);

			$this->assign('model_id', $this->modelInfo['id']);
			$this->assign('model_list', $name_list);
		}
	}
}