<?php
// +----------------------------------------------------------------------
// | YihaoCMS [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.tensent.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: molong <molong@tensent.cn> <http://www.tensent.cn>
// +----------------------------------------------------------------------

namespace app\user\controller;
use app\common\controller\Front;

class Login extends Front{

	public function index($username = '', $password = '', $verify = ''){
		if ($this->request->isPost()) {
			if (!$username || !$password) {
				return $this->error('用户名或者密码不能为空！','');
			}
			//验证码验证
			$this->checkVerify($verify);
			$user = model('User');
			$uid = $user->login($username,$password);
			if ($uid > 0) {
				$url = session('http_referer') ? session('http_referer') : url('user/index/index');
				return $this->success('登录成功！', $url);
			}else{
				switch($uid) {
					case -1: $error = '用户不存在或被禁用！'; break; //系统级别禁用
					case -2: $error = '密码错误！'; break;
					default: $error = '未知错误！'; break; // 0-接口参数错误（调试阶段使用）
				}
				return $this->error($error,'');
			}
		}else{
			session('http_referer', isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '');
			if (is_login()) {
				return $this->redirect('user/index/index');
			}else{
				return $this->fetch();
			}
		}
	}

	public function logout(){
		model('User')->logout();
		return $this->redirect('index/index/index');
	}

	public function register($username = '', $password = '', $repassword = '', $email = '', $verify = ''){
		if ($this->request->isPost()) {
			$user = model('User');
			
			//验证码验证
			$this->checkVerify($verify);

			if ($username == '' || $password == '' || $repassword == '') {
				return $this->error("请填写完整注册信息！", '');
			}
			$result = $user->register($username, $password, $repassword, $email);
			if ($result) {
				return $this->success('注册成功！', url('user/index/index'));
			}else{
				return $this->error($user->getError(), '');
			}
		}else{
			if (is_login()) {
				$this->redirect('user/index/index');
			}
			return $this->fetch();
		}
	}

	public function forget($email = '', $verify = ''){
		if ($this->request->isPost()) {
			//验证码验证
			$this->checkVerify($verify);
			if (!$email) {
				return $this->error('邮件必填！', url('index/index/index'));
			}
			$result = false;
			$user = db('Member')->where(array('email'=>$email))->find();
			if (!empty($user)){
				$time = time();
				$token = authcode($user['uid'] . "\n\r" . $user['email'] . "\n\r" . $time, 'ENCODE');
				config('url_common_param', true);
				$url = url('user/login/find',array('time'=>$time, 'token'=>$token), 'html', true);
				$html = \think\Lang::get('find_password', array('url'=>$url));

				$result = send_email($user['email'], '找回密码确认邮件', $html);
			}
			if ($result) {
				return $this->success("已发送邮件至您邮箱，请登录您的邮箱！", url('index/index/index'));
			}else{
				return $this->error('发送失败！', '');
			}
		}else{
			return $this->fetch();
		}
	}

	public function find(){
		if ($this->request->isPost()) {
			$data = $this->request->post();
			//验证码验证
			$this->checkVerify($data['verify']);
			if ($data['password'] !== $data['repassword']) {
				return $this->error('确认密码和密码不同！','');
			}

			$token_decode = authcode($data['token']);
			list($uid, $email, $time) = explode("\n\r", $token_decode);
			
			$save['salt'] = rand_string(6);
			$save['password'] = md5($data['password'].$save['salt']);
			$result = db('Member')->where(array('uid'=>$uid))->update($save);
			if (false != $result) {
				return $this->success('重置成功！');
			}else{
				return $this->success('重置失败！');
			}
		}else{
			$time = input('get.time', '', 'trim');
			$token = input('get.token', '', 'trim');
			if (!$time || !$token) {
				return $this->error('参数错误！','');
			}

			$token_decode = authcode($token);
			list($uid, $email, $time) = explode("\n\r", $token_decode);

			if ((time() - $time) > 3600 || (time() - $time) < 0) {
				return $this->error('链接已失效！', '');
			}
			if ($time != $time) {
				return $this->error('非法操作！', '');
			}

			$data = array(
				'token'  => $token,
				'email'  => $email,
				'uid'  => $uid,
			);
			$this->assign($data);
			return $this->fetch();
		}
	}
}
