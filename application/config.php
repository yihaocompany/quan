<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

return [
    // +----------------------------------------------------------------------
    // | 应用设置
    // +----------------------------------------------------------------------

    // 应用调试模式
    'app_debug'              => true,

    // 视图输出字符串内容替换
    'view_replace_str'       => [
        '__PUBLIC__'     => '/static',
        '__STATIC__'     => '/static',
        '__JS__'     => '/static/js',
        '__CSS__'     => '/static/css',
    ],
    // 默认跳转页面对应的模板文件
    'dispatch_success_tmpl'  => THINK_PATH . 'tpl' . DS . 'dispatch_jump.tpl',
    'dispatch_error_tmpl'    => THINK_PATH . 'tpl' . DS . 'dispatch_jump.tpl',

    // +----------------------------------------------------------------------
    // | 会话设置
    // +----------------------------------------------------------------------

    'session'                => [
        'id'             => '',
        // SESSION_ID的提交变量,解决flash上传跨域
        'var_session_id' => '',
        // SESSION 前缀
        'prefix'         => 'think',
        // 驱动方式 支持redis memcache memcached
        'type'           => '',
        // 是否自动开启 SESSION
        'auto_start'     => true,
    ],

    //分页配置
    'paginate'               => [
        'type'      => 'bootstrap',
        'var_page'  => 'page',
        'list_rows' => 15,
    ],

    'picture_upload'         => [
        'rootPath'   => ROOT_PATH . DS .'/web/uploads/',
        'size'       => '2',
        'ext'        => 'jpg,jpeg,png,gif'
    ],

    'attachment_upload'      => [
        'rootPath'   => ROOT_PATH . DS .'/web/uploads/',
        'size'       => '2',
        'ext'        => 'doc,docx,xls,xlsx,ppt,pptx,zip,rar'
    ],

    'picture_upload'         => [
        'rootPath'   => ROOT_PATH . DS .'/web/uploads/',
        'size'       => '2',
        'ext'        => 'jpg,jpeg,png,gif'
    ],
];
