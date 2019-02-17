<?php
namespace app\api\controller;

class Login extends \app\common\controller\Api{


	public function index(){
		$username = $this->request->param('username', '');
		$password = $this->request->param('password', '');

		if (!$username || !$password) {
			$this->data['code'] = 1;
			$this->data['msg'] = "账号密码不能为空！";
			return $this->data;
		}

		$user = db('Member')->where('username', $username)->find();
		if ($user && $user['status'] == 1) {
			if ($user['password'] == md5($password . $user['salt'])) {

				$info['access_token'] = authcode($user['uid'].'|'.$user['username'].'|'.$user['password'], 'ENCODE');
				$info['uid'] = $user['uid'];
				$info['username'] = $user['username'];
				$info['password'] = $user['password'];
				$info['avatar'] = (isset($user['avatar_url']) && $user['avatar_url']) ? $user['avatar_url'] : avatar($user['uid']);

				$this->data['data'] = $info;
				return $this->data;
			}else{
				$this->data['code'] = 1;
				$this->data['msg'] = "密码错误！";
				return $this->data;
			}
		}else{
			$this->data['code'] = 1;
			$this->data['msg'] = "无此账户或账户被禁用！";
			return $this->data;
		}
	}

	public function register(){
		$code = $this->request->post('code');
		$username = $this->request->post('username');
		$password = $this->request->post('password');
		$other['nickname'] = $this->request->post('nickname', '');
		$other['mini_openid'] = $this->request->post('openid', '');
		$other['mobile'] = $this->request->post('mobile', '');
		$other['invite'] = $this->request->post('invite', '');
		$client = $this->request->post('client', 'wxapp');

		if ($client == 'wxapp') {
			$user = db('Member')->where('mini_openid', $other['mini_openid'])->find();
			if ($user) {
				$user['access_token'] = authcode($user['uid'].'|'.$user['username'].'|'.$user['password'], 'ENCODE');
				$user['uid'] = $user['uid'];
				$user['username'] = $user['username'];
				$user['password'] = $user['password'];
				$user['avatar'] = (isset($user['avatar_url']) && $user['avatar_url']) ? $user['avatar_url'] : avatar($user['uid']);
				$this->data['data'] = $user;
				$this->data['code'] = 205;
				$this->data['msg'] = "您已注册！";
				return $this->data;
			}
		}

		//验证手机验证码
		$time = db('MobileCode')->where('code', $code)->where('mobile', $other['mobile'])->value('time');
		if (!$time || (time() - $time) > 60*1000) {
			//小于60秒则提示验证码过期
			$this->data['code'] = 1;
			$this->data['msg'] = "验证码已过期";
			return $this->data;
		}

		$result = model('Member')->register($username, $password, $password, $username.'@test.com', false, $other);
		if ($result) {
			get_coupon('client_register', $result['uid']);
			$result['access_token'] = authcode($result['uid'].'|'.$result['username'].'|'.$result['password'], 'ENCODE');
			$result['uid'] = $result['uid'];
			$result['username'] = $result['username'];
			$result['password'] = $result['password'];
			//$result['avatar'] = (isset($user['avatar_url']) && $user['avatar_url']) ? $user['avatar_url'] : avatar($user['uid']);
			$this->data['data'] = $result;
		}else{
			$this->data['msg'] = model('Member')->getError();
			$this->data['code'] = 1;
		}
		return $this->data;
	}
}