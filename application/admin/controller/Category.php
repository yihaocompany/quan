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
 * @title 分类管理
 * @description 分类管理
 */
class Category extends Admin {

	public function _initialize() {
		parent::_initialize();
		$this->getContentMenu();
	}

	/**
	 * @title 分类列表
	 */
	public function index($model_id = '') {
		$map  = array('status' => array('gt', -1));
		if ($model_id) {
			$map['model_id'] = $model_id;
		}
		$list = db('Category')->where($map)->order('sort asc,id asc')->column('*', 'id');

		if (!empty($list)) {
			$tree = new \com\Tree();
			$list = $tree->toFormatTree($list);
		}
		$subsql = db('Attribute')->where('name', 'category_id')->fetchSql(true)->column('model_id');
		$model_list = model('Model')->where('id IN ('. $subsql.')')->select();

		$this->assign('tree', $list);
		$this->assign('model_list', $model_list);
		$this->assign('model_id', $model_id);
		$this->setMeta('栏目列表');
		return $this->fetch();
	}

	/**
	 * @title 编辑字段
	 */
	public function editable($name = null, $value = null, $pk = null) {
		if ($name && ($value != null || $value != '') && $pk) {
			db('Category')->where(array('id' => $pk))->setField($name, $value);
		}
	}

	/**
	 * @title 编辑分类
	 */
	public function edit($id = null, $pid = 0) {
		if ($this->request->isPost()) {
			$category = model('Category');
			//提交表单
			$result = $category->change();
			if (false !== $result) {
				//记录行为
				action_log('update_category', 'category', $id, session('user_auth.uid'));
				return $this->success('编辑成功！', url('index'));
			} else {
				$error = $category->getError();
				return $this->error(empty($error) ? '未知错误！' : $error);
			}
		} else {
			$cate = '';
			if ($pid) {
				/* 获取上级分类信息 */
				$cate = db('Category')->find($pid);
				if (!($cate && 1 == $cate['status'])) {
					return $this->error('指定的上级分类不存在或被禁用！');
				}
			}
			$subsql = db('Attribute')->where('name', 'category_id')->fetchSql(true)->column('model_id');
			$model_list = model('Model')->where('id IN ('. $subsql.')')->select();

			/* 获取分类信息 */
			$info = $id ? db('Category')->find($id) : '';

			$this->assign('info', $info);
			$this->assign('model_list', $model_list);
			$this->assign('category', $cate);
			$this->setMeta('编辑分类');
			return $this->fetch();
		}
	}

	/**
	 * @title 添加分类
	 */
	public function add($pid = 0) {
		$Category = model('Category');

		if ($this->request->isPost()) {
			//提交表单
			$id = $Category->change();
			if (false !== $id) {
				action_log('update_category', 'category', $id, session('user_auth.uid'));
				return $this->success('新增成功！', url('index'));
			} else {
				$error = $Category->getError();
				return $this->error(empty($error) ? '未知错误！' : $error);
			}
		} else {
			$cate = array();
			if ($pid) {
				/* 获取上级分类信息 */
				$cate = $Category->info($pid, 'id,name,title,status');
				if (!($cate && 1 == $cate['status'])) {
					return $this->error('指定的上级分类不存在或被禁用！');
				}
			}
			$subsql = db('Attribute')->where('name', 'category_id')->fetchSql(true)->column('model_id');
			$model_list = model('Model')->where('id IN ('. $subsql.')')->select();
			
			/* 获取分类信息 */
			$this->assign('info', null);
			$this->assign('model_list', $model_list);
			$this->assign('category', $cate);
			$this->setMeta('新增分类');
			return $this->fetch('edit');
		}
	}
	/**
	 * @title 删除分类
	 * @author huajie <banhuajie@163.com>
	 */
	public function remove($id) {
		if (empty($id)) {
			return $this->error('参数错误!');
		}
		//判断该分类下有没有子分类，有则不允许删除
		$child = db('Category')->where(array('pid' => $id))->field('id')->select();
		if (!empty($child)) {
			return $this->error('请先删除该分类下的子分类');
		}
		//判断该分类下有没有内容
		// $document_list = db('Document')->where(array('category_id' => $id))->field('id')->select();
		// if (!empty($document_list)) {
		// 	return $this->error('请先删除该分类下的文章（包含回收站）');
		// }
		//删除该分类信息
		$res = db('Category')->where(array('id' => $id))->delete();
		if ($res !== false) {
			//记录行为
			action_log('update_category', 'category', $id, session('user_auth.uid'));
			return $this->success('删除分类成功！');
		} else {
			return $this->error('删除分类失败！');
		}
	}

