<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------

namespace app\common\model;

/**
 * 表单
 */
class Form extends Base{

	protected $auto   = ['update_time'];
	protected $insert = ['name', 'create_time', 'status' => 1, 'list_grid'=>"id:ID\r\ntitle:标题\r\ncreate_time:添加时间|time_format\r\nupdate_time:更新时间|time_format"];
	protected $type   = array(
		'id'             => 'integer',
		'create_time'    => 'integer',
		'update_time'    => 'integer',
	);

	public $addField = array(
		array('name'=>'name','title'=>'标识','type'=>'text','help'=>''),
		array('name'=>'title','title'=>'标题','type'=>'text','help'=>'')
	);

	public $editField = array(
		array('name'=>'id','title'=>'ID','type'=>'hidden','help'=>''),
		array('name'=>'name','title'=>'标识','type'=>'text','help'=>''),
		array('name'=>'title','title'=>'标题','type'=>'text','help'=>''),
		array('name' => 'list_grid', 'title'=>'列表定义', 'type' => 'textarea', 'help'=>'')
	);

	protected static function init(){
		self::beforeInsert(function($event){
			$data = $event->toArray();
			$tablename = 'form_' . strtolower($data['name']);
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
					if (in_array($key, array('uid', 'status', 'view', 'create_time', 'update_time'))) {
						$fields[$key]['form_id'] = $data['id'];
					}else{
						unset($fields[$key]);
					}
				}
				model('FormAttr')->saveAll($fields);
			}
			return true;
		});
		// self::beforeUpdate(function($event){
		// 	$data = $event->toArray();
		// 	if (isset($data['attribute_sort']) && $data['attribute_sort']) {
		// 		$attribute_sort = json_decode($data['attribute_sort'], true);
			
		// 		if (!empty($attribute_sort)) {
		// 			foreach ($attribute_sort as $key => $value) {
		// 				db('FormAttr')->where('id', 'IN', $value)->setField('group_id', $key);
		// 				foreach ($value as $k => $v) {
		// 					db('FormAttr')->where('id', $v)->setField('sort', $k);
		// 				}
		// 			}
		// 		}
		// 	}
		// 	return true;
		// });
	}

	public function getStatusTextAttr($value, $data) {
		$status = array(
			0 => '禁用',
			1 => '启用',
		);
		return $status[$data['status']];
	}

}