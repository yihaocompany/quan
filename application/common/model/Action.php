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
 * 分类模型
 */
class Action extends Base{

	protected function getStatusTextAttr($value, $data){
		$status = array(-1=>'删除',0=>'禁用',1=>'正常',2=>'待审核');
		return $status[$data['status']];
	}

	public $fieldlist = array(
		array('name'=>'id','title'=>'ID','type'=>'hidden'),
		array('name'=>'name','title'=>'行为标识','type'=>'text','help'=>'输入行为标识 英文字母'),
		array('name'=>'title','title'=>'行为名称','type'=>'text','help'=>'输入行为名称'),
		array('name'=>'type','title'=>'行为类型','type'=>'select','help'=>'选择行为类型','option'=>''),
		array('name'=>'remark','title'=>'行为描述','type'=>'textarea','help'=>'输入行为描述'),
		array('name'=>'rule','title'=>'行为规则','type'=>'textarea','help'=>'输入行为规则，不写则只记录日志'),
		array('name'=>'log','title'=>'日志规则','type'=>'textarea','help'=>'记录日志备注时按此规则来生成，支持[变量|函数]。目前变量有：user,time,model,record,data'),
	);

	public function _initialize(){
		parent::_initialize();
		foreach ($this->fieldlist as $key => $value) {
			if ($value['name'] == 'type') {
				$value['option'] = get_action_type(null,true);
			}
			$this->fieldlist[$key] = $value;
		}
	}
}