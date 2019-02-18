<?php
// +----------------------------------------------------------------------
// | YihaoCMS [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://diao.info All rights reserved.
// +----------------------------------------------------------------------
// | Author: molong <molong@tensent.cn> <http://diao.info>
// +----------------------------------------------------------------------

// YihaoCMS常量定义
define('YIHAO_VERSION', '0.1.2019-1-25');
define('YIHAO_TEC', '易好科技');
define('YIHAO_ADDON_PATH', __DIR__ . '/../addons' . DS);

//字符串解密加密
function authcode($string, $operation = 'DECODE', $key = '', $expiry = 0) {
	$ckey_length = 4; // 随机密钥长度 取值 0-32;
	// 加入随机密钥，可以令密文无任何规律，即便是原文和密钥完全相同，加密结果也会每次不同，增大破解难度。
	// 取值越大，密文变动规律越大，密文变化 = 16 的 $ckey_length 次方
	// 当此值为 0 时，则不产生随机密钥
	$uc_key = config('data_auth_key') ? config('data_auth_key') : 'YihaoCMS';
	$key    = md5($key ? $key : $uc_key);
	$keya   = md5(substr($key, 0, 16));
	$keyb   = md5(substr($key, 16, 16));
	$keyc   = $ckey_length ? ($operation == 'DECODE' ? substr($string, 0, $ckey_length) : substr(md5(microtime()), -$ckey_length)) : '';

	$cryptkey   = $keya . md5($keya . $keyc);
	$key_length = strlen($cryptkey);

	$string = $operation == 'DECODE' ? base64_decode(substr($string, $ckey_length)) : sprintf('%010d', $expiry ? $expiry + time() : 0) . substr(md5($string . $keyb), 0, 16) . $string;

	$string_length = strlen($string);
	$result        = '';
	$box           = range(0, 255);
	$rndkey        = array();
	for ($i = 0; $i <= 255; $i++) {
		$rndkey[$i] = ord($cryptkey[$i % $key_length]);
	}

	for ($j = $i = 0; $i < 256; $i++) {
		$j       = ($j + $box[$i] + $rndkey[$i]) % 256;
		$tmp     = $box[$i];
		$box[$i] = $box[$j];
		$box[$j] = $tmp;
	}

	for ($a = $j = $i = 0; $i < $string_length; $i++) {
		$a       = ($a + 1) % 256;
		$j       = ($j + $box[$a]) % 256;
		$tmp     = $box[$a];
		$box[$a] = $box[$j];
		$box[$j] = $tmp;
		$result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
	}

	if ($operation == 'DECODE') {
		if ((substr($result, 0, 10) == 0 || substr($result, 0, 10) - time() > 0) && substr($result, 10, 16) == substr(md5(substr($result, 26) . $keyb), 0, 16)) {
			return substr($result, 26);
		} else {
			return '';
		}
	} else {
		return $keyc . str_replace('=', '', base64_encode($result));
	}
}

/**
+----------------------------------------------------------
 * 产生随机字串，可用来自动生成密码 默认长度6位 字母和数字混合
+----------------------------------------------------------
 * @param string $len 长度
 * @param string $type 字串类型
 * 0 字母 1 数字 其它 混合
 * @param string $addChars 额外字符
+----------------------------------------------------------
 * @return string
+----------------------------------------------------------
 */
