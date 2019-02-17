<?php
namespace app\api\controller;

class Content extends \app\common\controller\Api{

	public function category(){
		$model_id = $this->request->param('model_id', 0);

		$map = array();
		if ($model_id) {
			$map['model_id'] = $model_id;
		}

		$list = db('Category')->where($map)->order('sort asc')->select();

		$this->data['data'] = $list;
		return $this->data;
	}

	public function lists(){
		$pagesize = $this->request->param('pagesize', 25);
		$category_id = $this->request->param('category_id', 0);
		$model = $this->request->param('model', 'Article');

		$map = array();
		if ($category_id) {
			$map['category_id'] = $category_id;
		}

		$res = db($model)->where($map)->order('id desc')->paginate($pagesize);
		$data = $res->toArray();

		$list = array();
		foreach($data['data'] as $key => $value){
			$value['create_time'] = date('Y-m-d H:i:s', $value['create_time']);
			$value['cover'] = $this->serverurl . get_cover($value['cover_id'], 'path');
			$list[] = $value;
		}

		$data['data'] = $list;
		$this->data['data'] = $data;
		return $this->data;
	}

	public function detail($id = 0, $model = 'Article'){
		$info = db($model)->where('id', $id)->find();
		$info['create_time'] = date('Y-m-d H:i:s', $info['create_time']);
		$info['content'] = str_replace('/uploads/', $this->serverurl . '/uploads/', $info['content']);
		$info['cover'] = $this->serverurl . get_cover($info['cover_id'], 'path');
		$info['download'] = get_file($info['download_file']);
		if (isset($info['download']['url'])) {
			$info['download']['url'] = $this->serverurl . $info['download']['url'];
		}

		$this->data['info'] = $info;
		return $this->data;
	}
}
