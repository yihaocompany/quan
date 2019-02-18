<?php
// +----------------------------------------------------------------------
// | YihaoCMS [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://diao.info All rights reserved.
// +----------------------------------------------------------------------
// | Author: molong <molong@tensent.cn> <http://diao.info>
// +----------------------------------------------------------------------

namespace app\common\controller;

class Api {

	protected $data = array('code' => 0, 'msg' => '', 'time' => 0, 'data' => '');
	public $mustToken = false;     //是否检查用户行为
	protected $user      = array();    //用户信息
	protected $client;                //客户端信息
	protected $request;

	public function __construct(\think\Request $request) {
		$this->setHeader();
		$this->request = $request;
		$this->data['time'] = time();
		if ($this->request->isOptions()){
			exit('OK');
		}
		$header = $this->request->header();

		if (!$this->checkAuthor($header)) {    //检查客户端接口是否可接入
			$this->data['code'] = '301';
			$this->data['data'] = '非法请求！';
			echo json_encode($this->data);exit();
		}

		if ($this->mustToken) {
			if (!$this->checkToken($header)) {
				$this->data['code'] = '203';
				$this->data['data'] = '用户登录信息失效，请重登！';
				echo json_encode($this->data);exit();
			}
		}
	}

	public function _empty(){
		$this->data['msg'] = '空操作！';
		return $this->data;
	}

	protected function checkAuthor($header){
		if (isset($header['authorization']) && $header['authorization']) {
			list($appid, $sign) = explode('{|}', $header['authorization']);
			$this->client = db('Client')->where('appid', $appid)->find();
			if ($sign == md5($this->client['appid'].$this->client['appsecret'])) {
				return true;
			}else{
				return false;
			}
		}else{
			return false;
		}
	}

	protected function checkToken($header){
		if (isset($header['accesstoken']) && $header['accesstoken']) {
			$token = authcode($header['accesstoken']);
			list($uid, $username, $password) = explode('|', $token);
			$this->user = model('Member')->where('uid', $uid)->where('username', $username)->find();

			if ($this->user && $password === $this->user['password']) {
				return true;
			}else{
				$this->user = array();
				return false;
			}
		}else{
			return false;
		}
	}

	protected function setHeader(){
		header("Access-Control-Allow-Origin: *");
		header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
		header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Authorization, accessToken");
	}
}