<?php
namespace app\common\model;

class Member extends Base {

	protected $createTime = 'reg_time';
	protected $updateTime = 'last_login_time';

	protected $insert = array('status'=>1, 'reg_time');

	protected $type = array(
		'uid'  => 'integer',
		'reg_time' => 'integer'
	);
	
	public $editfield = array(
		array('name'=>'uid','type'=>'hidden'),
		array('name'=>'username','title'=>'用户名','type'=>'readonly','help'=>''),
		array('name'=>'nickname','title'=>'昵称','type'=>'text','help'=>''),
		array('name'=>'password','title'=>'密码','type'=>'password','help'=>'为空时则不修改'),
		array('name'=>'sex','title'=>'性别','type'=>'select','option'=>array('0'=>'保密','1'=>'男','2'=>'女'),'help'=>''),
		array('name'=>'email','title'=>'邮箱','type'=>'text','help'=>'用户邮箱，用于找回密码等安全操作'),
		array('name'=>'qq','title'=>'QQ','type'=>'text','help'=>''),
		array('name'=>'score','title'=>'用户积分','type'=>'text','help'=>''),
		array('name'=>'signature','title'=>'用户签名','type'=>'textarea','help'=>''),
		array('name'=>'status','title'=>'状态','type'=>'select','option'=>array('0'=>'禁用','1'=>'启用'),'help'=>''),
	);

	public $addfield = array(
		array('name'=>'username','title'=>'用户名','type'=>'text','help'=>'用户名会作为默认的昵称'),
		array('name'=>'password','title'=>'密码','type'=>'password','help'=>'用户密码不能少于6位'),
		array('name'=>'repassword','title'=>'确认密码','type'=>'password','help'=>'确认密码'),
		array('name'=>'email','title'=>'邮箱','type'=>'text','help'=>'用户邮箱，用于找回密码等安全操作'),
	);
    
	public $useredit = array(
		array('name'=>'uid','type'=>'hidden'),
		array('name'=>'nickname','title'=>'昵称','type'=>'text','help'=>''),
		array('name'=>'sex','title'=>'性别','type'=>'select','option'=>array('0'=>'保密','1'=>'男','2'=>'女'),'help'=>''),
		array('name'=>'email','title'=>'邮箱','type'=>'text','help'=>'用户邮箱，用于找回密码等安全操作'),
		array('name'=>'mobile','title'=>'联系电话','type'=>'text','help'=>''),
		array('name'=>'qq','title'=>'QQ','type'=>'text','help'=>''),
		array('name'=>'signature','title'=>'用户签名','type'=>'textarea','help'=>''),
	);

	public $userextend = array(
		array('name'=>'company','title'=>'单位名称','type'=>'text','help'=>''),
		array('name'=>'company_addr','title'=>'单位地址','type'=>'text','help'=>''),
		array('name'=>'company_contact','title'=>'单位联系人','type'=>'text','help'=>''),
		array('name'=>'company_zip','title'=>'单位邮编','type'=>'text','help'=>''),
		array('name'=>'company_depart','title'=>'所属部门','type'=>'text','help'=>''),
		array('name'=>'company_post','title'=>'所属职务','type'=>'text','help'=>''),
		array('name'=>'company_type','title'=>'单位类型','type'=>'select', 'option'=>'', 'help'=>''),
	);

	protected function setStatusAttr($value){
		return 1;
	}

	protected function setPasswordAttr($value, $data){
		return md5($value.$data['salt']);
	}
	
	protected function getGroupListAttr($value, $data){
		$sql = db('AuthGroupAccess')->where('uid', $data['uid'])->fetchSql(true)->column('group_id');
		return db('AuthGroup')->where('id in ('.$sql.')')->column('title', 'id');
	}

