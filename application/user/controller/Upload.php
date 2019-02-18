<?php
// +----------------------------------------------------------------------
// | YihaoCMS [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://diao.info All rights reserved.
// +----------------------------------------------------------------------
// | Author: molong <molong@tensent.cn> <http://diao.info>
// +----------------------------------------------------------------------

namespace app\user\controller;
use app\common\controller\User;

class Upload extends User {

	protected $controller;

	public function _initialize(){
		parent::_initialize();
		$this->controller = controller('common/Upload');
	}

	public function _empty(){
		$action = $this->request->action();
		return $this->controller->$action();
	}

	public function download(){
		$order_id = input('order_id', '', 'trim');
		$product_id = input('product_id', '', 'trim');

		//判断是否已经支付
		$pay_status = db('Order')->where(array('id'=>$order_id))->value('pay_status');
		if (!$pay_status) {
			return $this->error("您还未购买！");
		}
		//获取产品文件
		$book = db('Book')->where(array('id'=>$product_id))->find();

		if (!$book['file']) {
			return $this->error("无此图书文件，请联系网站管理员！");
		}

		$book_file = db('file')->where(array('id'=>$book['file']))->find();
		$attachment = config('attachment_upload');
		$file = array(
			'rootpath'  => $attachment['rootPath'],
			'savepath'  => $book_file['savepath'],
			'savename'  => $book_file['savename'],
			'type'      => $book_file['ext'],
			'size'      => $book_file['size'],
			'name'      => $book['book_name'].'.'.$book_file['ext']
		);
		$result = $this->controller->downLocalFile($file);
		if ($result === false) {
			return $this->error("下载失败！");
		}
	}

	public function avatar(){
		$file = $this->request->file('UpFile');
		$info = $file->rule('uniqid')->move('./uploads/avatar/'.setavatardir(session('user_auth.uid')), true, true);

		$image = new \org\Image();
		$image->init()->open($info->getPathname())->thumb(120,120)->save('./uploads/avatar/'.setavatardir(session('user_auth.uid')).'/avatar_big.png');
		$image->init()->open($info->getPathname())->thumb(100,100)->save('./uploads/avatar/'.setavatardir(session('user_auth.uid')).'/avatar_middle.png');
		$image->init()->open($info->getPathname())->thumb(60,60)->save('./uploads/avatar/'.setavatardir(session('user_auth.uid')).'/avatar_small.png');
		unlink($info->getPathname());
		$data = array(
			array('ImgUrl' => '/uploads/avatar/'.setavatardir(session('user_auth.uid')).'/avatar_big.png'),
			array('ImgUrl' => '/uploads/avatar/'.setavatardir(session('user_auth.uid')).'/avatar_middle.png'),
			array('ImgUrl' => '/uploads/avatar/'.setavatardir(session('user_auth.uid')).'/avatar_small.png'),
		);
		return json_encode($data);
	}
}