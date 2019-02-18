<?php
namespace app\common\controller;
use think\Cache;
class Front extends Base{

	public function _initialize(){
		parent::_initialize();
		//设置主题信息
		$this->setThemes();
	}

	public function setThemes(){
	    //Cache::set( $_SERVER['SERVER_NAME'],null);

            if(Cache::get( $_SERVER['SERVER_NAME'])){
                $onerow        =Cache::get( $_SERVER['SERVER_NAME']);
                $themes['pc']     = $onerow['template'];
                $theme         = ($this->isMobile() && config('open_mobile_site') == '1') ? $themes['mobile_template'] : $themes['pc'];

                $this->assign([
                    'Webname'  => $onerow['webname'],
                    'Email' =>$onerow['email'],
                    'Phone' =>$onerow['phone'],
                    'Logo' =>$onerow['logo'],
                    'Address' =>$onerow['address']
                ]);

            }else{

                $onerow = db('web_config')->where('domain', $_SERVER['SERVER_NAME'])->find();
                if(isset($onerow['logo'])){
                    $picrow=db('picture')->where('id',$onerow['logo'])->find();
                    if('$picrow'){
                        $onerow['logo']=$picrow['path'];
                    }else{
                        $onerow['logo']="";
                    }
                }
                Cache::set( $_SERVER['SERVER_NAME'],$onerow);
                $onerow=Cache::get( $_SERVER['SERVER_NAME']);
                $themes['pc']     = config('pc_themes') ? config('pc_themes') : 'default';
                $themes['mobile'] = config('mobile_themes') ? config('mobile_themes') : 'mobile';
                $theme = ($this->isMobile() && config('open_mobile_site') == '1') ? $themes['mobile_template'] : $themes['pc'];
            }



		$module = $this->request->module();

		if ($module == 'index') {
			$view_path   = '/template/' . $theme . '/' ;
		}else{
			$view_path   = '/template/' . $theme . '/' . $module . '/';
		}
		$tpl_replace = array(
			'__TPL__' => $view_path,
			'__JS__' => $view_path . 'static/js',
            '__CSS__' => $view_path . 'static/css',
			'__IMG__' => $view_path . 'static/images',
		);
        $tpl_conf = array(
			'view_path'   => '.' . $view_path.'',
			'tpl_replace_string' => $tpl_replace
		);

        //菜单

     /*   $web = db('web_config')
            ->alias('a')
            ->join('nav_web b','a.id = b.web_id')
            ->join('nav c','c.id = b.nav_id')
            ->where('a.domain', $_SERVER['SERVER_NAME'])
            ->select();*/

        $channel=db('channel')->order('sort desc')->order('id asc')->select();
        $this->assign('topmenu',$channel);
		$this->view->config($tpl_conf);
	}
}