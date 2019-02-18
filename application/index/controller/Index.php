<?php
// +----------------------------------------------------------------------
// | YihaoCMS [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://diao.info All rights reserved.
// +----------------------------------------------------------------------
// | Author: molong <molong@tensent.cn> <http://diao.info>
// +----------------------------------------------------------------------

namespace app\index\controller;
use app\common\controller\Front;

class Index extends Front {

	//网站首页
	public function index() {		//设置SEO
		$this->setSeo(config('web_site_title'), config('web_site_keyword'), config('web_site_description'));
		return $this->fetch();
	}
}
