<?php
// +----------------------------------------------------------------------
// | YihaoCMS [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://diao.info All rights reserved.
// +----------------------------------------------------------------------
// | Author: molong <molong@tensent.cn> <http://diao.info>
// +----------------------------------------------------------------------

namespace app\common\model;

/**
* 设置模型
*/
class AuthGroup extends Base{

    const TYPE_ADMIN                = 1;                   // 管理员用户组类型标识
    const MEMBER                    = 'member';
    const AUTH_GROUP_ACCESS         = 'auth_group_access'; // 关系表表名
    const AUTH_EXTEND               = '__AUTH_EXTEND__';       // 动态权限扩展信息表
    const AUTH_GROUP                = 'auth_group';        // 用户组表名
    const AUTH_EXTEND_CATEGORY_TYPE = 1;              // 分类权限标识
    const AUTH_EXTEND_MODEL_TYPE    = 2; //分类权限标识

	protected $type = array(
		'id'  => 'integer',
	);

	public $keyList = array(
		array('name'=>'id', 'title'=>'ID', 'type'=>'hidden', 'help'=>'', 'option'=>''),
		array('name'=>'module', 'title'=>'所属模块', 'type'=>'hidden', 'help'=>'', 'option'=>''),
		array('name'=>'title', 'title'=>'用户组名', 'type'=>'text', 'help'=>'', 'option'=>''),
		array('name'=>'description', 'title'=>'分组描述', 'type'=>'textarea', 'help'=>'', 'option'=>''),
		array('name'=>'status', 'title'=>'状态', 'type'=>'select', 'help'=>'', 'option'=>array(
			0 => '禁用',
			1 => '启用'
		)),
	);

	public function change(){
		$data = input('post.');
		if ($data['id']) {
			$result = $this->save($data, array('id'=>$data['id']));
		}else{
			$result = $this->save($data);
		}
		if (false !== $result) {
			return true;
		}else{
			$this->error = "失败！";
			return false;
		}
	}

	/**
	 * 返回用户拥有管理权限的分类id列表
	 * 
	 * @param int     $uid  用户id
	 * @return array
	 *  
	 *  array(2,4,8,13) 
	 *
	 * @author 朱亚杰 <zhuyajie@topthink.net>
	 */
	static public function getAuthModels($uid){
		return self::getAuthExtend($uid,self::AUTH_EXTEND_MODEL_TYPE,'AUTH_MODEL');
	}

	/**
	 * 返回用户拥有管理权限的分类id列表
	 * 
	 * @param int     $uid  用户id
	 * @return array
	 *  
	 *  array(2,4,8,13) 
	 *
	 * @author 朱亚杰 <zhuyajie@topthink.net>
	 */
	static public function getAuthCategories($uid){
		return self::getAuthExtend($uid,self::AUTH_EXTEND_CATEGORY_TYPE,'AUTH_CATEGORY');
	}

	/**
	 * 返回用户拥有管理权限的扩展数据id列表
	 * 
	 * @param int     $uid  用户id
	 * @param int     $type 扩展数据标识
	 * @param int     $session  结果缓存标识
	 * @return array
	 *  
	 *  array(2,4,8,13) 
	 *
	 * @author 朱亚杰 <xcoolcc@gmail.com>
	 */
	static public function getAuthExtend($uid,$type,$session){
		if ( !$type ) {
			return false;
		}
		if ( $session ) {
			$result = session($session);
		}
		if ( $uid == session('user_auth.uid') && !empty($result) ) {
			return $result;
		}
		$result = db(self::AUTH_GROUP_ACCESS)->alias('g')
			->join(self::AUTH_EXTEND.' c', 'g.group_id=c.group_id')
			->where("g.uid='$uid' and c.type='$type' and !isnull(extend_id)")
			->column('extend_id');
		if ( $uid == session('user_auth.uid') && $session ) {
			session($session,$result);
		}
		return $result;
	}
}