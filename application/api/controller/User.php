<?php
namespace app\api\controller;

class User extends \app\common\controller\Api{
	
	public $mustToken = true;

	//获取用户账户信息
	public function accountinfo() {
		$extend = db('MemberExtend');

		$info = $extend->where('uid', $this->user['uid'])->find();
		if (!$info) {
			$extend->insert(array('uid'=>$this->user['uid']));
			$info = $extend->where('uid', $this->user['uid'])->find();
		}
		$info['coupon_count'] = db('MemberCoupon')->where('uid', $this->user['uid'])->where('is_used', 0)->count();
		$info['level'] = db('MemberLevel')->where('bomlimit', 'ELT', $info['point'])->where('toplimit', 'EGT', $info['point'])->find();
		$info['group'] = db('AuthGroupAccess')->where('uid', $this->user['uid'])->value('group_id');

		$this->data['data'] = $info;
		return $this->data;
	}

	public function getuserphone(){
		$this->data['data'] = $this->user['mobile'];
		return $this->data;
	}
}