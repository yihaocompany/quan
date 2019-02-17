<?php
namespace app\common\model;

use think\Model;

class Base extends Model {
	protected $param;
	protected $type = array(
		'id'  => 'integer',
		'cover_id'  => 'integer',
	);

	public function initialize(){
		parent::initialize();
		$this->param = \think\Request::instance()->param();
	}

	/**
	 * 数据修改
	 * @return [bool] [是否成功]
	 */
	public function change(){
		$data = \think\Request::instance()->post();
		if (isset($data['id']) && $data['id']) {
			return $this->save($data, array('id'=>$data['id']));
		}else{
			return $this->save($data);
		}
	}
}