<?php
namespace app\common\behavior;

class InitHook {

	public function run(&$request) {
		//未安装时不执行
		if (substr(request()->pathinfo(), 0, 7) != 'install' && is_file(APP_PATH . 'database.php')) {
			//初始化某些配置信息
			if (cache('db_config_data')) {
				\think\Config::set(cache('db_config_data'));
			} else {
				$config = model('common/Config');
				\think\Config::set($config->lists());
			}

			//扩展插件
			\think\Loader::addNamespace('addons', ROOT_PATH . '/addons/');

			$this->setHook();

			//设置模型内容路由
			$this->setRoute();
		}
	}

	protected function setHook() {
		$data = cache('hooks');
		if (!$data) {
			$hooks = db('Hooks')->column('name,addons');
			foreach ($hooks as $key => $value) {
				if ($value) {
					$map['status'] = 1;
					$names         = explode(',', $value);
					$map['name']   = array('IN', $names);
					$data          = db('Addons')->where($map)->column('id,name');
					if ($data) {
						$addons = array_intersect($names, $data);
						\think\Hook::add($key, array_map('get_addon_class', $addons));
					}
				}
			}
			cache('hooks', \think\Hook::get());
		} else {
			\think\Hook::import($data, false);
		}
	}

	protected function setRoute() {
		$list = db('Rewrite')->select();
		foreach ($list as $key => $value) {
			$route[$value['rule']] = $value['url'];
		}

		//模型类路由配置
		$list = db('Model')->column('id,name', 'id');
		foreach ($list as $key => $value) {
			$route["admin/" . $value . "/index"]  = "admin/content/index?model_id=" . $key;
			$route["admin/" . $value . "/add"]    = "admin/content/add?model_id=" . $key;
			$route["admin/" . $value . "/edit"]   = "admin/content/edit?model_id=" . $key;
			$route["admin/" . $value . "/del"]    = "admin/content/del?model_id=" . $key;
			$route["admin/" . $value . "/status"] = "admin/content/status?model_id=" . $key;
			$route[$value . "/index"]             = "index/content/index?model=" . $value;
			$route[$value . "/list/:id"]          = "index/content/lists?model=" . $value;
			$route[$value . "/detail-<id>"]        = "index/content/detail?model=" . $value;
			$route["user/" . $value . "/index"]   = "user/content/index?model_id=" . $key;
			$route["user/" . $value . "/add"]     = "user/content/add?model_id=" . $key;
			$route["user/" . $value . "/edit"]    = "user/content/edit?model_id=" . $key;
			$route["user/" . $value . "/del"]     = "user/content/del?model_id=" . $key;
			$route["user/" . $value . "/status"]  = "user/content/status?model_id=" . $key;
		}
		$route["list/:id"] = "index/content/category";

		//自定义表单路由配置
		$form = db('Form')->column('id,name', 'id');
		foreach ($form as $key => $value) {
			$route["form/".$value."/index"] = "index/form/index?model=" . $value;
			$route["form/".$value."/list"] = "index/form/lists?model=" . $value;
			$route["form/".$value."/add"] = "index/form/add?model=" . $value;
		}
		\think\Route::rule($route);
	}
}