function rand_string($len = 6, $type = '', $addChars = '') {
	$str = '';
	switch ($type) {
	case 0:
		$chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz' . $addChars;
		break;
	case 1:
		$chars = str_repeat('0123456789', 3);
		break;
	case 2:
		$chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ' . $addChars;
		break;
	case 3:
		$chars = 'abcdefghijklmnopqrstuvwxyz' . $addChars;
		break;
	case 4:
		$chars = "们以我到他会作时要动国产的一是工就年阶义发成部民可出能方进在了不和有大这主中人上为来分生对于学下级地个用同行面说种过命度革而多子后自社加小机也经力线本电高量长党得实家定深法表着水理化争现所二起政三好十战无农使性前等反体合斗路图把结第里正新开论之物从当两些还天资事队批点育重其思与间内去因件日利相由压员气业代全组数果期导平各基或月毛然如应形想制心样干都向变关问比展那它最及外没看治提五解系林者米群头意只明四道马认次文通但条较克又公孔领军流入接席位情运器并飞原油放立题质指建区验活众很教决特此常石强极土少已根共直团统式转别造切九你取西持总料连任志观调七么山程百报更见必真保热委手改管处己将修支识病象几先老光专什六型具示复安带每东增则完风回南广劳轮科北打积车计给节做务被整联步类集号列温装即毫知轴研单色坚据速防史拉世设达尔场织历花受求传口断况采精金界品判参层止边清至万确究书术状厂须离再目海交权且儿青才证低越际八试规斯近注办布门铁需走议县兵固除般引齿千胜细影济白格效置推空配刀叶率述今选养德话查差半敌始片施响收华觉备名红续均药标记难存测士身紧液派准斤角降维板许破述技消底床田势端感往神便贺村构照容非搞亚磨族火段算适讲按值美态黄易彪服早班麦削信排台声该击素张密害侯草何树肥继右属市严径螺检左页抗苏显苦英快称坏移约巴材省黑武培著河帝仅针怎植京助升王眼她抓含苗副杂普谈围食射源例致酸旧却充足短划剂宣环落首尺波承粉践府鱼随考刻靠够满夫失包住促枝局菌杆周护岩师举曲春元超负砂封换太模贫减阳扬江析亩木言球朝医校古呢稻宋听唯输滑站另卫字鼓刚写刘微略范供阿块某功套友限项余倒卷创律雨让骨远帮初皮播优占死毒圈伟季训控激找叫云互跟裂粮粒母练塞钢顶策双留误础吸阻故寸盾晚丝女散焊功株亲院冷彻弹错散商视艺灭版烈零室轻血倍缺厘泵察绝富城冲喷壤简否柱李望盘磁雄似困巩益洲脱投送奴侧润盖挥距触星松送获兴独官混纪依未突架宽冬章湿偏纹吃执阀矿寨责熟稳夺硬价努翻奇甲预职评读背协损棉侵灰虽矛厚罗泥辟告卵箱掌氧恩爱停曾溶营终纲孟钱待尽俄缩沙退陈讨奋械载胞幼哪剥迫旋征槽倒握担仍呀鲜吧卡粗介钻逐弱脚怕盐末阴丰雾冠丙街莱贝辐肠付吉渗瑞惊顿挤秒悬姆烂森糖圣凹陶词迟蚕亿矩康遵牧遭幅园腔订香肉弟屋敏恢忘编印蜂急拿扩伤飞露核缘游振操央伍域甚迅辉异序免纸夜乡久隶缸夹念兰映沟乙吗儒杀汽磷艰晶插埃燃欢铁补咱芽永瓦倾阵碳演威附牙芽永瓦斜灌欧献顺猪洋腐请透司危括脉宜笑若尾束壮暴企菜穗楚汉愈绿拖牛份染既秋遍锻玉夏疗尖殖井费州访吹荣铜沿替滚客召旱悟刺脑措贯藏敢令隙炉壳硫煤迎铸粘探临薄旬善福纵择礼愿伏残雷延烟句纯渐耕跑泽慢栽鲁赤繁境潮横掉锥希池败船假亮谓托伙哲怀割摆贡呈劲财仪沉炼麻罪祖息车穿货销齐鼠抽画饲龙库守筑房歌寒喜哥洗蚀废纳腹乎录镜妇恶脂庄擦险赞钟摇典柄辩竹谷卖乱虚桥奥伯赶垂途额壁网截野遗静谋弄挂课镇妄盛耐援扎虑键归符庆聚绕摩忙舞遇索顾胶羊湖钉仁音迹碎伸灯避泛亡答勇频皇柳哈揭甘诺概宪浓岛袭谁洪谢炮浇斑讯懂灵蛋闭孩释乳巨徒私银伊景坦累匀霉杜乐勒隔弯绩招绍胡呼痛峰零柴簧午跳居尚丁秦稍追梁折耗碱殊岗挖氏刃剧堆赫荷胸衡勤膜篇登驻案刊秧缓凸役剪川雪链渔啦脸户洛孢勃盟买杨宗焦赛旗滤硅炭股坐蒸凝竟陷枪黎救冒暗洞犯筒您宋弧爆谬涂味津臂障褐陆啊健尊豆拔莫抵桑坡缝警挑污冰柬嘴啥饭塑寄赵喊垫丹渡耳刨虎笔稀昆浪萨茶滴浅拥穴覆伦娘吨浸袖珠雌妈紫戏塔锤震岁貌洁剖牢锋疑霸闪埔猛诉刷狠忽灾闹乔唐漏闻沈熔氯荒茎男凡抢像浆旁玻亦忠唱蒙予纷捕锁尤乘乌智淡允叛畜俘摸锈扫毕璃宝芯爷鉴秘净蒋钙肩腾枯抛轨堂拌爸循诱祝励肯酒绳穷塘燥泡袋朗喂铝软渠颗惯贸粪综墙趋彼届墨碍启逆卸航衣孙龄岭骗休借" . $addChars;
		break;
	default:
		// 默认去掉了容易混淆的字符oOLl和数字01，要添加请使用addChars参数
		$chars = 'ABCDEFGHIJKMNPQRSTUVWXYZabcdefghijkmnpqrstuvwxyz23456789' . $addChars;
		break;
	}
	if ($len > 10) {
		//位数过长重复字符串一定次数
		$chars = $type == 1 ? str_repeat($chars, $len) : str_repeat($chars, 5);
	}
	if ($type != 4) {
		$chars = str_shuffle($chars);
		$str   = substr($chars, 0, $len);
	} else {
		// 中文随机字
		for ($i = 0; $i < $len; $i++) {
			$str .= msubstr($chars, floor(mt_rand(0, mb_strlen($chars, 'utf-8') - 1)), 1);
		}
	}
	return $str;
}

/**
 * 字符串截取，支持中文和其他编码
 * @static
 * @access public
 * @param string $str 需要转换的字符串
 * @param string $start 开始位置
 * @param string $length 截取长度
 * @param string $charset 编码格式
 * @param string $suffix 截断显示字符
 * @return string
 */
