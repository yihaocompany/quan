<?php
// +----------------------------------------------------------------------
// | YihaoCMS [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://diao.info All rights reserved.
// +----------------------------------------------------------------------
// | Author: molong <molong@tensent.cn> <http://diao.info>
// +----------------------------------------------------------------------

namespace app\admin\controller;
use app\common\controller\Admin;

/**
 * @title 频道管理
 * @description 频道管理
 */
class Channel extends Admin {

	public function _initialize() {
		parent::_initialize();
	}

	/**
	 * @title 频道列表
	 */
	public function index($type = 0) {
		/* 获取频道列表 */
		//$map  = array('status' => array('gt', -1), 'pid'=>$pid);
		$map  = array('status' => array('gt', -1));
		if ($type) {
			$map['type']   = $type;
		}
		$list = db('Channel')->where($map)->order('sort asc,id asc')->column('*', 'id');

		if (!empty($list)) {
			$tree = new \com\Tree();
			$list = $tree->toFormatTree($list);
		}

		config('_sys_get_channel_tree_', true);

		$data = array(
			'tree' => $list,
			'type' => $type
		);
		$this->assign($data);
		$this->setMeta('导航管理');
		return $this->fetch();
	}

	/**
	 * @title 单字段编辑
	 */
	public function editable($name = null, $value = null, $pk = null) {
		if ($name && ($value != null || $value != '') && $pk) {
			model('Channel')->where(array('id' => $pk))->setField($name, $value);
		}
	}

	/**
	 * @title 添加频道
	 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
	 */
	public function add() {
		if ($this->request->isPost()) {
			$Channel = model('Channel');
			$data    = $this->request->post();
			if ($data) {
				$id = $Channel->save($data);
				if ($id) {
					return $this->success('新增成功', url('index'));
					//记录行为
					action_log('update_channel', 'channel', $id, session('user_auth.uid'));
				} else {
					return $this->error('新增失败');
				}
			} else {
				$this->error($Channel->getError());
			}
		} else {
			$pid = input('pid', 0);
			//获取父导航
			if (!empty($pid)) {
				$parent = db('Channel')->where(array('id' => $pid))->field('title')->find();
				$this->assign('parent', $parent);
			}

			$pnav = db('Channel')->where(array('pid' => '0'))->select();
			$this->assign('pnav', $pnav);
			$this->assign('pid', $pid);
			$this->assign('info', null);
			$this->setMeta('新增导航');
			return $this->fetch('edit');
		}
	}
	/**
	 * @title 编辑频道
	 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
	 */
	public function edit($id = 0) {
		if ($this->request->isPost()) {
			$Channel = model('Channel');
			$data    = $this->request->post();
			if ($data) {
				if (false !== $Channel->save($data, array('id' => $data['id']))) {
					//记录行为
					action_log('update_channel', 'channel', $data['id'], session('user_auth.uid'));
					return $this->success('编辑成功', url('index'));
				} else {
					return $this->error('编辑失败');
				}
			} else {
				return $this->error($Channel->getError());
			}
		} else {
			$info = array();
			/* 获取数据 */
			$info = db('Channel')->find($id);

			if (false === $info) {
				return $this->error('获取配置信息错误');
			}

			$pid = input('pid', 0);
			//获取父导航
			if (!empty($pid)) {
				$parent = db('Channel')->where(array('id' => $pid))->field('title')->find();
				$this->assign('parent', $parent);
			}

			$pnav = db('Channel')->where(array('pid' => '0'))->select();
			$this->assign('pnav', $pnav);
			$this->assign('pid', $pid);
			$this->assign('info', $info);
			$this->setMeta('编辑导航');
			return $this->fetch();
		}
	}
	/**
	 * @title 删除频道
	 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
	 */
	public function del() {
		$id = $this->getArrayParam('id');

		if (empty($id)) {
			return $this->error('请选择要操作的数据!');
		}

		$map = array('id' => array('in', $id));
		if (db('Channel')->where($map)->delete()) {
                        //删除category中的ismenu字段记录
                        $map = array('ismenu' => array('in', $id));    
                        db('Category')->where($map)->setField('ismenu',0);  
			//记录行为
			action_log('update_channel', 'channel', $id, session('user_auth.uid'));
			return $this->success('删除成功');
		} else {
			return $this->error('删除失败！');
		}
	}
	/**
	 * @title 导航排序
	 * @author huajie <banhuajie@163.com>
	 */
	public function sort() {
		if ($this->request->isGet()) {
			$ids = input('ids');
			$pid = input('pid');
			//获取排序的数据
			$map = array('status' => array('gt', -1));
			if (!empty($ids)) {
				$map['id'] = array('in', $ids);
			} else {
				if ($pid !== '') {
					$map['pid'] = $pid;
				}
			}
			$list = db('Channel')->where($map)->field('id,title')->order('sort asc,id asc')->select();

			$this->assign('list', $list);
			$this->setMeta('导航排序');
			return $this->fetch();
		} elseif ($this->request->isPost()) {
			$ids = input('post.ids');
			$ids = explode(',', $ids);
			foreach ($ids as $key => $value) {
				$res = db('Channel')->where(array('id' => $value))->setField('sort', $key + 1);
			}
			if ($res !== false) {
				return $this->success('排序成功！', url('admin/channel/index'));
			} else {
				return $this->error('排序失败！');
			}
		} else {
			return $this->error('非法请求！');
		}
	}

