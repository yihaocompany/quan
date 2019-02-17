<?php
// +----------------------------------------------------------------------
// | YihaoCMS [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.tensent.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: molong <molong@tensent.cn> <http://www.tensent.cn>
// +----------------------------------------------------------------------

namespace app\common\model;

use \think\Validate;
use \think\Loader;

/**
 * @title 基础模型
 * 自定义基础模型
 */
class BaseModel {

	protected $data;
	protected $autoWriteTimestamp = true;
	protected $auto               = array();
	protected $insert             = array();
	protected $update             = array();
	// 创建时间字段
	protected $createTime = 'create_time';
	// 更新时间字段
	protected $updateTime = 'update_time';
	// 时间字段取出后的默认时间格式
	protected $dateFormat;
	// 字段类型或者格式转换
	protected $type = [];
	protected $attrDb = 'Attribute';

	public function __construct($name) {
		if ($this->attrDb == 'FormAttr') {
			$this->db = db('Form'.ucfirst($name));
		}else{
			$this->db = db($name);
		}
	}

	public function save($data, $where = array()) {
		$this->data = $data;
		$rule       = $msg       = array();
		if ($this->attrDb == 'FormAttr') {
			$attr       = db($this->attrDb)->where('form_id', $data['form_id'])->select();
		}else{
			$attr       = db($this->attrDb)->where('model_id', $data['model_id'])->select();
		}
		foreach ($attr as $key => $value) {
			if ($value['is_must'] == 1) {
				$rule[$value['name']]             = "require";
				$msg[$value['name'] . '.require'] = $value['title'] . "不能为空！";
			}
			if ($value['name'] == 'uid') {
				$this->data['uid'] = session('user_auth.uid');
			}
			if ($value['is_must'] == 1 && $value['is_show'] == 0) {
				$this->data[$value['name']] = $value['value'];
			}
			if ($value['type'] == 'datetime' || $value['type'] == 'date') {
				if (isset($data[$value['name']]) && $data[$value['name']]) {
					$this->data[$value['name']] = strtotime($data[$value['name']]);
				}else{
					$this->data[$value['name']] = time();
				}
			}
		}
		$this->autoCompleteData($this->auto);
		if (!empty($where)) {
			$this->autoCompleteData($this->update);

			$validate = new Validate($rule, $msg);
			$result   = $validate->check($this->data);
			if (!$result) {
				$this->error = $validate->getError();
				return false;
			}
			return $this->where($where)->update($this->data);
		} else {
			$this->autoCompleteData($this->insert);

			$validate = new Validate($rule, $msg);
			$result   = $validate->check($this->data);
			if (!$result) {
				$this->error = $validate->getError();
				return false;
			}
			return $this->insert($this->data);
		}
	}

	/**
	 * 返回模型的错误信息
	 * @access public
	 * @return string|array
	 */
	public function getError() {
		return $this->error;
	}

	/**
	 * 数据自动完成
	 * @access public
	 * @param array $auto 要自动更新的字段列表
	 * @return void
	 */
	protected function autoCompleteData($auto = []) {
		foreach ($auto as $field => $value) {
			if (is_integer($field)) {
				$field = $value;
				$value = null;
			}

			if (!isset($this->data[$field])) {
				$default = null;
			} else {
				$default = $this->data[$field];
			}

			$this->setAttr($field, !is_null($value) ? $value : $default);
		}
	}
	/**
	 * 修改器 设置数据对象值
	 * @access public
	 * @param string $name  属性名
	 * @param mixed  $value 属性值
	 * @param array  $data  数据
	 * @return $this
	 */
	public function setAttr($name, $value, $data = []) {
		if ((is_null($value) || !$value || $value != '0') && $this->autoWriteTimestamp && in_array($name, [$this->createTime, $this->updateTime])) {
			// 自动写入的时间戳字段
			$value = $this->autoWriteTimestamp($name);
		} else {
			// 检测修改器
			$method = 'set' . Loader::parseName($name, 1) . 'Attr';
			if (method_exists($this, $method)) {
				$value = $this->$method($value, array_merge($this->data, $data));
			} elseif (isset($this->type[$name])) {
				// 类型转换
				$value = $this->writeTransform($value, $this->type[$name]);
			}
		}

		// 设置数据对象属性
		$this->data[$name] = $value;
		return $this;
	}

