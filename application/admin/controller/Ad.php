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
 * @title 广告管理
 * @description 广告管理
 */
class Ad extends Admin {

	protected $ad;
	protected $adplace;

	public function _initialize() {
		parent::_initialize();
		$this->ad      = db('Ad');
		$this->adplace = db('AdPlace');
	}

	/**
	 * @title 广告位管理
	 */
	public function index() {
		$map   = array();
		$order = "id desc";

		$list = db('AdPlace')->where($map)->order($order)->paginate(10, false, array(
				'query'  => $this->request->param()
			));
		$data = array(
			'list' => $list,
			'page' => $list->render(),
		);
		$this->assign($data);
		$this->setMeta("广告管理");
		return $this->fetch();
	}

	/**
	 * @title 广告位添加
	 */
	public function add() {
		$place = model('AdPlace');
		if ($this->request->isPost()) {
			$result = $place->change();
			if (false !== false) {
				return $this->success("添加成功！");
			} else {
				return $this->error($place->getError());
			}
		} else {
			$data = array(
				'keyList' => $place->keyList,
			);
			$this->assign($data);
			$this->setMeta("添加广告位");
			return $this->fetch('public/edit');
		}
	}

	/**
	 * @title 广告位编辑
	 */
	public function edit($id = null) {
		$place = model('AdPlace');
		if ($this->request->isPost()) {
			$result = $place->change();
			if ($result) {
				return $this->success("修改成功！", url('admin/ad/index'));
			} else {
				return $this->error($this->adplace->getError());
			}
		} else {
			$info = db('AdPlace')->where(array('id' => $id))->find();
			if (!$info) {
				return $this->error("非法操作！");
			}
			$data = array(
				'info'    => $info,
				'keyList' => $place->keyList,
			);
			$this->assign($data);
			$this->setMeta("编辑广告位");
			return $this->fetch('public/edit');
		}
	}

	/**
	 * @title 广告位删除
	 */
	public function del() {
		$id = $this->getArrayParam('id');

		if (empty($id)) {
			return $this->error("非法操作！");
		}
		$map['id'] = array('IN', $id);
		$result    = $this->adplace->where($map)->delete();
		if ($result) {
			return $this->success("删除成功！");
		} else {
			return $this->error("删除失败！");
		}
	}

	/**
	 * @title 广告列表
	 */
	public function lists($id = null) {
		$map['place_id'] = $id;
		$order           = "id desc";

		$list = db('Ad')->where($map)->order($order)->paginate(10, false, array(
				'query'  => $this->request->param()
			));
		$data = array(
			'id'   => $id,
			'list' => $list,
			'page' => $list->render(),
		);
		$this->assign($data);
		$this->setMeta("广告管理");
		return $this->fetch();
	}

	/**
	 * @title 添加广告
	 */
	public function addad($id) {
		$ad = model('ad');
		if ($this->request->isPost()) {
			$result = $ad->change();
			if ($result) {
				return $this->success("添加成功！", url('admin/ad/lists', array('id' => $this->request->param('place_id'))));
			} else {
				return $this->error($ad->getError());
			}
		} else {
			$info['place_id'] = $id;
			$data             = array(
				'info'    => $info,
				'keyList' => $ad->keyList,
			);
			$this->assign($data);
			$this->setMeta("添加广告位");
			return $this->fetch('public/edit');
		}
	}

	/**
	 * @title 编辑广告
	 */
	public function editad($id = null) {
		$ad = model('ad');
		if ($this->request->isPost()) {
			$result = $ad->change();
			if ($result) {
				return $this->success("修改成功！", url('admin/ad/lists', array('id' => $this->request->param('place_id'))));
			} else {
				return $this->error($ad->getError());
			}
		} else {
			$info = db('ad')->where(array('id' => $id))->find();
			if (!$info) {
				return $this->error("非法操作！");
			}
			$data = array(
				'info'    => $info,
				'keyList' => $ad->keyList,
			);
			$this->assign($data);
			$this->setMeta("编辑广告位");
			return $this->fetch('public/edit');
		}
	}

	/**
	 * @title 删除广告
	 */
	public function delad() {
		$id = $this->getArrayParam('id');

		if (empty($id)) {
			return $this->error("非法操作！");
		}
		$map['id'] = array('IN', $id);
		$result    = db('ad')->where($map)->delete();
		if ($result) {
			return $this->success("删除成功！");
		} else {
			return $this->error("删除失败！");
		}
	}
}