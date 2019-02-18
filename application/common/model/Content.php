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
class Content extends BaseModel{
	
	protected $type = array(
		'create_time'   => 'integer',
		'update_time'   => 'integer',
	);
}