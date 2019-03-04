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

        $id=input('id');
        $this->assign('articleid',$id);
        $usemap=['article_id'=>$id,'status'=>1];
        $total=db('article_tag')->where($usemap)->count();



        $this->assign('counter',$total);
        $articleone=db('article')->where(['id'=>$id])->find();
        $list = db('tag')->alias('t')->join('article_tag ats','ats.	tag_id=t.id','left')->join('article a','a.id=ats.article_id','left')
            ->field('t.*,a.id as article_id,ats.tag_id,a.title,a.id as article_id,ats.status as status')->order('t.counter desc')->paginate($r, false, array(
            'query'  => $this->request->param()
        ));

        $data = array(
            'article'=>$articleone,
            'list' => $list,
            'page' => $list->render(),
        );
        $this->assign($data);
        $this->setMeta("关键字列表");
        return $this->fetch();
    }

    /*
     * 文章绑定TAGS
     */
    public function tagbindarticle(){
        $id    =input('articleid');
        $tagid =input('tagid');
        $statustr=input('status')?'加入标签':'取消标签';
        $status=input('status')?1:0;
        if($id&&$tagid){
            if($status){
                $totalmap=['article_id'=>$id,'status'=>1];
                if(db('article_tag')->where($totalmap)->count()>=cache('db_config_data')['tags']){
                    $returnarr=['status'=>0,'msg'=>$statustr.'不能超过'.ache('db_config_data')['tags']];
                    return json($returnarr);

                }
            }
            $usemap=['article_id'=>$id,'status'=>1];
            $map=['tag_id'=>$tagid,'article_id'=>$id];
            $onerow=db('article_tag')->where($map)->find();
            if($onerow){
                $data=['tag_id'=>$tagid,'article_id'=>$id,'status'=>$status];
                if(db('article_tag')->where($map)->update($data)){
                    $returnarr=['status'=>1,'msg'=>$statustr.'成功'];
                }else{
                    $returnarr=['status'=>0,'msg'=>$statustr.'失败'];
                }

            }else{
                $data=['tag_id'=>$tagid,'article_id'=>$id,'status'=>$status];
                if(db('article_tag')->insert($data)){

                    $returnarr=['status'=>1,'msg'=>$statustr.'成功'];
                }else{
                    $returnarr=['status'=>0,'msg'=>$statustr.'失败'];
                }

            }
            $total=db('article_tag')->where($usemap)->count();
            $returnarr['total']=$total;

        }else{
            $returnarr=['status'=>0,'msg'=>$status.'失败','total'=>0];
        }

        return json($returnarr);
    }




    /*
  * 文章绑定分类
  */
    public function categorybindarticle(){
        $articleid    =input('articleid');
        $categoryid =input('categoryid');
        $statustr=input('status')?'加入分类':'取消分类';
        $status=input('status')?1:0;
        if($articleid&&$articleid){
            if($status){
                $totalmap=['article_id'=>$articleid,'status'=>1];
                $cats=cache('db_config_data')['cats'];

                if(db('article_category')->where($totalmap)->count()>=$cats){
                    $returnarr=['status'=>0,'msg'=>$statustr.'不能超过'.$cats];
                    return json($returnarr);

                }
            }
            $usemap=['article_id'=>$articleid,'status'=>1];
            $map=['category_id'=>$categoryid,'article_id'=>$articleid];
            $onerow=db('article_category')->where($map)->find();
            if($onerow){
                $data=['category_id'=>$categoryid,'article_id'=>$articleid,'status'=>$status];
                if(db('article_category')->where($map)->update($data)){
                    $returnarr=['status'=>1,'msg'=>$statustr.'成功'];
                }else{
                    $returnarr=['status'=>0,'msg'=>$statustr.'失败'];
                }

            }else{
                $data=['category_id'=>$categoryid,'article_id'=>$articleid,'status'=>$status];
                if(db('article_category')->insert($data)){
                    $returnarr=['status'=>1,'msg'=>$statustr.'成功'];
                }else{
                    $returnarr=['status'=>0,'msg'=>$statustr.'失败'];
                }

            }
            $total=db('article_category')->where($usemap)->count();
            $returnarr['total']=$total;

        }else{
            $returnarr=['status'=>0,'msg'=>$status.'失败','total'=>0];
        }

        return json($returnarr);
    }



    public function  category($page = 1, $r = 20) {

        $articleid=input('articleid');
        $this->assign('articleid',$articleid);
        $usemap=['article_id'=>$articleid,'status'=>1];
        $total=db('article_category')->where($usemap)->count();



        $this->assign('counter',$total);
        $articleone=db('article')->where(['id'=>$articleid])->find();
        $selectcategory=db('article_category')->where(['article_id'=>$articleid])->select();


        $this->assign('selectcategory',$selectcategory);

        $list = db('category')->order('id desc')->paginate($r, false, array(
            'query'  => $this->request->param()
        ));

        $data = array(
            'article'=>$articleone,
            'list' => $list,
            'page' => $list->render(),
        );
        $this->assign($data);
        $this->setMeta("分类列表");
        return $this->fetch();
    }



    public function burl($page = 1, $r = 20) {
        //读取规则列表
        $map['status']=1;

        $list = db('web_config')->where($map)->order('id desc')->paginate($r, false, array(
            'query'  => $this->request->param()
        ));

        $data = array(
            'list' => $list,
            'page' => $list->render(),
        );
        $this->assign($data);
        $this->setMeta("域名列表");
        return $this->fetch();
    }






}