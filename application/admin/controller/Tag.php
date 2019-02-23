<?php

namespace app\admin\controller;
use app\common\controller\Admin;

/**
 * @title 关键字管理
 * @description 关键字管理
 */
class Tag extends Admin {
    protected $db;

    public function _initialize() {
        parent::_initialize();
        $this->db      = db('tag');

    }

    /**
     * @title 关键字例表
     */
    public function index($page = 1, $r = 20) {
        //读取规则列表
        $map = array('status' => array('EGT', 0));

        $list = $this->db->where($map)->order('sort asc')->paginate(10, false, array(
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