function msubstr($str, $start = 0, $length, $charset = "utf-8", $suffix = true) {
	if (function_exists("mb_substr")) {
		$slice = mb_substr($str, $start, $length, $charset);
	} elseif (function_exists('iconv_substr')) {
		$slice = iconv_substr($str, $start, $length, $charset);
		if (false === $slice) {
			$slice = '';
		}
	} else {
		$re['utf-8']  = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/";
		$re['gb2312'] = "/[\x01-\x7f]|[\xb0-\xf7][\xa0-\xfe]/";
		$re['gbk']    = "/[\x01-\x7f]|[\x81-\xfe][\x40-\xfe]/";
		$re['big5']   = "/[\x01-\x7f]|[\x81-\xfe]([\x40-\x7e]|\xa1-\xfe])/";
		preg_match_all($re[$charset], $str, $match);
		$slice = join("", array_slice($match[0], $start, $length));
	}
	if (strlen($slice) == strlen($str)) {
		return $slice;
	} else {
		return $suffix ? $slice . '...' : $slice;
	}
}

/**
 * 处理插件钩子
 * @param string $hook   钩子名称
 * @param mixed $params 传入参数
 * @return void
 */
function hook($hook, $params = array()) {
	\think\Hook::listen($hook, $params);
}

/**
 * 获取广告位广告
 * @param string $name   广告位名称
 * @param mixed $params 传入参数
 * @return html
 */
function ad($name, $param = array()) {
	return widget('common/Ad/run', array('name' => $name));
}

/**
 * 获取插件类的类名
 * @param strng $name 插件名
 */
function get_addon_class($name) {
	$class = "\\addons\\" . strtolower($name) . "\\{$name}";
	return $class;
}

/**
 * 获取插件类的配置文件数组
 * @param string $name 插件名
 */
function get_addon_config($name) {
	$class = get_addon_class($name);
	if (class_exists($class)) {
		$addon = new $class();
		return $addon->getConfig();
	} else {
		return array();
	}
}

/**
 * 插件显示内容里生成访问插件的url
 * @param string $url url
 * @param array $param 参数
 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
 */
function addons_url($url, $param = array()) {
	$url        = parse_url($url);
	$case       = config('URL_CASE_INSENSITIVE');
	$addons     = $case ? parse_name($url['scheme']) : $url['scheme'];
	$controller = $case ? parse_name($url['host']) : $url['host'];
	$action     = trim($case ? strtolower($url['path']) : $url['path'], '/');

	/* 解析URL带的参数 */
	if (isset($url['query'])) {
		parse_str($url['query'], $query);
		$param = array_merge($query, $param);
	}

	/* 基础参数 */
	$params = array(
		'mc' => $addons,
		'op' => $controller,
		'ac' => $action,
	);
	$params = array_merge($params, $param); //添加额外参数

	return \think\Url::build('index/addons/execute', $params);
}

/**
 * 获取导航URL
 * @param  string $url 导航URL
 * @return string      解析或的url
 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
 */
function get_nav_url($url) {
	switch ($url) {
	case 'http://' === substr($url, 0, 7):
	case 'https://' === substr($url, 0, 8):
	case '#' === substr($url, 0, 1):
		break;
	default:
		$url = \think\Url::build($url);
		break;
	}
	return $url;
}

/**
 * 获取文档封面图片
 * @param int $cover_id
 * @param string $field
 * @return 完整的数据  或者  指定的$field字段值
 * @author huajie <banhuajie@163.com>
 */
function get_cover($cover_id, $field = null) {
	if (empty($cover_id)) {
		return BASE_PATH . '/static/images/default.png';
	}
	$picture = db('Picture')->where(array('status' => 1, 'id' => $cover_id))->find();
	if ($field == 'path') {
		if (!empty($picture['url'])) {
			$picture['path'] = $picture['url'] ? BASE_PATH . $picture['url'] : BASE_PATH . '/static/images/default.png';
		} else {
			$picture['path'] = $picture['path'] ? BASE_PATH . $picture['path'] : BASE_PATH . '/static/images/default.png';
		}
	}
	return empty($field) ? $picture : $picture[$field];
}

/**
 * 获取文件
 * @param int $file_id
 * @param string $field
 * @return 完整的数据  或者  指定的$field字段值
 * @author huajie <banhuajie@163.com>
 */
function get_file($file_id, $field = null) {
	if (empty($file_id)) {
		return '';
	}
	$file = db('File')->where(array('id' => $file_id))->find();
	if ($field == 'path') {
		return $file['savepath'];
	} elseif ($field == 'time') {
		return date('Y-m-d H:i:s', $file['create_time']);
	}
	return empty($field) ? $file : $file[$field];
}

/**
 * 获取多图地址
 * @param array $covers
 * @return 返回图片列表
 * @author molong <molong@tensent.cn>
 */
function get_cover_list($covers) {
	if ($covers == '') {
		return false;
	}
	$cover_list = explode(',', $covers);
	foreach ($cover_list as $item) {
		$list[] = get_cover($item, 'path');
	}
	return $list;
}

