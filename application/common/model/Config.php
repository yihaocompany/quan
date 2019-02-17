<?php
// +----------------------------------------------------------------------
// | YihaoCMS [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.tensent.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: molong <molong@tensent.cn> <http://www.tensent.cn>
// +----------------------------------------------------------------------

namespace app\common\model;

/**
* 设置模型
*/
class Config extends Base{
	
	protected $type = array(
		'id'  => 'integer',
	);

	protected $auto = array('name', 'update_time', 'status'=>1);
	protected $insert = array('create_time');

    protected function setNameAttr($value){
        return strtolower($value);
    }

    protected function getTypeTextAttr($value, $data){
    	$type = config('config_type_list');
    	$type_text = explode(',', $type[$data['type']]);
        return $type_text[0];
    }

	public function lists(){
		$map    = array('status' => 1);
		$data   = $this->db()->where($map)->field('type,name,value')->select();

		$config = array();
		if($data && is_array($data)){
			foreach ($data as $value) {
				$config[$value['name']] = $this->parse($value['type'], $value['value']);
			}
		}
		return $config;
	}

	/**
	 * 根据配置类型解析配置
	 * @param  integer $type  配置类型
	 * @param  string  $value 配置值
	 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
	 */
	private function parse($type, $value){
		switch ($type) {
			case 'textarea': //解析数组
			$array = preg_split('/[,;\r\n]+/', trim($value, ",;\r\n"));
			if(strpos($value,':')){
				$value  = array();
				foreach ($array as $val) {
					$list = explode(':', $val);
					if(isset($list[2])){
						$value[$list[0]]   = $list[1].','.$list[2];
					}else{
						$value[$list[0]]   = $list[1];
					}
				}
			}else{
				$value =    $array;
			}
			break;
		}
		return $value;
	}

	public function getThemesList(){
		$files = array();
		$files['pc'] = $this->getList('pc');
		$files['mobile'] = $this->getList('mobile');
		return $files;
	}

	protected function getList($type){
		$path = './template/';
		$file  = opendir($path);
		while (false !== ($filename = readdir($file))) {
			if (!in_array($filename, array('.', '..'))) {
				$files = $path . $filename . '/info.php';
				if (is_file($files)) {
					$info = include($files);
					if (isset($info['type']) && $info['type'] == $type) {
						$info['id']  = $filename;
						$info['images'] = '/template/' . $filename . '/' . $info['images'];
						$list[] = $info;
					}else{
						continue;
					}
				}
			}
		}
		return isset($list) ? $list : array();
	}
}