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
 * @title 友情链接
 * @description 友情链接
 */
class Link extends Admin
{

    /**
     * @title 链接列表
     */
    public function index()
    {
        $map = array();

        $order = "id desc";
        $list = db('Link')->where($map)->order($order)->paginate(10, false, array(
            'query' => $this->request->param()
        ));

        $data = array(
            'list' => $list,
            'page' => $list->render(),
        );
        $this->assign($data);
        $this->setMeta("友情链接");
        return $this->fetch();
    }

    /**
     * @title 添加链接
     */
    public function add()
    {
        $link = model('Link');
        if ($this->request->isPost()) {
            $data = input('post.');
            if ($data) {
                unset($data['id']);
                $result = $link->save($data);
                if ($result) {
                    return $this->success("添加成功！", url('Link/index'));
                } else {
                    return $this->error($link->getError());
                }
            } else {
                return $this->error($link->getError());
            }
        } else {
            $data = array(
                'keyList' => $link->keyList,
            );
            $this->assign($data);
            $this->setMeta("添加友链");
            return $this->fetch('public/edit');
        }
    }


    /**
     * @title 修改链接
     */
    public function edit()
    {
        $link = model('Link');
        $id = input('id', '', 'trim,intval');
        if ($this->request->isPost()) {
            $data = input('post.');
            if ($data) {
                $result = $link->save($data, array('id' => $data['id']));
                if ($result) {
                    return $this->success("修改成功！", url('Link/index'));
                } else {
                    return $this->error("修改失败！");
                }
            } else {
                return $this->error($link->getError());
            }
        } else {
            $map = array('id' => $id);
            $info = db('Link')->where($map)->find();

            $data = array(
                'keyList' => $link->keyList,
                'info' => $info,
            );
            $this->assign($data);
            $this->setMeta("编辑友链");
            return $this->fetch('public/edit');
        }
    }

    /**
     * @title 删除链接
     */
    public function delete()
    {
        $id = $this->getArrayParam('id');
        if (empty($id)) {
            return $this->error('非法操作！');
        }
        $link = db('Link');

        $map = array('id' => array('IN', $id));
        $result = $link->where($map)->delete();
        if ($result) {
            return $this->success("删除成功！");
        } else {
            return $this->error("删除失败！");
        }
    }

    public function domain($page = 1, $r = 20)
    {

        $linkid = input('linkid');
        $this->assign('linkid', $linkid);
        $usemap = ['link_id' => $linkid, 'status' => 1];
        $total = db('web_link')->where($usemap)->count();
        $this->assign('counter', $total);
        $one = db('link')->where(['id' => $linkid])->find();
        $linklist = db('web_link')->alias('web')->where(['link_id' => $linkid])->select();

        $this->assign('linklist', $linklist);
        $list = db('web_config')->order('id desc')->paginate($r, false, array(
            'query' => $this->request->param()
        ));

        $data = array(
            'one' => $one,
            'list' => $list,
            'page' => $list->render(),
        );
        $this->assign($data);
        $this->setMeta("域名分配列表");
        return $this->fetch();

    }

    public function linkbinddomain()
    {

        try{
            $linkid = input('linkid');
            $webid = input('webid');
            $statustr = input('status') ? '加入域名' : '取消域名';
            $status = input('status') ? 1 : 0;
            $one = db('web_link')->where(['link_id' => $linkid, 'web_id' => $webid])->find();
            $data['web_id'] = $webid;
            $data['link_id'] = $linkid;
            $data['status'] = $status;
            if ($one) {
                $id = $one['id'];
                $re = db('web_link')->where(['id' => $id])->update($data);
            } else {
                //insert
                $re = db('web_link')->insert($data);
            }
            $usemap = ['link_id' => $linkid, 'status' => 1];
            $total = db('web_link')->where($usemap)->count();

            if ($re) {
                $returnarr = ['status' => 1, 'msg' => $statustr . '成功'];
            } else {
                $returnarr = ['status' => 0, 'msg' => $statustr . '失败'];
            }

            $usemap = ['link_id' => $linkid, 'status' => 1];
            $total = db('web_link')->where($usemap)->count();

            $returnarr=array_merge($returnarr,['total'=>$total]);

        }catch (\Exception $e){
            $returnarr = ['status' => 0, 'msg' =>  '发生错误'];
        }




        return json($returnarr);

    }

}