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
class Model extends Base {

	protected $auto   = ['update_time'];
	protected $insert = ['name', 'create_time', 'status' => 1, 'list_grid'=>"id:ID\r\ntitle:标题\r\ncreate_time:添加时间|time_format\r\nupdate_time:更新时间|time_format"];
	protected $type   = array(
		'id'             => 'integer',
		'create_time'    => 'integer',
		'update_time'    => 'integer',
	);

	protected static function init(){
		self::beforeInsert(function($event){
			$data = $event->toArray();
			$tablename = strtolower($data['name']);
			//实例化一个数据库操作类
			$db = new \com\Datatable();
			//检查表是否存在并创建
			if (!$db->CheckTable($tablename)) {
				//创建新表
				return $db->initTable($tablename, $data['title'], 'id')->query();
			}else{
				return false;
			}
		});
		self::afterInsert(function($event){
			$data = $event->toArray();
			
			$fields = include(APP_PATH.'admin/fields.php');
			if (!empty($fields)) {
				foreach ($fields as $key => $value) {
					if ($data['is_doc']) {
						$fields[$key]['model_id'] = $data['id'];
					}else{
						if (in_array($key, array('uid', 'status', 'view', 'create_time', 'update_time'))) {
							$fields[$key]['model_id'] = $data['id'];
						}else{
							unset($fields[$key]);
						}
					}
				}
				model('Attribute')->saveAll($fields);
			}
			return true;
		});
		self::beforeUpdate(function($event){
			$data = $event->toArray();
			if (isset($data['attribute_sort']) && $data['attribute_sort']) {
				$attribute_sort = json_decode($data['attribute_sort'], true);
			
				if (!empty($attribute_sort)) {
					foreach ($attribute_sort as $key => $value) {
						db('Attribute')->where('id', 'IN', $value)->setField('group_id', $key);
						foreach ($value as $k => $v) {
							db('Attribute')->where('id', $v)->setField('sort', $k);
						}
					}
				}
			}
			return true;
		});
	}

	protected function setAttributeSortAttr($value){
		return $value ? json_encode($value) : '';
	}

	public function setNameAttr($value) {
		return strtolower($value);
	}

	public function getStatusTextAttr($value, $data) {
		$status = array(
			0 => '禁用',
			1 => '启用',
		);
		return $status[$data['status']];
	}

	public function del() {
		$id        = input('id', '', 'trim,intval');
		$tablename = $this->where('id', $id)->value('name');

		//删除数据表
		$db = new \com\Datatable();
		if ($db->CheckTable($tablename)) {
			//检测表是否存在
			$result = $db->delTable($tablename)->query();
			if (!$result) {
				return false;
				$this->error = "数据表删除失败！";
			}
		}
		db('Attribute')->where('model_id', $id)->delete(); //删除字段信息
		$result = $this->where('id', $id)->delete();
		if ($result) {
			return true;
		} else {
			$this->error = "模型删除失败！";
			return false;
		}
	}

	public function attribute() {
		return $this->hasMany('Attribute');
	}
}