	/**
	 * 操作分类初始化
	 * @param string $type
	 * @author huajie <banhuajie@163.com>
	 */
	public function operate($type = 'move', $from = '') {
		//检查操作参数
		if ($type == 'move') {
			$operate = '移动';
		} elseif ($type == 'merge') {
			$operate = '合并';
		} else {
			return $this->error('参数错误！');
		}

		if (empty($from)) {
			return $this->error('参数错误！');
		}
		//获取分类
		$map  = array('status' => 1, 'id' => array('neq', $from));
		$list = db('Category')->where($map)->field('id,pid,title')->select();
		//移动分类时增加移至根分类
		if ($type == 'move') {
			//不允许移动至其子孙分类
			$list = tree_to_list(list_to_tree($list));

			$pid = db('Category')->getFieldById($from, 'pid');
			$pid && array_unshift($list, array('id' => 0, 'title' => '根分类'));
		}

		$this->assign('type', $type);
		$this->assign('operate', $operate);
		$this->assign('from', $from);
		$this->assign('list', $list);
		$this->setMeta($operate . '分类');
		return $this->fetch();
	}
	
	/**
	 * @title 移动分类
	 * @author huajie <banhuajie@163.com>
	 */
	public function move() {
		$to   = input('post.to');
		$from = input('post.from');
		$res  = db('Category')->where(array('id' => $from))->setField('pid', $to);
		if ($res !== false) {
			return $this->success('分类移动成功！', url('index'));
		} else {
			return $this->error('分类移动失败！');
		}
	}

	/**
	 * @title 合并分类
	 * @author huajie <banhuajie@163.com>
	 */
	public function merge() {
		$to    = input('post.to');
		$from  = input('post.from');
		$Model = model('Category');
		//检查分类绑定的模型
		$from_models = explode(',', $Model->getFieldById($from, 'model'));
		$to_models   = explode(',', $Model->getFieldById($to, 'model'));
		foreach ($from_models as $value) {
			if (!in_array($value, $to_models)) {
				return $this->error('请给目标分类绑定' . get_document_model($value, 'title') . '模型');
			}
		}
		//检查分类选择的文档类型
		$from_types = explode(',', $Model->getFieldById($from, 'type'));
		$to_types   = explode(',', $Model->getFieldById($to, 'type'));
		foreach ($from_types as $value) {
			if (!in_array($value, $to_types)) {
				$types = config('document_model_type');
				return $this->error('请给目标分类绑定文档类型：' . $types[$value]);
			}
		}
		//合并文档
		$res = db('Document')->where(array('category_id' => $from))->setField('category_id', $to);

		if ($res !== false) {
			//删除被合并的分类
			$Model->delete($from);
			return $this->success('合并分类成功！', url('index'));
		} else {
			return $this->error('合并分类失败！');
		}
	}

	/**
	 * @title 修改状态
	 * @author huajie <banhuajie@163.com>
	 */
	public function status() {
		$id     = $this->getArrayParam('id');
		$status = input('status', '0', 'trim,intval');

		if (!$id) {
			return $this->error("非法操作！");
		}

		$map['id'] = array('IN', $id);
		$result    = db('Category')->where($map)->setField('status', $status);
		if ($result) {
			return $this->success("设置成功！");
		} else {
			return $this->error("设置失败！");
		}
	}
	
	/**
	 * @title 生成频道
	 * @author huajie <banhuajie@163.com>
	 */
	public function add_channel() {
			if ($this->request->isPost()) {
				$Channel = model('Channel');
				$data    = $this->request->param();
				if ($data) {
					$id = $Channel->save($data);
					if ($id) {
						$map['id'] = array('IN', $data['mid']);
						$result  = db('Category')->where($map)->setField('ismenu',$Channel->id);                                      
						return $this->success('生成成功',url('index'));
						//记录行为
						action_log('update_channel', 'channel', $id, session('user_auth.uid'));
					} else {
						return $this->error('生成失败');
					}
				} else {
					$this->error($Channel->getError());
				}
			} else {
				$data    = $this->request->param();
				$modelname = db('Model')->where( array('id' => $data['model_id']) )->field('id,name')->find();  
				$data['url'] = $modelname['name'].'/list/'.$data['mid'];
				$pid = input('pid', 0);
				//获取父导航
				if (!empty($pid)) {
					$parent = db('Channel')->where(array('id' => $pid))->field('title')->find();
					$this->assign('parent', $parent);
				}
				$pnav = db('Channel')->where(array('pid' => '0'))->select();
				$this->assign('pnav', $pnav);
				$this->assign('pid', $pid);
				$this->assign('info', $data);
				$this->assign('data',null );
				$this->setMeta('生成导航');    
				return $this->fetch('edit_channel');
			}
	}
}