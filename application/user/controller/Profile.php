<?php
// +----------------------------------------------------------------------
// | YihaoCMS [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.tensent.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: molong <molong@tensent.cn> <http://www.tensent.cn>
// +----------------------------------------------------------------------

namespace app\user\controller;
use app\common\controller\User;

class Profile extends User{

	//修改资料
	public function index(){
		$user = model('User');
		if (IS_POST) {
			$result = $user->editUser($this->request->post());
			if ($result !== false) {
				return $this->success("更新成功！", "");
			}else{
				return $this->error($user->getError(), '');
			}
		}else{
			$group['基础资料'] = $user->useredit;
			$group['扩展信息'] = $user->userextend;

			$info = $user->where(array('uid'=>session('user_auth.uid')))->find();

			if ($info->extend) {
				$info = array_merge($info->toArray(), $info->extend->toArray());
			}
			$data = array(
				'fieldGroup' => $group,
				'info'       => $info
			);
			$this->assign($data);
			return $this->fetch('public/edit');
		}
	}

	//修改密码
	public function editpw(){
		$user = model('User');
		if (IS_POST) {
			$result = $user->editpw($this->request->post());
			if ($result !== false) {
				return $this->success("更新成功！", "");
			}else{
				return $this->error($user->getError(), '');
			}
		}else{
			return $this->fetch();
		}
	}

	//修改头像
	public function avatar(){
		return $this->fetch();
	}
}