	/**
	 * @title 设置状态
	 */
	public function setStatus() {
		$id     = array_unique((array) input('ids', 0));
		$status = input('status', '0', 'trim');

		if (empty($id)) {
			return $this->error('请选择要操作的数据!');
		}

		$map    = array('id' => array('in', $id));
		$result = db('Channel')->where($map)->update(array('status' => $status));
		if ($result) {
			return $this->success("操作成功！");
		} else {
			return $this->error("操作失败！");
		}
	}

    public function domain(){

        $id = input('nav_id');
        if($id>0){
            $map=['web.status'=>1];
            $list=db('web_config')->alias('web')
                ->Join("nav_web nav",'web.id = nav.web_id',"left")->join('channel ch','ch.id=nav.nav_id','left')
                ->field("ch.title as topmenu , web.id as id,web.domain as domain,nav.menu as menu,nav.id as menuid,nav.status as status,web.status as webstatus,nav.title as navtitle,nav.keywords as navkeywords, nav.description as navdescription")
                ->where($map)->select();
            $this->assign('list', $list);
            $this->assign('nav_id', $id);
            return $this->fetch();
        }else{
            return $this->error('请选择要操作的数据!');

        }

    }

    public function fornav(){
        return $this->fetch();
    }
    public function selectdomain(){

        $web_id = input('web_id');
        $nav_id = input('nav_id');
        $status=input('status');
        if ($web_id>0 and $nav_id>0){

            //此domain是不是存在 是不是有效

            $where=['id'=>$web_id,'status'=>1];
            if(!db('web_config')->where($where)->find()){
                //修改
                return json(['code'=>'0','msg'=>'域名不正常']);
            }

            $map=['web_id'=>$web_id,'nav_id'=>$nav_id];
            $record=db('nav_web')->where($map)->find();
            if($record){
                //修改
                $newmap['id']=$record['id'];
                if(db('nav_web')->where($newmap)->update(['status'=>$status])){
                    return json(['code'=>'1','msg'=>'修改成功']);
                }else{
                    return json(['code'=>'0','msg'=>'修改失败']);
                }
            }else{
                //增加

                $newdata=['web_id'=>$web_id,'nav_id'=>$nav_id,'status'=>$status];

                if(db('nav_web')->where($map)->insert($newdata)){
                    return json(['code'=>'1','msg'=>'修改成功']);
                }else{
                    return json(['code'=>'0','msg'=>'修改失败']);
                }

            }
            //如果没有记录到

            $data['status'] = input('status');
            $where['id'] = $id;
            if($this->mod->Dosave($data, $where)){
                $rdata=array('status'=>1,'info'=>'修改成功');
                cache('links',null);
            }else{
                $rdata=array('status'=>0,'info'=>'修改失败');
            }
        }else{
            $rdata=array('status'=>0,'info'=>'修改失败');
        }
        return json($rdata);



    }
}