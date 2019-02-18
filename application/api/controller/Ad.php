<?php
// +----------------------------------------------------------------------
// | YihaoCMS [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://diao.info All rights reserved.
// +----------------------------------------------------------------------
// | Author: molong <molong@tensent.cn> <http://diao.info>
// +----------------------------------------------------------------------

namespace app\api\controller;

class Ad extends \app\common\controller\Api{


	public function lists($name = '', $is_cover = 1){
		if ($name) {
			$place_id = db('AdPlace')->where('name', $name)->value('id');

			$map['place_id'] = $place_id;
			if ($is_cover) {
				$map['cover_id'] = array('GT', 0);
			}
			$res = db('Ad')->where($map)->select();

			$list = array();
			foreach ($res as $key => $value) {
				$value['cover'] = $this->serverurl . get_cover($value['cover_id'], 'path');
				$list[] = $value;
			}
			$this->data['data'] = $list;
			return $this->data;
		}else{
			$this->data['code'] = 1;
			return $this->data;
		}
	}
}