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
class WebConfig extends Base{

	protected $auto = array('update_at');
	protected $insert = array('create_at');
    public $keyList = array(
        array('name'=>'id' ,'title'=>'ID', 'type'=>'hidden'),
        array('name'=>'webname' ,'title'=>'网站名称', 'type'=>'text', 'help'=>''),
        array('name'=>'title' ,'title'=>'网站title', 'type'=>'text', 'help'=>''),
        array('name'=>'domain' ,'title'=>'域名', 'type'=>'text', 'help'=>''),
        array('name'=>'template' ,'title'=>'电脑模版', 'type'=>'select', 'option'=>array(
        ), 'help'=>''),
        array('name'=>'mobile_template' ,'title'=>'手机模板', 'type'=>'select', 'option'=>array(
        ), 'help'=>''),
        array('name'=>'logo' ,'title'=>'网站LOGO', 'type'=>'image', 'help'=>''),
        array('name'=>'status' ,'title'=>'状态', 'type'=>'select','option'=>array('1'=>'启用','0'=>'禁用'), 'help'=>''),
        array('name'=>'keywords' ,'title'=>'关键字', 'type'=>'textarea', 'help'=>''),
        array('name'=>'description' ,'title'=>'描述', 'type'=>'textarea', 'help'=>'')
    );


    protected $type = array(
        'id'  => 'integer'
    );
}
