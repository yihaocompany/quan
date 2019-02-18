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

class Search extends Front{

	//文档搜索结果显示
	public function index(\think\Request $request){
		$model = $request->param('model', 'article');

		$model_id = db('Model')->where('name', $model)->value('id');

		if (!$model_id) {
			return $this->error('无此模型！');
		}

		$map = $this->mapBuild();
		$list = M($model)->where($map)->order('create_time desc')->paginate(10);
		$data = array(
			'list'   => $list,
			'page'   => $list->render()
		);
		$this->assign($data);
		return $this->fetch();
	}

	//查询条件组装
	protected function mapBuild(){
		$keyword = input('keyword', '', 'trim');

		$map['status'] = array('GT',0);

		if ($keyword) {
			$map['title'] = array("LIKE","%$keyword%");
		}
		$search = array(
			'keyword'=>$keyword
		);
		$this->assign('search',$search);
		return $map;
	}
}