/**
 * 字符串命名风格转换
 * type 0 将Java风格转换为C的风格 1 将C风格转换为Java的风格
 * @param string $name 字符串
 * @param integer $type 转换类型
 * @return string
 */
function parse_name($name, $type = 0) {
	if ($type) {
		return ucfirst(preg_replace_callback('/_([a-zA-Z])/', function ($match) {return strtoupper($match[1]);}, $name));
	} else {
		return strtolower(trim(preg_replace("/[A-Z]/", "_\\0", $name), "_"));
	}
}

// 不区分大小写的in_array实现
function in_array_case($value, $array) {
	return in_array(strtolower($value), array_map('strtolower', $array));
}

/**
 * 数据签名认证
 * @param  array  $data 被认证的数据
 * @return string       签名
 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
 */
function data_auth_sign($data) {
	//数据类型检测
	if (!is_array($data)) {
		$data = (array) $data;
	}
	ksort($data); //排序
	$code = http_build_query($data); //url编码并生成query字符串
	$sign = sha1($code); //生成签名
	return $sign;
}

/**
 * 检测用户是否登录
 * @return integer 0-未登录，大于0-当前登录用户ID
 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
 */
function is_login() {
	$user = session('user_auth');
	if (empty($user)) {
		return 0;
	} else {
		return session('user_auth_sign') == data_auth_sign($user) ? $user['uid'] : 0;
	}
}

/**
 * 检测当前用户是否为管理员
 * @return boolean true-管理员，false-非管理员
 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
 */
function is_administrator($uid = null) {
	$uid = is_null($uid) ? is_login() : $uid;
	return $uid && (intval($uid) === config('user_administrator'));
}

/**
 * 获取客户端IP地址
 * @param integer $type 返回类型 0 返回IP地址 1 返回IPV4地址数字
 * @param boolean $adv 是否进行高级模式获取（有可能被伪装）
 * @return mixed
 */
function get_client_ip($type = 0, $adv = false) {
	$type      = $type ? 1 : 0;
	static $ip = NULL;
	if ($ip !== NULL) {
		return $ip[$type];
	}

	if ($adv) {
		if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
			$arr = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
			$pos = array_search('unknown', $arr);
			if (false !== $pos) {
				unset($arr[$pos]);
			}

			$ip = trim($arr[0]);
		} elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
			$ip = $_SERVER['HTTP_CLIENT_IP'];
		} elseif (isset($_SERVER['REMOTE_ADDR'])) {
			$ip = $_SERVER['REMOTE_ADDR'];
		}
	} elseif (isset($_SERVER['REMOTE_ADDR'])) {
		$ip = $_SERVER['REMOTE_ADDR'];
	}
	// IP地址合法验证
	$long = sprintf("%u", ip2long($ip));
	$ip   = $long ? array($ip, $long) : array('0.0.0.0', 0);
	return $ip[$type];
}

/**
 * 时间戳格式化
 * @param int $time
 * @return string 完整的时间显示
 * @author huajie <banhuajie@163.com>
 */
function time_format($time = NULL, $format = 'Y-m-d H:i') {
	$time = $time === NULL ? time() : intval($time);
	return date($format, $time);
}

/**
 * 根据用户ID获取用户名
 * @param  integer $uid 用户ID
 * @return string       用户名
 */
function get_username($uid = 0) {
	static $list;
	if (!($uid && is_numeric($uid))) {
		//获取当前登录用户名
		return session('user_auth.username');
	}
	$name = db('member')->where(array('uid' => $uid))->value('username');
	return $name;
}

/**
 * 根据用户ID获取用户昵称
 * @param  integer $uid 用户ID
 * @return string       用户昵称
 */
function get_nickname($uid = 0) {
	static $list;
	if (!($uid && is_numeric($uid))) {
		//获取当前登录用户名
		return session('user_auth.username');
	}

	/* 获取缓存数据 */
	if (empty($list)) {
		$list = cache('sys_user_nickname_list');
	}

	/* 查找用户信息 */
	$key = "u{$uid}";
	if (isset($list[$key])) {
		//已缓存，直接使用
		$name = $list[$key];
	} else {
		//调用接口获取用户信息
		$info = db('Member')->field('nickname')->find($uid);
		if ($info !== false && $info['nickname']) {
			$nickname = $info['nickname'];
			$name     = $list[$key]     = $nickname;
			/* 缓存用户 */
			$count = count($list);
			$max   = config('USER_MAX_CACHE');
			while ($count-- > $max) {
				array_shift($list);
			}
			cache('sys_user_nickname_list', $list);
		} else {
			$name = '';
		}
	}
	return $name;
}

/**
 * 对查询结果集进行排序
 * @access public
 * @param array $list 查询结果
 * @param string $field 排序的字段名
 * @param array $sortby 排序类型
 * asc正向排序 desc逆向排序 nat自然排序
 * @return array
 */
