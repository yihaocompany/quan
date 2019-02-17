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
 * @title 模型管理
 */
class Model extends Admin {

	public function _initialize() {
		parent::_initialize();
		$this->getContentMenu();
		$this->model = model('Model');
	}

	/**
	 * @title 模型列表
	 * @author huajie <banhuajie@163.com>
	 */
	public function index() {
		$map = array('status' => array('gt', -1));

		$order = "id desc";
		$list  = $this->model->where($map)->order($order)->paginate(10, false, array(
				'query'  => $this->request->param()
			));

		$data = array(
			'list' => $list,
			'page' => $list->render(),
		);

		// 记录当前列表页的cookie
		Cookie('__forward__', $_SERVER['REQUEST_URI']);

		$this->assign($data);
		$this->setMeta('模型管理');
		return $this->fetch();
	}

	/**
	 * @title 新增模型
	 * @author huajie <banhuajie@163.com>
	 */
	public function add(\think\Request $request) {
		if ($this->request->isPost()) {
			$result = $this->model->validate('Model.add')->save($request->post());
			if (false !== $result) {
				//记录行为
				action_log('add_model', 'model', $result, session('auth_user.uid'));
				$this->success('创建成功！', url('admin/model/index'));
			} else {
				return $this->error($this->model->getError() ? $this->model->getError() : '模型标识为保留名称！');
			}
		} else {
			$this->setMeta('新增模型');
			return $this->fetch();
		}
	}

	/**
	 * @title 编辑模型
	 * @author molong <molong@tensent.cn>
	 */
	public function edit(\think\Request $request) {
		if ($this->request->isPost()) {
			$result = $this->model->validate('Model.edit')->save($request->post(), array('id' => $request->post('id')));
			if (false !== $result) {
				//记录行为
				action_log('update_model', 'model', $request->post('id'), session('auth_user.uid'));
				$this->success('更新成功！', url('admin/model/index'));
			} else {
				return $this->error($this->model->getError());
			}
		} else {
			$info = $this->model->where('id', $request->param('id'))->find();

			$field_group = parse_config_attr($info['attribute_group']);
			//获取字段列表
			$rows = db('Attribute')->where('model_id', $request->param('id'))->where('is_show', 1)->order('group_id asc, sort asc')->select();
			if ($rows) {
				// 梳理属性的可见性
				foreach ($rows as $key => $field) {
					$list[$field['group_id']][] = $field;
				}
				foreach ($field_group as $key => $value) {
					$fields[$key] = isset($list[$key]) ? $list[$key] : array();
				}
			} else {
				$fields = array();
			}
			$data = array(
				'info'        => $info,
				'field_group' => $field_group,
				'fields'      => $fields,
			);
			$this->assign($data);
			$this->setMeta('编辑模型');
			return $this->fetch();
		}
	}

	/**
	 * @title 删除模型
	 * @author huajie <banhuajie@163.com>
	 */
	public function del() {
		$result = $this->model->del();
		if ($result) {
			return $this->success('删除模型成功！');
		} else {
			return $this->error($this->mdoel->getError());
		}
	}

	public function update() {
		$res = \think\Loader::model('Model')->change();
		if ($res['status']) {
			return $this->success($res['info'], url('index'));
		} else {
			return $this->error($res['info']);
		}
	}

	/**
	 * @title 更新数据
	 * @author colin <colin@tensent.cn>
	 */
	public function status(\think\Request $request) {
		$map['id'] = $request->param('id');

		$data['status'] = $request->param('status');

		if (null == $map['id'] || null == $data['status']) {
			return $this->error('参数不正确！');
		}

		$model = $this->model->where($map)->find();
		if ($model['list_grid'] == '' && $data['status'] == 1) {
			return $this->error('模型列表未定义');
		}
		$result = $this->model->where($map)->update($data);
		if (false !== $result) {
			return $this->success('状态设置成功！');
		} else {
			return $this->error($this->model->getError());
		}
	}
}