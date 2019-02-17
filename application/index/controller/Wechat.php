<?php
namespace app\index\controller;

use EasyWeChat\Factory;

/**
 * 微信
 */
class Wechat {

	public function __construct() {
		$this->options = [
			'app_id'    => 'wx3cf0f39249eb0exxx',
			'secret'    => 'f1c242f4f28f735d4687abb469072xxx',
			'token'     => 'easywechat',
			'log' => [
				'level' => 'debug',
				'file'  => RUNTIME_PATH . '/easywechat.log',
			],
		];
	}

	public function index(){
		$app = Factory::officialAccount($this->options);

		$server = $app->server;
		$user = $app->user;

		$server->push(function($message) use ($user) {
			// $message['MsgType']       消息类型
			// $message['ToUserName']    接收方帐号（该公众号 ID）
			// $message['FromUserName']  发送方帐号（OpenID, 代表用户的唯一标识）
			// $message['CreateTime']    消息创建时间（时间戳）
			// $message['MsgId']         消息 ID（64位整型）
			$fromUser = $user->get($message['FromUserName']);
			switch ($message['MsgType']) {
				case 'text':
					return $this->_keys($message);
				case 'event':
					return $this->_event($message);
				case 'image':
					return $this->_image($message);
				case 'location':
					return $this->_location($message);
				case 'voice':
					return '收到语音消息';
					break;
				case 'video':
					return '收到视频消息';
					break;
				case 'link':
					return '收到链接消息';
					break;
				default:
					return "{$fromUser->nickname} 您好！欢迎关注!";
			}
		});

		$server->serve()->send();
	}

	public function _keys(){
		
	}

	public function _event(){
		
	}

	public function _image(){
		
	}

	public function _location(){
		
	}
}