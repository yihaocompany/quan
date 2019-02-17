<?php
// +----------------------------------------------------------------------
// | YihaoCMS [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.tensent.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: molong <molong@tensent.cn> <http://www.tensent.cn>
// +----------------------------------------------------------------------

namespace app\api\controller\user;

use EasyWeChat\Factory;

class Wechat extends \app\common\controller\Api{

	public $mustToken = true;

	public function getqrcode(){
		$app = Factory::miniProgram(config('wechat.miniProgram'));
		$param = $this->request->param();

		$response = $app->app_code->getUnlimit('invite_' . $this->user['uid'], array(
			'page'       => 'pages/register',
			'auto_color' => true
		));

		if ($response) {
			$filename = $response->saveAs('./uploads/qrcode', 'appcode_' . $this->user['uid'] . '.png');
			$this->data['data'] = $this->serverurl . '/uploads/qrcode/' . $filename;
		}else{
			$this->data['code'] = 1;
			$this->data['msg'] = $response;
		}
		return $this->data;
	}
}