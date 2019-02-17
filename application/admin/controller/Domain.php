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
 * @title 友情链接
 * @description 友情链接
 */
class Domain extends Admin {

	/**
	 * @title 链接列表
	 */
	public function index() {
        $map = array();

        $order = "id desc";
        $list  = db('web_config')->where($map)->order($order)->paginate(10, false, array(
            'query'  => $this->request->param()
        ));

        $data = array(
            'list' => $list,
            'page' => $list->render(),
        );
        $this->assign($data);
        $this->setMeta("网站管理");
        return $this->fetch();
	}

	/**
	 * @title 添加链接
	 */
	public function add() {
        $webconfig = model('WebConfig');
		if ($this->request->isPost()) {
			$data = input('post.');
			if ($data) {
				unset($data['id']);
				$result = $webconfig->save($data);
				if ($result) {
					return $this->success("添加成功！", url('Link/index'));
				} else {
					return $this->error($webconfig->getError());
				}
			} else {
				return $this->error($webconfig->getError());
			}
		} else {
            $this->assign(array('info'=>null));

			$this->setMeta("添加网站");
			return $this->fetch('edit');
		}
	}
    /**
     * 修改装态
     */

    public function  status(){
        $link = model('WebConfig');
        $id   = input('id', '', 'trim,intval');
    }


	/**
	 * @title 修改链接
	 */
	public function edit() {
		$webconfig = model('WebConfig');
		$id   = input('id', '', 'trim,intval');
		if ($this->request->isPost()) {
			$data = input('post.');
			if ($data) {
				$result = $webconfig->save($data, array('id' => $data['id']));
				if ($result) {
					return $this->success("修改成功！", url('Link/index'));
				} else {
					return $this->error("修改失败！");
				}
			} else {
				return $this->error($webconfig->getError());
			}
		} else {
			$map  = array('id' => $id);
			$info = db('WebConfig')->where($map)->find();
			$this->assign(array('info'=>$info));
			$this->setMeta("网站管理");
			return $this->fetch('edit');
		}
	}

	/**
	 * @title 删除链接
	 */
	public function delete() {
		$id = $this->getArrayParam('id');
		if (empty($id)) {
			return $this->error('非法操作！');
		}
		$link = db('web_config');

		$map    = array('id' => array('IN', $id));
		$result = $link->where($map)->delete();
		if ($result) {
			return $this->success("删除成功！");
		} else {
			return $this->error("删除失败！");
		}
	}
}