	/**
	 * 用户登录模型
	 * @param  string  $username [description]
	 * @param  string  $password [description]
	 * @param  integer $type     登录类型，1为用户名登录2为邮箱登录3为手机登录4为用户ID登录5为微信登录
	 * @return [type]            [description]
	 */
	public function login($username = '', $password = '', $type = 1) {
		$map = array();
		if (\think\Validate::is($username, 'email')) {
			$type = 2;
		} elseif (preg_match("/^1[34578]{1}\d{9}$/", $username)) {
			$type = 3;
		}
		switch ($type) {
			case 1:
				$map['username'] = $username;
				break;
			case 2:
				$map['email'] = $username;
				break;
			case 3:
				$map['mobile'] = $username;
				break;
			case 4:
				$map['uid'] = $username;
				break;
			case 5:
				$map['openid'] = $username;
				break;
			default:
				return 0; 	//参数错误
		}
		if (!$username) {
			return false;
		}

		$user = $this->where($map)->find();
		if (isset($user['uid']) && $user['uid'] && $user['status']) {
			if ($type == 3) {
				//手机验证手机动态密码
				if ($password == session('mobile_login_code')) {
					$this->autoLogin($user); //更新用户登录信息
					return $user['uid'];
				}else{
					return -5;
				}
			} elseif ($type == 5) {
				$this->autoLogin($user); //更新用户登录信息
				return $user['uid'];
			} else {
				/* 验证用户密码 */
				if (md5($password . $user['salt']) === $user['password']) {
					$this->autoLogin($user); //更新用户登录信息
					return $user['uid']; //登录成功，返回用户ID
				} else {
					return -2; //密码错误
				}
			}
		} else {
			if ($type == 3 && preg_match("/^1[34578]{1}\d{9}$/", $username) && $password == session('mobile_login_code')) {
				$data = array(
					'username' => $username,
					'mobile'   => $username,
					'salt'     => rand_string(6),
					'password' => $password,
				);
				$result = $this->save($data);
				if ($result) {
					$this->autoLogin($this->data); //更新用户登录信息
				}
				return $this->data['uid'];
			} else {
				return -1; //用户不存在或被禁用
			}
		}
	}


	/**
	 * 用户注册
	 * @param  integer $user 用户信息数组
	 */
	function register($username, $password, $repassword, $email, $isautologin = true, $other = array()){
		$data['username'] = $username;
		$data['salt'] = rand_string(6);
		$data['password'] = $password;
		$data['repassword'] = $repassword;
		$data['email'] = $email;
		if (!empty($other)) {
			$data = array_merge($data, $other);
		}
		$result = $this->validate(true)->save($data);
		if ($result) {
			$data['uid'] = $this->data['uid'];
			if ($isautologin) {
				$this->autoLogin($this->data);
			}
			return $data;
		}else{
			if (!$this->getError()) {
				$this->error = "注册失败！";
			}
			return false;
		}
	}

	/**
	 * 自动登录用户
	 * @param  integer $user 用户信息数组
	 */
	private function autoLogin($user){
		/* 更新登录信息 */
		$data = array(
			'uid'             => $user['uid'],
			'login'           => array('inc', 'login', 1),
			'last_login_time' => time(),
			'last_login_ip'   => get_client_ip(1),
		);
		$this->where(array('uid'=>$user['uid']))->update($data);
		$user = $this->where(array('uid'=>$user['uid']))->find();

		/* 记录登录SESSION和COOKIES */
		$auth = array(
			'uid'             => $user['uid'],
			'username'        => $user['username'],
			'last_login_time' => $user['last_login_time'],
		);

		session('user_auth', $auth);
		session('user_auth_sign', data_auth_sign($auth));
	}

	public function logout(){
		session('user_auth', null);
		session('user_auth_sign', null);
	}

	public function getInfo($uid){
		$data = $this->where(array('uid'=>$uid))->find();
		return $data;
	}

	/**
	 * 修改用户资料
	 */
	public function editUser($data, $ischangepwd = false){
		if ($data['uid']) {
			if (!$ischangepwd || ($ischangepwd && $data['password'] == '')) {
				unset($data['salt']);
				unset($data['password']);
			}else{
				$data['salt'] = rand_string(6);
			}
			$result = $this->validate('member.edit')->save($data, array('uid'=>$data['uid']));
			if ($result) {
				return $this->extend->save($data, array('uid'=>$data['uid']));
			}else{
				return false;
			}
		}else{
			$this->error = "非法操作！";
			return false;
		}
	}

	public function editpw($data, $is_reset = false){
		$uid = $is_reset ? $data['uid'] : session('user_auth.uid');
		if (!$is_reset) {
			//后台修改用户时可修改用户密码时设置为true
			$this->checkPassword($uid,$data['oldpassword']);

			$validate = $this->validate('member.password');
			if (false === $validate) {
				return false;
			}
		}

		$data['salt'] = rand_string(6);

		return $this->save($data, array('uid'=>$uid));
	}

	protected function checkPassword($uid,$password){
		if (!$uid || !$password) {
			$this->error = '原始用户UID和密码不能为空';
			return false;
		}

		$user = $this->where(array('uid'=>$uid))->find();
		if (md5($password.$user['salt']) === $user['password']) {
			return true;
		}else{
			$this->error = '原始密码错误！';
			return false;
		}
	}

	public function extend(){
		return $this->hasOne('MemberExtend', 'uid');
	}
}