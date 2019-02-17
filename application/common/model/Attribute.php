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
class Attribute extends Base{

	protected $type = array(
		'id'  => 'integer',
	);

	protected static function init(){
		self::afterInsert(function($data){
			if ($data['model_id']) {
				$name = db('Model')->where('id', $data['model_id'])->value('name');
				$db = new \com\Datatable();
				$attr = $data->toArray();
				$model_attr = array(
					'model_id' => $data['model_id'],
					'attr_id'  => $data->id,
					'group_id' => 0,
					'is_add_table'  => 1,
					'is_show'  => $data['is_show'],
					'is_must'  => $data['is_must'],
					'sort' => 0,
				);
				$attr['after'] = db('Attribute')->where('name', '<>', $data['name'])->where('model_id', $data['model_id'])->order('id desc')->value('name');
				return $db->columField(strtolower($name), $attr)->query();
			}
		});
		self::beforeUpdate(function($data){
			$attr = $data->toArray();
			$attr['action'] = 'CHANGE';
			$attr['oldname'] = db('Attribute')->where('id', $attr['id'])->value('name');
			if ($attr['id']) {
				$name = db('Model')->where('id', $attr['model_id'])->value('name');
				$db = new \com\Datatable();
				return $db->columField(strtolower($name), $attr)->query();
			}else{
				return false;
			}
		});
	}

	protected function getTypeTextAttr($value, $data){
    	$type = config('config_type_list');
    	$type_text = explode(',', $type[$data['type']]);
        return $type_text[0];
	}

	public function getFieldlist($map,$index='id'){
		$list = array();
		$row = db('Attribute')->field('*,remark as help,type,extra as "option",model_id')->where($map)->order('group_id asc, sort asc')->select();
		foreach ($row as $key => $value) {
			if (in_array($value['type'],array('checkbox','radio','select','bool'))) {
				$value['option'] = parse_field_attr($value['extra']);
			} elseif ($value['type'] == 'bind') {
				$extra = parse_field_bind($value['extra'], '', $value['model_id']);
				$option = array();
				foreach ($extra as $k => $v) {
					$option[$v['id']] = $v['title_show'];
				}
				$value['option'] = $option;
			}
			$list[$value['id']] = $value;
		}
		return $list;
	}

	public function del($id, $model_id){
		$map['id'] = $id;
		$info = $this->find($id);
		$tablename = db('Model')->where(array('id'=>$model_id))->value('name');

		//先删除字段表内的数据
		$result = $this->where($map)->delete();
		if ($result) {
			$tablename = strtolower($tablename);
			//删除模型表中字段
			$db = new \com\Datatable();
			if (!$db->CheckField($tablename,$info['name'])) {
				return true;
			}
			$result = $db->delField($tablename,$info['name'])->query();
			if ($result) {
				return true;
			}else{
				$this->error = "删除失败！";
				return false;
			}
		}else{
			$this->error = "删除失败！";
			return false;
		}
	}
}