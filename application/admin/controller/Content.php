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
 * @title 内容管理
 */
class Content extends Admin {

	public function _initialize() {
		parent::_initialize();
		$this->getContentMenu();
		$this->model_id = $model_id = $this->request->param('model_id');
		$list            = db('Model')->column('*', 'id');

		if (empty($list[$model_id])) {
			return $this->error("无此模型！");
		} else {
			$this->modelInfo = $list[$model_id];
			$this->model = M($this->modelInfo['name']);
		}

		$this->assign('model_id', $model_id);
		$this->assign('model_list', $list);
	}

	/**
	 * @title 内容列表
	 * @return [html] [页面内容]
	 * @author molong <ycgpp@126.com>
	 */
	public function index() {
		if ($this->modelInfo['list_grid'] == '') {
			return $this->error("列表定义不正确！", url('admin/model/edit', array('id' => $this->modelInfo['id'])));
		}
		$grid_list = get_grid_list($this->modelInfo['list_grid']);
		$order     = "id desc";
		$map       = $this->buildMap();
		$field     = array_filter($grid_list['fields']);


		$list = $this->model->where($map)->order($order)->paginate($this->modelInfo['list_row'], false, array(
				'query'  => $this->request->param()
			));

		$data = array(
			'grid' => $grid_list,
			'list' => $list,
			'page' => $list->render(),
		);
		if ($this->modelInfo['template_list']) {
			$template = 'content/' . $this->modelInfo['template_list'];
		} else {
			$template = 'content/index';
		}
		$this->assign($data);
		$this->setMeta($this->modelInfo['title'] . "列表");
		return $this->fetch($template);
	}

	/**
	 * @title 内容添加
	 * @author molong <ycgpp@126.com>
	 */
	public function add() {
		if ($this->request->isPost()) {
			$result = $this->model->save($this->request->param());
			if ($result) {
				//记录行为
				action_log('add_content', 'content', $result, session('auth_user.uid'));
				return $this->success("添加成功！", url('admin/content/index', array('model_id' => $this->modelInfo['id'])));
			} else {
				return $this->error($this->model->getError(), url('admin/content/add', array('model_id' => $this->modelInfo['id'])));
			}
		} else {
			$info = array(
				'model_id' => $this->modelInfo['id'],
			);
			$data = array(
				'info'       => $info,
				'fieldGroup' => $this->getField($this->modelInfo),
			);
			if ($this->modelInfo['template_add']) {
				$template = 'content/' . $this->modelInfo['template_add'];
			} else {
				$template = 'public/edit';
			}
			$this->assign($data);
			$this->setMeta("添加" . $this->modelInfo['title']);
			return $this->fetch($template);
		}
	}

	/**
	 * @title 内容修改
	 * @author molong <ycgpp@126.com>
	 */
	public function edit($id) {
		if ($this->request->isPost()) {
			$result = $this->model->save($this->request->param(), array('id'=> $id));
			if ($result !== false) {
				//记录行为
				action_log('update_content', 'content', $result, session('auth_user.uid'));
				return $this->success("更新成功！", url('admin/content/index', array('model_id' => $this->modelInfo['id'])));
			} else {
				return $this->error($this->model->getError(), url('admin/content/edit', array('model_id' => $this->modelInfo['id'], 'id' => $id)));
			}
		} else {
			if (!$id) {
				return $this->error("非法操作！");
			}
			$info = $this->model->find($id);
			if (!$info) {
				return $this->error($this->model->getError());
			}
			$info['model_id'] = $this->modelInfo['id'];
			$data             = array(
				'info'       => $info,
				'fieldGroup' => $this->getField($this->modelInfo),
			);
			if ($this->modelInfo['template_edit']) {
				$template = 'content/' . $this->modelInfo['template_edit'];
			} else {
				$template = 'public/edit';
			}
			$this->assign($data);
			$this->setMeta("编辑" . $this->modelInfo['title']);
			return $this->fetch($template);
		}
	}