	/**
	 * 自动写入时间戳
	 * @access public
	 * @param string $name 时间戳字段
	 * @return mixed
	 */
	protected function autoWriteTimestamp($name) {
		if (isset($this->type[$name])) {
			$type = $this->type[$name];
			if (strpos($type, ':')) {
				list($type, $param) = explode(':', $type, 2);
			}
			switch ($type) {
			case 'datetime':
			case 'date':
				$format = !empty($param) ? $param : $this->dateFormat;
				$value  = $this->formatDateTime(time(), $format);
				break;
			case 'timestamp':
			case 'integer':
			default:
				$value = time();
				break;
			}
		} elseif (is_string($this->autoWriteTimestamp) && in_array(strtolower($this->autoWriteTimestamp), [
			'datetime',
			'date',
			'timestamp',
		])
		) {
			$value = $this->formatDateTime(time(), $this->dateFormat);
		} else {
			$value = $this->formatDateTime(time(), $this->dateFormat, true);
		}
		return $value;
	}

	/**
	 * 时间日期字段格式化处理
	 * @access public
	 * @param mixed $time      时间日期表达式
	 * @param mixed $format    日期格式
	 * @param bool  $timestamp 是否进行时间戳转换
	 * @return mixed
	 */
	protected function formatDateTime($time, $format, $timestamp = false) {
		if (false !== strpos($format, '\\')) {
			$time = new $format($time);
		} elseif (!$timestamp && false !== $format) {
			$time = date($format, $time);
		}
		return $time;
	}

	/**
	 * 数据写入 类型转换
	 * @access public
	 * @param mixed        $value 值
	 * @param string|array $type  要转换的类型
	 * @return mixed
	 */
	protected function writeTransform($value, $type) {
		if (is_null($value)) {
			return;
		}

		if (is_array($type)) {
			list($type, $param) = $type;
		} elseif (strpos($type, ':')) {
			list($type, $param) = explode(':', $type, 2);
		}
		switch ($type) {
		case 'integer':
			$value = (int) $value;
			break;
		case 'float':
			if (empty($param)) {
				$value = (float) $value;
			} else {
				$value = (float) number_format($value, $param, '.', '');
			}
			break;
		case 'boolean':
			$value = (bool) $value;
			break;
		case 'timestamp':
			if (!is_numeric($value)) {
				$value = strtotime($value);
			}
			break;
		case 'datetime':
			$format = !empty($param) ? $param : $this->dateFormat;
			$value  = is_numeric($value) ? $value : strtotime($value);
			$value  = $this->formatDateTime($value, $format);
			break;
		case 'object':
			if (is_object($value)) {
				$value = json_encode($value, JSON_FORCE_OBJECT);
			}
			break;
		case 'array':
			$value = (array) $value;
		case 'json':
			$option = !empty($param) ? (int) $param : JSON_UNESCAPED_UNICODE;
			$value  = json_encode($value, $option);
			break;
		case 'serialize':
			$value = serialize($value);
			break;

		}
		return $value;
	}


    /**
     * 获取对象原始数据 如果不存在指定字段返回false
     * @access public
     * @param string $name 字段名 留空获取全部
     * @return mixed
     * @throws InvalidArgumentException
     */
    public function getData($name = null)
    {
        if (is_null($name)) {
            return $this->data;
        } elseif (array_key_exists($name, $this->data)) {
            return $this->data[$name];
        } elseif (array_key_exists($name, $this->relation)) {
            return $this->relation[$name];
        } else {
            throw new InvalidArgumentException('property not exists:' . $this->class . '->' . $name);
        }
    }
    
	public function __call($method, $args) {
		return call_user_func_array([$this->db, $method], $args);
	}
}