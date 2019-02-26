<?php

namespace app\admin\controller;
use app\common\controller\Admin;
use app\common\controller\Upload as pupload;

/**
 * @title 关键字管理
 * @description 关键字管理
 */
class Articles extends Admin {
    protected $db;
    public function _initialize() {
        parent::_initialize();
        $this->db      = db('article');
    }

    /**
     * @title 关键字例表
     */
    public function index($page = 1, $r = 20) {
        //读取规则列表
        $list =  db('article')->alias('a')->join('picture p','p.id=a.pic','left')->field('a.*,p.path as picture')->order('a.counter desc')->paginate($r, false, array(
            'query'  => $this->request->param()
        ));

        $data = array(
            'list' => $list,
            'page' => $list->render(),
        );
        $this->assign($data);
        $this->setMeta("关键字列表");
        return $this->fetch();
    }
    /**
     * @title 添加链接
     */
    public function add() {
        $article= db('article');
        if ($this->request->isPost()) {
            $data = input('post.');
            if ($data) {
                unset($data['id']);
                $data['create_at']=strtotime('now');
                $result = $article->insert($data);
                if ($result) {
                    return $this->success("添加成功！", url('Articles/index'));
                } else {
                    return $this->error($article->getError());
                }
            } else {
                return $this->error($article->getError());
            }
        } else {
            $data = array(
                'info' => null,
            );
            $this->assign($data);
            $this->setMeta("添加文章");
            return $this->fetch('edit');
        }
    }


    /**
     * @title 修改链接
     */
    public function edit() {
        $link = db('article');
        $id   = input('id', '', 'trim,intval');
        if ($this->request->isPost()) {
            $data = input('post.');
            if ($data) {
                $result = $link->where( array('id' => $data['id']))->update($data);
                if ($result) {
                    return $this->success("修改成功！", url('Articles/index'));
                } else {
                    return $this->error("修改失败！");
                }
            } else {
                return $this->error($link->getError());
            }
        } else {
            $map  = array('id' => $id);
            $info = db('article')->where($map)->find();

            $data = array(
                'info'    => $info,
            );
            $this->assign($data);
            $this->setMeta("编辑文章");
            return $this->fetch('edit');
        }
    }

    /**
     * @title 删除链接
     */
    public function delete() {
        $id = $this->getArrayParam('id');
        if (empty($id)) {
            return $this->error('非法操作！');
        }
        $link = db('Link');

        $map    = array('id' => array('IN', $id));
        $result = $link->where($map)->delete();
        if ($result) {
            return $this->success("删除成功！");
        } else {
            return $this->error("删除失败！");
        }
    }

    public function picuploads(){
        $pic=new pupload();
        $ed=input('ed', '0', 'trim');
        $pic->upload($ed);
    }

    public function del(){
        $param = $this->request->param();
        $id = $param['id'];
        if (empty($id)) {
            return $this->error("非法操作！");
        }

        $map['id'] = array('IN', $id);
        $result    = db('article')->where($map)->delete();

        if (false !== $result) {
            //记录行为
            action_log('delete_content', 'content', $result, session('auth_user.uid'));
            return $this->success("删除成功！");
        } else {
            return $this->error("删除失败！");
        }
    }

    public function tag($page = 1, $r = 20) {
        //读取规则列表


        $list = db('tag')->order('counter desc')->paginate($r, false, array(
            'query'  => $this->request->param()
        ));

        $data = array(
            'list' => $list,
            'page' => $list->render(),
        );
        $this->assign($data);
        $this->setMeta("关键字列表");
        return $this->fetch();
    }







}