function list_sort_by($list, $field, $sortby = 'asc') {
	if (is_array($list)) {
		$refer = $resultSet = array();
		foreach ($list as $i => $data) {
			$refer[$i] = &$data[$field];
		}

		switch ($sortby) {
		case 'asc': // 正向排序
			asort($refer);
			break;
		case 'desc': // 逆向排序
			arsort($refer);
			break;
		case 'nat': // 自然排序
			natcasesort($refer);
			break;
		}
		foreach ($refer as $key => $val) {
			$resultSet[] = &$list[$key];
		}

		return $resultSet;
	}
	return false;
}

/**
 * 把返回的数据集转换成Tree
 * @param array $list 要转换的数据集
 * @param string $pid parent标记字段
 * @param string $level level标记字段
 * @return array
 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
 */
function list_to_tree($list, $pk = 'id', $pid = 'pid', $child = '_child', $root = 0) {
	// 创建Tree
	$tree = array();
	if (is_array($list) && !is_object($list)) {
		// 创建基于主键的数组引用
		$refer = array();
		foreach ($list as $key => $data) {
			$refer[$data[$pk]] = &$list[$key];
		}
		foreach ($list as $key => $data) {
			// 判断是否存在parent
			$parentId = $data[$pid];
			if ($root == $parentId) {
				$tree[] = &$list[$key];
			} else {
				if (isset($refer[$parentId])) {
					$parent             = &$refer[$parentId];
					$parent['childs'][] = $data['id'];
					$parent[$child][]   = &$list[$key];
				}
			}
		}
	}
	return $tree;
}

/**
 * 获取父树列表
 * 
 */
function get_parent_tree($id = ''){
	if ($id) {
		return array();
	}
}


/**
 * 将list_to_tree的树还原成列表
 * @param  array $tree  原来的树
 * @param  string $child 孩子节点的键
 * @param  string $order 排序显示的键，一般是主键 升序排列
 * @param  array  $list  过渡用的中间数组，
 * @return array        返回排过序的列表数组
 * @author yangweijie <yangweijiester@gmail.com>
 */
function tree_to_list($tree, $child = '_child', $order = 'id', &$list = array()) {
	if (is_array($tree)) {
		foreach ($tree as $key => $value) {
			$reffer = $value;
			if (isset($reffer[$child])) {
				unset($reffer[$child]);
				tree_to_list($value[$child], $child, $order, $list);
			}
			$list[] = $reffer;
		}
		$list = list_sort_by($list, $order, $sortby = 'asc');
	}
	return $list;
}

// 分析枚举类型字段值 格式 a:名称1,b:名称2
// 暂时和 parse_config_attr功能相同
// 但请不要互相使用，后期会调整
function parse_field_attr($string) {
	if (0 === strpos($string, ':')) {
		// 采用函数定义
		return eval('return ' . substr($string, 1) . ';');
	} elseif (0 === strpos($string, '[')) {
		// 支持读取配置参数（必须是数组类型）
		return \think\Config::get(substr($string, 1, -1));
	}

	$array = preg_split('/[,;\r\n]+/', trim($string, ",;\r\n"));
	if (strpos($string, ':')) {
		$value = array();
		foreach ($array as $val) {
			list($k, $v) = explode(':', $val);
			$value[$k]   = $v;
		}
	} else {
		$value = $array;
	}
	return $value;
}

function parse_field_bind($table, $selected = '', $model = 0) {
	$list = array();
	if ($table) {
		$res    = db($table)->select();
		foreach ($res as $key => $value) {
			if (($model && isset($value['model_id']) && $value['model_id'] == $model) || (isset($value['model_id']) && $value['model_id'] == 0)) {
				$list[] = $value;
			} elseif(!$model) {
				$list[] = $value;
			}
		}
		if (!empty($list)) {
			$tree = new \com\Tree();
			$list = $tree->toFormatTree($list);
		}
	}
	return $list;
}

// 分析枚举类型配置值 格式 a:名称1,b:名称2
function parse_config_attr($string) {
	$array = preg_split('/[,;\r\n]+/', trim($string, ",;\r\n"));
	if (strpos($string, ':')) {
		$value = array();
		foreach ($array as $val) {
			list($k, $v) = explode(':', $val);
			$value[$k]   = $v;
		}
	} else {
		$value = $array;
	}
	return $value;
}

/**
 * 记录行为日志，并执行该行为的规则
 * @param string $action 行为标识
 * @param string $model 触发行为的模型名
 * @param int $record_id 触发行为的记录id
 * @param int $user_id 执行行为的用户id
 * @return boolean
 * @author huajie <banhuajie@163.com>
 */
