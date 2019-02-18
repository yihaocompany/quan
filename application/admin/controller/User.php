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
 * @title 用户管理
 */
class User extends Admin {

	/**
	 * @title 用户列表
	 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
	 */
	public function index() {
		$param = $this->request->param();
		$map['status'] = array('egt', 0);
		if (isset($param['nickname']) && $param['nickname']) {
			$map['nickname'] = array('like', '%' . $param['nickname'] . '%');
		} 
		if (isset($param['username']) && $param['username']) {
			$map['username'] = array('like', '%' . (string) $param['nickname'] . '%');
		}

		$order = "uid desc";
		$list  = model('Member')->where($map)->order($order)
			->paginate(15, false, array(
				'param'  => $param
			));

		$data = array(
			'list' => $list,
			'page' => $list->render(),
			'param' => $param
		);
		$this->assign($data);
		$this->setMeta('用户信息');
		return $this->fetch();
	}

	/**
	 * @title 添加用户
	 * @author colin <molong@tensent.cn>
	 */
	public function add() {
		$model = \think\Loader::model('Member');
		if ($this->request->isPost()) {
			$data = $this->request->param();
			//创建注册用户
			$result = $model->register($data['username'], $data['password'], $data['repassword'], $data['email'], false);
			if ($result) {
				return $this->success('用户添加成功！', url('admin/user/index'));
			} else {
				return $this->error($model->getError());
			}
		} else {
			$data = array(
				'keyList' => $model->addfield,
			);
			$this->assign($data);
			$this->setMeta("添加用户");
			return $this->fetch('public/edit');
		}
	}

	/**
	 * @title 修改用户
	 * @author huajie <banhuajie@163.com>
	 */
	public function edit() {
		$model = model('Member');
		if ($this->request->isPost()) {
			$data = $this->request->post();

			$reuslt = $model->editUser($data, true);

			if (false !== $reuslt) {
				return $this->success('修改成功！', url('admin/user/index'));
			} else {
				return $this->error($model->getError(), '');
			}
		} else {
			$info = $this->getUserinfo();

			$data = array(
				'info'    => $info,
				'keyList' => $model->editfield,
			);
			$this->assign($data);
			$this->setMeta("编辑用户");
			return $this->fetch('public/edit');
		}
	}

	/**
	 * @title 删除用户
	 * @author colin <colin@tensent.cn>
	 */
	public function del($id) {
		$uid = array('IN', is_array($id) ? implode(',', $id) : $id);
		//获取用户信息
		$find = $this->getUserinfo($uid);
		model('Member')->where(array('uid' => $uid))->delete();
		return $this->success('删除用户成功！');
	}


	/**
	 * @title 用户授权
	 * @author colin <colin@tensent.cn>
	 */
	public function auth() {
		$access = model('AuthGroupAccess');
		$group  = model('AuthGroup');
		if ($this->request->isPost()) {
			$uid = input('uid', '', 'trim,intval');
			$access->where(array('uid' => $uid))->delete();
			$group_type = config('user_group_type');
			foreach ($group_type as $key => $value) {
				$group_id = input($key, '', 'trim,intval');
				if ($group_id) {
					$add = array(
						'uid'      => $uid,
						'group_id' => $group_id,
					);
					$access->save($add);
				}
			}
			return $this->success("设置成功！");
		} else {
			$uid  = input('id', '', 'trim,intval');
			$row  = $group::select();
			$auth = $access::where(array('uid' => $uid))->select();

			$auth_list = array();
			foreach ($auth as $key => $value) {
				$auth_list[] = $value['group_id'];
			}
			foreach ($row as $key => $value) {
				$list[$value['module']][] = $value;
			}
			$data = array(
				'uid'       => $uid,
				'auth_list' => $auth_list,
				'list'      => $list,
			);
			$this->assign($data);
			$this->setMeta("用户分组");
			return $this->fetch();
		}
	}

	/**
	 * @title 获取某个用户的信息
	 * @var uid 针对状态和删除启用
	 * @var pass 是查询password
	 * @var errormasg 错误提示
	 * @author colin <colin@tensent.cn>
	 */
	private function getUserinfo($uid = null, $pass = null, $errormsg = null) {
		$user = model('Member');
		$uid  = $uid ? $uid : input('id');
		//如果无UID则修改当前用户
		$uid        = $uid ? $uid : session('user_auth.uid');
		$map['uid'] = $uid;
		if ($pass != null) {
			unset($map);
			$map['password'] = $pass;
		}
		$list = $user::where($map)->field('uid,username,nickname,sex,email,qq,score,signature,status,salt')->find();
		if (!$list) {
			return $this->error($errormsg ? $errormsg : '不存在此用户！');
		}
		return $list;
	}

	/**
	 * @title 修改昵称
	 * @author huajie <banhuajie@163.com>
	 */
	public function submitNickname() {

		//获取参数
		$nickname = input('post.nickname');
		$password = input('post.password');
		if (empty($nickname)) {
			return $this->error('请输入昵称');
		}
		if (empty($password)) {
			return $this->error('请输入密码');
		}

		//密码验证
		$User = new UserApi();
		$uid  = $User->login(UID, $password, 4);
		if ($uid == -2) {
			return $this->error('密码不正确');
		}

		$Member = model('User');
		$data   = $Member->create(array('nickname' => $nickname));
		if (!$data) {
			return $this->error($Member->getError());
		}

		$res = $Member->where(array('uid' => $uid))->save($data);

		if ($res) {
			$user             = session('user_auth');
			$user['username'] = $data['nickname'];
			session('user_auth', $user);
			session('user_auth_sign', data_auth_sign($user));
			return $this->success('修改昵称成功！');
		} else {
			return $this->error('修改昵称失败！');
		}
	}

	/**
	 * @title 修改密码初始化
	 * @author huajie <banhuajie@163.com>
	 */
	public function editpwd() {
		if ($this->request->isPost()) {
			$user = model('User');
			$data = $this->request->post();

			$res = $user->editpw($data);
			if ($res) {
				return $this->success('修改密码成功！');
			} else {
				return $this->error($user->getError());
			}
		} else {
			$this->setMeta('修改密码');
			return $this->fetch();
		}
	}

	/**
	 * @title 会员状态修改
	 * @author 朱亚杰 <zhuyajie@topthink.net>
	 */
	public function changeStatus($method = null) {
		$id = array_unique((array) input('id', 0));
		if (in_array(config('user_administrator'), $id)) {
			return $this->error("不允许对超级管理员执行该操作!");
		}
		$id = is_array($id) ? implode(',', $id) : $id;
		if (empty($id)) {
			return $this->error('请选择要操作的数据!');
		}
		$map['uid'] = array('in', $id);
		switch (strtolower($method)) {
		case 'forbiduser':
			$this->forbid('Member', $map);
			break;

		case 'resumeuser':
			$this->resume('Member', $map);
			break;

		case 'deleteuser':
			$this->delete('Member', $map);
			break;

		default:
			return $this->error('参数非法');
		}
	}
}