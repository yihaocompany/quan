<?php
namespace app\api\controller;
use Overtrue\EasySms\EasySms;

class Index extends \app\common\controller\Api{

	public $mustToken = false;

	public function getconfig(){
		$map = array(
			'status'  => 1,
			'group'   => array('NOTIN', array('0', '1', '2', '3','99'))
		);
		$this->data['config'] = model('Config')->lists($map);
		return $this->data;
	}

	public function region(){
		$pname = $this->request->param('pname');
		$map['is_show'] = 1;
		if ($pname) {
			$upid = db('District')->where(array('name'=>array('LIKE', "%".$pname."%")))->value('id');
			$map['upid'] = $upid;
			$data = db('District')->where($map)->column('name');
			$this->data['data'] = $data;
		}else{
			$map['upid'] = $this->request->param('pid', 0);
			$data = db('District')->where($map)->column('name');
			$this->data['data'] = $data;
		}
		return $this->data;
	}

	public function getcode($mobile = ''){
		if ($mobile == '') {
			$this->data['code'] = 1;
			$this->data['msg'] = "电话号码不能为空！";
		}else{
			$time = db('MobileCode')->where('mobile', $mobile)->value('time');
			if ($time && (time() - $time) < 60) {
				$this->data['code'] = 1;
				return $this->data;
			}
			$config = config('msg');
			$easySms = new EasySms($config);
			$data = array(
				'mobile'  => $mobile,
				'code'    => rand(100000, 999999),
				'time'    => time()
			);
			
			try {
				$result = $easySms->send($data['mobile'], [
						'content'  => '您本次的验证码为：' . $data['code'] . '，请在1分钟内输入。',
						'template' => '94745'
					]
				);
			} catch (Exception $e) {
				dump($e);
			}
			if ($result['qcloud']['status'] == 'success') {
				db('MobileCode')->insert($data);
			}
		}
		return $this->data;
	}
}