function action_log($action = null, $model = null, $record_id = null, $user_id = null) {

	//参数检查
	if (empty($action) || empty($model) || empty($record_id)) {
		return '参数不能为空';
	}
	if (empty($user_id)) {
		$user_id = is_login();
	}

	//查询行为,判断是否执行
	$action_info = db('Action')->getByName($action);
	if ($action_info['status'] != 1) {
		return '该行为被禁用或删除';
	}

	//插入行为日志
	$data['action_id']   = $action_info['id'];
	$data['user_id']     = $user_id;
	$data['action_ip']   = ip2long(get_client_ip());
	$data['model']       = $model;
	$data['record_id']   = $record_id;
	$data['create_time'] = time();

	//解析日志规则,生成日志备注
	if (!empty($action_info['log'])) {
		if (preg_match_all('/\[(\S+?)\]/', $action_info['log'], $match)) {
			$log['user']   = $user_id;
			$log['record'] = $record_id;
			$log['model']  = $model;
			$log['time']   = time();
			$log['data']   = array('user' => $user_id, 'model' => $model, 'record' => $record_id, 'time' => time());
			foreach ($match[1] as $value) {
				$param = explode('|', $value);
				if (isset($param[1])) {
					$replace[] = call_user_func($param[1], $log[$param[0]]);
				} else {
					$replace[] = $log[$param[0]];
				}
			}
			$data['remark'] = str_replace($match[0], $replace, $action_info['log']);
		} else {
			$data['remark'] = $action_info['log'];
		}
	} else {
		//未定义日志规则，记录操作url
		$data['remark'] = '操作url：' . $_SERVER['REQUEST_URI'];
	}

	db('ActionLog')->insert($data);

	if (!empty($action_info['rule'])) {
		//解析行为
		$rules = parse_action($action, $user_id);

		//执行行为
		$res = execute_action($rules, $action_info['id'], $user_id);
	}
}

/**
 * 解析行为规则
 * 规则定义  table:$table|field:$field|condition:$condition|rule:$rule[|cycle:$cycle|max:$max][;......]
 * 规则字段解释：table->要操作的数据表，不需要加表前缀；
 *              field->要操作的字段；
 *              condition->操作的条件，目前支持字符串，默认变量{$self}为执行行为的用户
 *              rule->对字段进行的具体操作，目前支持四则混合运算，如：1+score*2/2-3
 *              cycle->执行周期，单位（小时），表示$cycle小时内最多执行$max次
 *              max->单个周期内的最大执行次数（$cycle和$max必须同时定义，否则无效）
 * 单个行为后可加 ； 连接其他规则
 * @param string $action 行为id或者name
 * @param int $self 替换规则里的变量为执行用户的id
 * @return boolean|array: false解析出错 ， 成功返回规则数组
 * @author huajie <banhuajie@163.com>
 */
function parse_action($action = null, $self) {
	if (empty($action)) {
		return false;
	}

	//参数支持id或者name
	if (is_numeric($action)) {
		$map = array('id' => $action);
	} else {
		$map = array('name' => $action);
	}

	//查询行为信息
	$info = db('Action')->where($map)->find();
	if (!$info || $info['status'] != 1) {
		return false;
	}

	//解析规则:table:$table|field:$field|condition:$condition|rule:$rule[|cycle:$cycle|max:$max][;......]
	$rules  = $info['rule'];
	$rules  = str_replace('{$self}', $self, $rules);
	$rules  = explode(';', $rules);
	$return = array();
	foreach ($rules as $key => &$rule) {
		$rule = explode('|', $rule);
		foreach ($rule as $k => $fields) {
			$field = empty($fields) ? array() : explode(':', $fields);
			if (!empty($field)) {
				$return[$key][$field[0]] = $field[1];
			}
		}
		//cycle(检查周期)和max(周期内最大执行次数)必须同时存在，否则去掉这两个条件
		if (!array_key_exists('cycle', $return[$key]) || !array_key_exists('max', $return[$key])) {
			unset($return[$key]['cycle'], $return[$key]['max']);
		}
	}

	return $return;
}

/**
 * 执行行为
 * @param array $rules 解析后的规则数组
 * @param int $action_id 行为id
 * @param array $user_id 执行的用户id
 * @return boolean false 失败 ， true 成功
 * @author huajie <banhuajie@163.com>
 */
function execute_action($rules = false, $action_id = null, $user_id = null) {
	if (!$rules || empty($action_id) || empty($user_id)) {
		return false;
	}

	$return = true;
	foreach ($rules as $rule) {

		//检查执行周期
		$map                = array('action_id' => $action_id, 'user_id' => $user_id);
		$map['create_time'] = array('gt', time() - intval($rule['cycle']) * 3600);
		$exec_count         = db('ActionLog')->where($map)->count();
		if ($exec_count > $rule['max']) {
			continue;
		}

		//执行数据库操作
		$Model = db(ucfirst($rule['table']));
		$field = $rule['field'];
		$res   = $Model->where($rule['condition'])->setField($field, array('exp', $rule['rule']));

		if (!$res) {
			$return = false;
		}
	}
	return $return;
}

function avatar($uid, $size = 'middle') {
	$size = in_array($size, array('big', 'middle', 'small', 'real')) ? $size : 'middle';
	$dir  = setavatardir($uid);
	$file = BASE_PATH . '/static/avatar/' . $dir . 'avatar_' . $size . '.png';
	if (!file_exists('.' . $file)) {
		$file = BASE_PATH . '/static/images/default_avatar_' . $size . '.jpg';
	}
	return $file;
}

function setavatardir($uid) {
	$uid  = abs(intval($uid));
	$uid  = sprintf("%09d", $uid);
	$dir1 = substr($uid, 0, 3);
	$dir2 = substr($uid, 3, 2);
	$dir3 = substr($uid, 5, 2);
	$dir4 = substr($uid, 7, 2);
	$dir  = $dir1 . '/' . $dir2 . '/' . $dir3 . '/' . $dir4 . '/';
	if (!is_dir("./uploads/avatar/$dir")) {
		mk_dir("./uploads/avatar/" . $dir);
	}
	return $dir;
}

