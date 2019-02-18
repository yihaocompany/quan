<?php
// +----------------------------------------------------------------------
// | YihaoCMS [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://diao.info All rights reserved.
// +----------------------------------------------------------------------
// | Author: molong <molong@tensent.cn> <http://diao.info>
// +----------------------------------------------------------------------

namespace app\common\controller;

class User extends Front {

	public function _initialize() {
		parent::_initialize();

		if (!is_login() and !in_array($this->url_path, array('user/login/index', 'user/index/verify'))) {
			return $this->redirect('user/login/index');
		} elseif (is_login()) {
			$user = model('User')->getInfo(session('user_auth.uid'));
			// if (!$this->checkProfile($user) && $this->url !== 'user/profile/index') {
			// 	return $this->error('请补充完个人资料！', url('user/profile/index'));
			// }
			$this->assign('user', $user);

			//设置会员中心菜单
			//$this->setMenu();
		}

		if ($this->is_wechat() && !session('wechat_user')) {
			$user = & load_wechat('User');
			$wechat_user = $user->getUserInfo($this->wechat_oauth['openid']);
			//更新用户信息
			session('wechat_user', $wechat_user);
		}
		
		$this->assign('wechat_user', session('wechat_user'));
	}

	protected function setMenu() {
		$menu['基础设置'] = array(
			array('title' => '个人资料', 'url' => 'user/profile/index', 'icon' => 'newspaper-o'),
			array('title' => '密码修改', 'url' => 'user/profile/editpw', 'icon' => 'key'),
			array('title' => '更换头像', 'url' => 'user/profile/avatar', 'icon' => 'male'),
		);
		$contetnmenu = $this->getContentMenu();
		if (!empty($contetnmenu)) {
			$menu['内容管理'] = $contetnmenu;
		}

		foreach ($menu as $group => $item) {
			foreach ($item as $key => $value) {
				if (url($value['url']) == $_SERVER['REQUEST_URI']) {
					$value['active'] = 'active';
				} else {
					$value['active'] = '';
				}
				$menu[$group][$key] = $value;
			}
		}
		$this->assign('__MENU__', $menu);
	}

	protected function getContentMenu() {
		$list = array();
		$map  = array(
			'status'       => array('gt', 0)
		);
		$list = db('Model')->where($map)->field("name,id,title,icon,'' as 'style'")->select();

		foreach ($list as $key => $value) {
			$value['url']   = "user/content/index?model_id=" . $value['id'];
			$value['title'] = $value['title'] . "管理";
			$value['icon']  = $value['icon'] ? $value['icon'] : 'file';
			$list[$key]     = $value;
		}
		return $list;
	}

	protected function checkProfile($user) {
		$result = true;
		//判断用户资料是否填写完整
		if (!$user['nickname'] || !$user['qq']) {
			$result = false;
		}
		return $result;
	}
}
