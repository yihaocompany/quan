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
class AuthRule extends Base{

	const rule_url = 1;
	const rule_mian = 2;

	protected $type = array(
		'id'    => 'integer',
	);

	public $keyList = array(
		array('name'=>'module','title'=>'所属模块','type'=>'hidden'),
		array('name'=>'title','title'=>'节点名称','type'=>'text','help'=>''),
		array('name'=>'name','title'=>'节点标识','type'=>'text','help'=>''),
		array('name'=>'group','title'=>'功能组','type'=>'text','help'=>'功能分组'),
		array('name'=>'status','title'=>'状态','type'=>'select','option'=>array('1'=>'启用','0'=>'禁用'),'help'=>''),
		array('name'=>'condition','title'=>'条件','type'=>'text','help'=>'')
	);

	public $filter_method = array('__construct', 'execute', 'login', 'sqlSplit', 'isMobile', 'is_wechat', '_initialize');

	public function uprule($type){
		$data = $this->updaterule($type);
		foreach ($data as $value) {
			$id = $this->where(array('name' => $value['name']))->value('id');
			if ($id) {
				$value['id'] = $id;
			}
			$list[] = $value;
		}
		return $this->saveAll($list);
	}

	public function updaterule($type){
		$path = APP_PATH . $type . '/controller';
		$classname = $this->scanFile($path);
		foreach ($classname as $value) {
			$class = "\\app\\" . $type . "\\controller\\" . $value;
			if(class_exists($class)){
				$reflection = new \ReflectionClass($class);
				$group_doc = $this->Parser($reflection->getDocComment());
				$method = $reflection->getMethods(\ReflectionMethod::IS_PUBLIC);
				foreach ($method as $key => $v) {
					if (!in_array($v->name, $this->filter_method)) {
						$title_doc = $this->Parser($v->getDocComment());
						if (isset($title_doc['title']) && $title_doc['title']) {
							$list[] = array(
								'module' => $type,
								'type' => 2,
								'name' => $type . '/' . strtolower($value) . '/' . strtolower($v->name),
								'title' => trim($title_doc['title']),
								'group' => (isset($group_doc['title']) && $group_doc['title']) ? trim($group_doc['title']) : '',
								'status' => 1
							);
						}
					}
				}
			}
		}

		return $list;
	}

	protected function scanFile($path){
		$result = array();
		$files = scandir($path);
		foreach ($files as $file) {
			if ($file != '.' && $file != '..') {
				if (is_dir($path . '/' . $file)) {
					$this->scanFile($path . '/' . $file);
				} else {
					$result[] = substr(basename($file), 0 , -4);
				}
			}
		}
		return $result;
	}

	protected function Parser($text){
		$doc = new \doc\Doc();
		return $doc->parse($text);
	}
}