function mk_dir($dir, $mode = 0755) {
	if (is_dir($dir) || @mkdir($dir, $mode, true)) {
		return true;
	}

	if (!mk_dir(dirname($dir), $mode, true)) {
		return false;
	}

	return @mkdir($dir, $mode, true);
}

/**
 * 字符串转换为数组，主要用于把分隔符调整到第二个参数
 * @param  string $str  要分割的字符串
 * @param  string $glue 分割符
 * @return array
 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
 */
function str2arr($str = '', $glue = ',') {
	if ($str) {
		return explode($glue, $str);
	} else {
		return array();
	}
}

/**
 * 数组转换为字符串，主要用于把分隔符调整到第二个参数
 * @param  array  $arr  要连接的数组
 * @param  string $glue 分割符
 * @return string
 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
 */
function arr2str($arr = array(), $glue = ',') {
	if (empty($arr)) {
		return '';
	} else {
		return implode($glue, $arr);
	}
}

/**
 * 格式化字节大小
 * @param  number $size      字节数
 * @param  string $delimiter 数字和单位分隔符
 * @return string            格式化后的带单位的大小
 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
 */
function format_bytes($size, $delimiter = '') {
	$units = array('B', 'KB', 'MB', 'GB', 'TB', 'PB');
	for ($i = 0; $size >= 1024 && $i < 5; $i++) {
		$size /= 1024;
	}

	return round($size, 2) . $delimiter . $units[$i];
}

function get_grid_list($list_grids) {
	$grids = preg_split('/[;\r\n]+/s', trim($list_grids));
	foreach ($grids as &$value) {
		// 字段:标题:链接
		$val = explode(':', $value);
		// 支持多个字段显示
		$field = explode(',', $val[0]);
		$value = array('field' => $field, 'title' => $val[1]);
		if (isset($val[2])) {
			// 链接信息
			$value['href'] = $val[2];
			// 搜索链接信息中的字段信息
			preg_replace_callback('/\[([a-z_]+)\]/', function ($match) use (&$fields) {$fields[] = $match[1];}, $value['href']);
		}
		if (strpos($val[1], '|')) {
			// 显示格式定义
			list($value['title'], $value['format']) = explode('|', $val[1]);
		}
		foreach ($field as $val) {
			$array    = explode('|', $val);
			$fields[] = $array[0];
		}
	}
	$data = array('grids' => $grids, 'fields' => $fields);
	return $data;
}

// 获取属性类型信息
function get_attribute_type($type = '') {
	// TODO 可以加入系统配置
	$type_array       = config('config_type_list');
	static $type_list = array();
	foreach ($type_array as $key => $value) {
		$type_list[$key] = explode(',', $value);
	}
	return $type ? $type_list[$type][0] : $type_list;
}

//获得内容状态
function get_content_status($status) {
	$text = array(
		'-1' => '<span class="label label-danger">删除</span>',
		'0'  => '<span class="label label-default">禁用</span>',
		'1'  => '<span class="label label-primary">正常</span>',
		'2'  => '<span class="label label-info">待审核</span>',
	);
	return $text[$status];
}

/**
 * 获取分类信息并缓存分类
 * @param  integer $id    分类ID
 * @param  string  $field 要获取的字段名
 * @return string         分类信息
 */
function get_category($id, $field = null) {
	/* 非法分类ID */
	if (empty($id) || !is_numeric($id)) {
		return '';
	}

	$list = db('Category')->find($id);
	return is_null($field) ? $list : $list[$field];
}

/* 根据ID获取分类标识 */
function get_category_name($id) {
	return get_category($id, 'title');
}

/* 根据ID获取分类名称 */
function get_category_title($id) {
	return get_category($id, 'title');
}

//分类分组
function get_category_list_tree($model) {
	$list = cache('sys_category_list');

	/* 读取缓存数据 */
	if (empty($list)) {
		$list = db('Category')->select();
		cache('sys_category_list', $list);
	}
	if ($model) {
		foreach ($list as $key => $value) {
			$models = explode(',', $value['model']);
			if (in_array($model, $models)) {
				$res[] = $value;
			}
		}
	} else {
		$res = $list;
	}

	$tree = list_to_tree($res);
	return $tree;
}

//获取栏目子ID
function get_category_child($id) {
	$list = cache('sys_category_list');

	/* 读取缓存数据 */
	if (empty($list)) {
		$list = db('category')->select();
		cache('sys_category_list', $list);
	}
	$ids[] = $id;
	foreach ($list as $key => $value) {
		if ($value['pid'] == $id) {
			$ids[] = $value['id'];
			$ids   = array_merge($ids, get_category_child($value['id']));
		}
	}
	return array_unique($ids);
}

/**
 * 栏目文章当前位置
 * @param  integer $id   当前栏目ID
 * @param  string $ext   文章标题
 * @return here          当前栏目树
 * @author K先森 <77413254@qq.com>
 **/
