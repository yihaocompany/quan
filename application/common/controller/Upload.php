<?php
// +----------------------------------------------------------------------
// | YihaoCMS [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.tensent.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: molong <molong@tensent.cn> <http://www.tensent.cn>
// +----------------------------------------------------------------------

namespace app\common\controller;

class Upload {

	/**
	 * 上传控制器
	 */
	public function upload() {
		$upload_type = input('get.filename', 'images', 'trim');
		$config      = $this->$upload_type();
		// 获取表单上传文件 例如上传了001.jpg
		$file = request()->file('file');
		$size = $config['size'] * 1024 * 1024;
		$info = $file->validate(array(
			'size'     => $size,
			'ext'      => $config['ext'],
		))->move($config['rootPath'], true, false);

		if ($info) {
			$return['status'] = 1;
			$return['info']   = $this->save($config, $upload_type, $info);
		} else {
			$return['status'] = 0;
			$return['info']   = $file->getError();
		}

		echo json_encode($return);
	}

	/**
	 * 图片上传
	 * @var view
	 * @access public
	 */
	protected function images() {
		return config('picture_upload');
	}

	/**
	 * 文件上传
	 * @var view
	 * @access public
	 */
	protected function attachment() {
		return config('attachment_upload');
	}

	/**
	 * 百度编辑器使用
	 * @var view
	 * @access public
	 */
	public function ueditor() {
		$data = new \com\Ueditor(session('auth_user.uid'));
		echo $data->output();
	}

	public function editor() {
		$callback = request()->get('callback');
		$CKEditorFuncNum = request()->get('CKEditorFuncNum');
		$file = request()->file('upload');
		$info = $file->move(config('editor_upload.rootPath'), true, false);
		if ($info) {
			$fileInfo              = $this->parseFile($info);
			$data = array(
				"originalName" => $fileInfo['name'],
				"name" => $fileInfo['name'],
				"url" => $fileInfo['url'],
				"size" => $fileInfo['size'],
				"type" => $fileInfo['ext'],
				"state" => 'SUCCESS'
			);
		} else {
			$data['state'] = $file->getError();
		}
		/**
		* 返回数据
		*/
		if($callback) {
			return '<script>'.$callback.'('.json_encode($data).')</script>';
		}elseif($CKEditorFuncNum) {
			return '<script>window.parent.CKEDITOR.tools.callFunction("'.$CKEditorFuncNum.'","'.$fileInfo['url'].'","'.$data['state'].'");</script>';
		} else {
			return json_encode($data);
		}
	}

	public function delete() {
		$data = array(
			'status' => 1,
		);
		echo json_encode($data);exit();
	}

	/**
	 * 保存上传的信息到数据库
	 * @var view
	 * @access public
	 */
	public function save($config, $type, $file) {
		$file           = $this->parseFile($file);
		$file['status'] = 1;
		$dbname         = ($type == 'images') ? 'picture' : 'file';
		$id             = db($dbname)->insertGetId($file);

		if ($id) {
			$data = db($dbname)->where(array('id' => $id))->find();
			return $data;
		} else {
			return false;
		}
	}

	/**
	 * 下载本地文件
	 * @param  array    $file     文件信息数组
	 * @param  callable $callback 下载回调函数，一般用于增加下载次数
	 * @param  string   $args     回调函数参数
	 * @return boolean            下载失败返回false
	 */
	public function downLocalFile($file, $callback = null, $args = null) {
		if (is_file($file['rootpath'] . $file['savepath'] . $file['savename'])) {
			/* 调用回调函数新增下载数 */
			is_callable($callback) && call_user_func($callback, $args);

			/* 执行下载 *///TODO: 大文件断点续传
			header("Content-Description: File Transfer");
			header('Content-type: ' . $file['type']);
			header('Content-Length:' . $file['size']);
			if (preg_match('/MSIE/', $_SERVER['HTTP_USER_AGENT'])) {
				//for IE
				header('Content-Disposition: attachment; filename="' . rawurlencode($file['name']) . '"');
			} else {
				header('Content-Disposition: attachment; filename="' . $file['name'] . '"');
			}
			readfile($file['rootpath'] . $file['savepath'] . $file['savename']);
			exit;
		} else {
			$this->error = '文件已被删除！';
			return false;
		}
	}

	protected function parseFile($info) {
		$data['create_time'] = $info->getATime(); //最后访问时间
		$data['savename']    = $info->getBasename(); //获取无路径的basename
		$data['c_time']      = $info->getCTime(); //获取inode修改时间
		$data['ext']         = $info->getExtension(); //文件扩展名
		$data['name']        = $info->getFilename(); //获取文件名
		$data['m_time']      = $info->getMTime(); //获取最后修改时间
		$data['owner']       = $info->getOwner(); //文件拥有者
		$data['savepath']    = $info->getPath(); //不带文件名的文件路径
		$data['url']         = $data['path']         = '/uploads/' . $info->getSaveName(); //全路径
		$data['size']        = $info->getSize(); //文件大小，单位字节
		$data['is_file']     = $info->isFile(); //是否是文件
		$data['is_execut']   = $info->isExecutable(); //是否可执行
		$data['is_readable'] = $info->isReadable(); //是否可读
		$data['is_writable'] = $info->isWritable(); //是否可写
		$data['md5']         = md5_file($info->getPathname());
		$data['sha1']        = sha1_file($info->getPathname());
		return $data;
	}
}