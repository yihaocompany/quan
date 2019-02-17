<?php
namespace app\api\controller;

use EasyWeChat\Factory;

/**
 * 微信类接口
 */
class Wechat extends \app\common\controller\Api{

	public function onLogin() {
		$app = Factory::miniProgram(array(
			'app_id' => 'wxbcf7b64b8dc6ca72',
			'secret' => '01f8b10956ea44e6a726f1391af6e3d8',
		));

		$info = $app->auth->session($this->request->param('code'));

		$this->data['data'] = $info;
		
		return json($this->data);
	}

	public function jscode2session(){
		$app = Factory::miniProgram(config('wechat.miniProgram'));
		$param = $this->request->param();

		$info = $app->auth->session($param['jsCode']);

		if (isset($info['openid']) && $info['openid']) {
			//查询用户是否已添加
			$user = db('Member')->where('mini_openid', $info['openid'])->find();
			//if (!$user) {
				// $other = array(
				// 	'avatar_url' => $param['avatar'],
				// 	'nickname' => $param['nickname'],
				// 	'openid' => $info['openid']
				// );
				// $user = model('Member')->register($param['nickname'], $info['openid'], $info['openid'], $info['openid'].'@wxapp.com', false, $other);
			// }else{
			// 	model('Member')->where('openid', $info['openid'])->setField('avatar_url', $param['avatar']);
			// }
			if ($user) {
				model('Member')->where('mini_openid', $info['openid'])->setField('wechat_avatar', $param['avatar']);
				$info['access_token'] = authcode($user['uid'].'|'.$user['username'].'|'.$user['password'], 'ENCODE');
				$info['uid'] = $user['uid'];
			}
			$this->data['data'] = $info;
		}else{
			$this->data['code'] = 1;
			$this->data['msg'] = '非法操作！';
		}
		
		return json($this->data);
	}
}