function get_category_pos($id,$ext=''){
        $cat = db('Category');
        $here = '<a href="/">首页</a>';
        $uplevels = $cat->field("id,title,pid,model_id")->where("id=$id")->find();
        if(empty($uplevels)){
            return '栏目不存在';            
        }
        if($uplevels['pid'] != 0)
        $here .= get_category_uplevels($uplevels['pid']);
        $modelid = $uplevels['model_id'];
        $modelname = db('Model')->field("name")->where("id = $modelid")->find();
        $links = url('index/content/lists?model='.$modelname['name'],array('id'=>$uplevels['id']));        
        $here .= ' &gt;&gt; <a href="'.$links.'">'.$uplevels['title']."</a>";
        if($ext != '') $here .= ' &gt;&gt; '.$ext;
        return $here;
}
function get_category_uplevels($id){
        $cat = db('Category');
        $here = '';
        $uplevels = $cat->field("id,title,pid,model_id")->where("id=$id")->find();
        $modelid = $uplevels['model_id'];
        $modelname = db('Model')->field("name")->where("id = $modelid")->find();
        $links = url('index/content/lists?model='.$modelname['name'],array('id'=>$uplevels['id']));
        $here .= ' &gt;&gt; <a href="'.$links.'">'.$uplevels['title']."</a>";
        if($uplevels['pid'] != 0){
            $here = get_category_uplevels($uplevels['pid']).$here;
        }
        return $here;
}


function send_email($to, $subject, $message) {
	$config = array(
		'protocol'  => 'smtp',
		'smtp_host' => \think\Config::get('mail_host'),
		'smtp_user' => \think\Config::get('mail_username'),
		'smtp_pass' => \think\Config::get('mail_password'),
	);
	$email = new \com\Email($config);
	$email->from(\think\Config::get('mail_fromname'), \think\Config::get('web_site_title'));
	$email->to($to);

	$email->subject($subject);
	$email->message($message);

	return $email->send();
}

//实例化模型
function M($name, $type = 'model') {
	if ($type == 'model') {
		return new \app\common\model\Content(strtolower($name));
	} elseif ($type == 'form') {
		return new \app\common\model\DiyForm(strtolower($name));
	}
}

//php获取中文字符拼音首字母
function getFirstCharter($s0) {
	$fchar = ord($s0{0});
	if ($fchar >= ord("A") and $fchar <= ord("z")) {
		return strtoupper($s0{0});
	}

	$s1 = \iconv("UTF-8", "gb2312", $s0);
	$s2 = \iconv("gb2312", "UTF-8", $s1);
	if ($s2 == $s0) {$s = $s1;} else { $s = $s0;}
	$asc = ord($s{0}) * 256 + ord($s{1}) - 65536;
	if ($asc >= -20319 and $asc <= -20284) {
		return "A";
	}

	if ($asc >= -20283 and $asc <= -19776) {
		return "B";
	}

	if ($asc >= -19775 and $asc <= -19219) {
		return "C";
	}

	if ($asc >= -19218 and $asc <= -18711) {
		return "D";
	}

	if ($asc >= -18710 and $asc <= -18527) {
		return "E";
	}

	if ($asc >= -18526 and $asc <= -18240) {
		return "F";
	}

	if ($asc >= -18239 and $asc <= -17923) {
		return "G";
	}

	if ($asc >= -17922 and $asc <= -17418) {
		return "H";
	}

	if ($asc >= -17417 and $asc <= -16475) {
		return "J";
	}

	if ($asc >= -16474 and $asc <= -16213) {
		return "K";
	}

	if ($asc >= -16212 and $asc <= -15641) {
		return "L";
	}

	if ($asc >= -15640 and $asc <= -15166) {
		return "M";
	}

	if ($asc >= -15165 and $asc <= -14923) {
		return "N";
	}

	if ($asc >= -14922 and $asc <= -14915) {
		return "O";
	}

	if ($asc >= -14914 and $asc <= -14631) {
		return "P";
	}

	if ($asc >= -14630 and $asc <= -14150) {
		return "Q";
	}

	if ($asc >= -14149 and $asc <= -14091) {
		return "R";
	}

	if ($asc >= -14090 and $asc <= -13319) {
		return "S";
	}

	if ($asc >= -13318 and $asc <= -12839) {
		return "T";
	}

	if ($asc >= -12838 and $asc <= -12557) {
		return "W";
	}

	if ($asc >= -12556 and $asc <= -11848) {
		return "X";
	}

	if ($asc >= -11847 and $asc <= -11056) {
		return "Y";
	}

	if ($asc >= -11055 and $asc <= -10247) {
		return "Z";
	}

	return null;
}

function PyFirst($zh) {
	$ret = "";
	$s1  = \iconv("UTF-8", "gb2312", $zh);
	$s2  = \iconv("gb2312", "UTF-8", $s1);
	if ($s2 == $zh) {$zh = $s1;}
	for ($i = 0; $i < strlen($zh); $i++) {
		$s1 = substr($zh, $i, 1);
		$p  = ord($s1);
		if ($p > 160) {
			$s2 = substr($zh, $i++, 2);
			$ret .= getFirstCharter($s2);
		} else {
			$ret .= $s1;
		}
	}
	return $ret;
}