<?php
namespace app\api\controller;

class Upload extends \app\common\controller\Api {

	public $mustToken = true;

	public function images(){
		$config = config('picture_upload');
		// 获取表单上传文件 例如上传了001.jpg
		$file = $this->request->file('file');
		$size = $config['size'] * 1024 * 1024;
		$info = $file->validate(array(
			'size'     => $size,
			'ext'      => $config['ext'],
		))->move($config['rootPath'], true, false);

		if ($info) {
			$data = $this->save($config, 'images', $info);
			$data['url'] = $this->serverurl . $data['url'];
			$this->data['data'] = $data;
			return $this->data;
		}else{
			$this->data['code'] = 1;
			$this->data['msg'] = $file->getError();
			return $this->data;
		}
	}

	public function video(){
		$config = config('video_upload');
		// 获取表单上传文件 例如上传了001.jpg
		$file = $this->request->file('file');
		$size = $config['size'] * 1024 * 1024;
		$info = $file->validate(array(
			'size'     => $size,
			'ext'      => $config['ext'],
		))->move($config['rootPath'], true, false);

		if ($info) {
			$data = $this->save($config, 'images', $info);
			$data['url'] = $this->serverurl . $data['url'];
			$this->data['data'] = $data;
			return $this->data;
		}else{
			$this->data['code'] = 1;
			$this->data['msg'] = $file->getError();
			return $this->data;
		}
	}

	/**
	 * 下载文件
	 * @var view
	 * @access public
	 */
	public function download($id, $type = 'File'){
		$file = db($type)->where('id', $id)->find();
		$file_config = config('attachment_upload');
		if (is_file(ROOT_PATH . 'web' . $file['url'])) {
			/* 调用回调函数新增下载数 */
			//is_callable($callback) && call_user_func($callback, $args);

			/* 执行下载 *///TODO: 大文件断点续传
			header("Content-Description: File Transfer");
			header('Content-type: ' . $file['ext']);
			header('Content-Length:' . $file['size']);
			if (preg_match('/MSIE/', $_SERVER['HTTP_USER_AGENT'])) {
				//for IE
				header('Content-Disposition: attachment; filename="' . rawurlencode($file['name']) . '"');
			} else {
				header('Content-Disposition: attachment; filename="' . $file['name'] . '"');
			}
			readfile(ROOT_PATH . 'web' . $file['url']);
			exit;
		} else {
			$this->error = '文件已被删除！';
			return false;
		}
	}

	/**
	 * 保存上传的信息到数据库
	 * @var view
	 * @access public
	 */
	protected function save($config, $type, $file) {
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