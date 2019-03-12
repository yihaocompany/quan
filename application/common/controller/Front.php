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

        $where=['domain'=> $_SERVER['SERVER_NAME'],'web.status'=>1,'c.status'=>1,'nav.status'=>1];
        $menurows=null;
        if(Cache::get( 'menu'.$_SERVER['SERVER_NAME'])){
            $menurows=Cache::get( 'menu'.$_SERVER['SERVER_NAME']);
        }else{
            $menulist=db('channel')->alias('c')->join('nav_web nav','nav.nav_id=c.id','inner')->
            join('web_config web','web.id=nav.web_id','inner')
                ->field('c.* ,nav.menu as menu,nav.title as navtitle,nav.id as navid, web.id as webid')->where($where)->select();
            Cache::set( 'menu'.$_SERVER['SERVER_NAME'],$menulist);
            $menurows=$menulist;
        }

        //$channel=db('channel')->order('sort desc')->order('id asc')->select();

        $this->assign('topmenu',$menurows);

        //友情链接

        $linkwhere=['web.domain'=> $_SERVER['SERVER_NAME'],'web.status'=>1,'link.status'=>1];
        if(Cache::get( 'link'.$_SERVER['SERVER_NAME'])){
            $linkrows=Cache::get( 'link'.$_SERVER['SERVER_NAME']);
        }else{
            $linklist=db('link')->alias('link')->join('web_link weblink','link.web_id=weblink.id','inner')->join('web_config web','web.id=link.web_id')

                ->field('link.* ')->where($linkwhere)->select();
            Cache::set( 'link'.$_SERVER['SERVER_NAME'],$linklist);
            $linkrows=$linklist;
        }


        $this->assign('links',$linkrows);

		$this->view->config($tpl_conf);
	}
}