	/**
	 * @title 内容删除
	 * @author molong <ycgpp@126.com>
	 */
	public function del() {
		$param = $this->request->param();
		$id = $param['id'];
		if (empty($id)) {
			return $this->error("非法操作！");
		}

		$map['id'] = array('IN', $id);
		$result    = $this->model->where($map)->delete();

		if (false !== $result) {
			//记录行为
			action_log('delete_content', 'content', $result, session('auth_user.uid'));
			return $this->success("删除成功！");
		} else {
			return $this->error("删除失败！");
		}
	}

	/**
	 * @title 设置状态
	 * @author molong <ycgpp@126.com>
	 */
	public function status($id, $status) {
		$map['id'] = $id;
		$result    = $this->model->where($map)->setField('status', $status);
		if (false !== $result) {
			return $this->success("操作成功！");
		} else {
			return $this->error("操作失败！！");
		}
	}

	/**
	 * @title 设置置顶
	 * @author molong <ycgpp@126.com>
	 */
	public function settop($id, $is_top) {
		$map['id'] = $id;
		$result    = $this->model->where($map)->setField('is_top', $is_top);
		if (false !== $result) {
			return $this->success("操作成功！");
		} else {
			return $this->error("操作失败！！");
		}
	}

	/**
	 * @title 获取字段信息
	 * @return array 字段数组
	 * @author molong <ycgpp@126.com>
	 */
	protected function getField() {
		$field_group = parse_config_attr($this->modelInfo['attribute_group']);

		$map['model_id'] = $this->modelInfo['id'];
		if ($this->request->action() == 'add') {
			$map['is_show'] = array('in', array('1', '2'));
		} elseif ($this->request->action() == 'edit') {
			$map['is_show'] = array('in', array('1', '3'));
		}

		//获得数组的第一条数组
		$rows    = model('Attribute')->getFieldlist($map, 'id');
		if (!empty($rows)) {
			foreach ($rows as $key => $value) {
				$list[$value['group_id']][] = $value;
			}
			foreach ($field_group as $key => $value) {
				$fields[$value] = isset($list[$key]) ? $list[$key] : array();
			}
		}else{
			$fields = array();
		}
		return $fields;
	}

	/**
	 * @title 创建搜索
	 * @return [array] [查询条件]
	 */
	protected function buildMap() {
		$map  = array();
		$data = $this->request->param();
		foreach ($data as $key => $value) {
			if ($value) {
				if ($key == 'keyword') {
					$map['title'] = array("LIKE", "%$value%");
				} elseif ($key == 'category') {
					$map['category_id'] = $value;
				} elseif ($key == 'create_time') {
					$map['create_time'] = array('BETWEEN', array(strtotime($value[0]), strtotime($value[1])));
				} else {
					$map[$key] = $value;
				}
			}
		}
		if (isset($map['page'])) {
			unset($map['page']);
		}
		if (isset($map['model_id'])) {
			unset($map['model_id']);
		}
		$this->assign($data);
		return $map;
	}

	/**
	 * 检测需要动态判断的文档类目有关的权限
	 *
	 * @return boolean|null
	 *      返回true则表示当前访问有权限
	 *      返回false则表示当前访问无权限
	 *      返回null，则会进入checkRule根据节点授权判断权限
	 *
	 * @author 朱亚杰  <xcoolcc@gmail.com>
	 */
	protected function checkDynamic() {
		$model_id = $this->request->param('model_id');
		if (IS_ROOT) {
			return true; //管理员允许访问任何页面
		}
		$models = model('AuthGroup')->getAuthModels(session('user_auth.uid'));
		if (!$model_id) {
			return false;
		} elseif (in_array($model_id, $models)) {
			//返回null继续判断操作权限
			return null;
		} else {
			return false; //无权限
		}
		return false;
	}
}