// +----------------------------------------------------------------------
// | YihaoCMS [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://diao.info All rights reserved.
// +----------------------------------------------------------------------
// | Author: molong <molong@tensent.cn> <http://diao.info>
// +----------------------------------------------------------------------

// 当前资源URL目录
var baseRoot = (function () {
    var scripts = document.scripts, src = scripts[scripts.length - 1].src;
    return src.substring(0, src.lastIndexOf("/") - 2);
})();

// 配置参数
require.config({
    waitSeconds: 60,
    baseUrl: baseRoot,
    map: {'*': {css: baseRoot + 'plugs/require/require.css.js'}},
    paths: {
        'template': ['plugs/template/template'],
        'pcasunzips': ['plugs/jquery/pcasunzips'],
        // openSource
        'json': ['plugs/jquery/json2.min'],
        'layui': ['plugs/layui/layui'],
        'base64': ['plugs/jquery/base64.min'],
        'angular': ['plugs/angular/angular.min'],
        'ckeditor': ['plugs/ckeditor/ckeditor'],
        'websocket': ['plugs/socket/websocket'],
        // jQuery
        'jquery.ztree': ['plugs/ztree/jquery.ztree.all.min'],
        'jquery.masonry': ['plugs/jquery/masonry.min'],
        'jquery.cookies': ['plugs/jquery/jquery.cookie'],
        // bootstrap
        'bootstrap': ['plugs/bootstrap/js/bootstrap.min'],
        'bootstrap.typeahead': ['plugs/bootstrap/js/bootstrap3-typeahead.min'],
        'bootstrap.multiselect': ['plugs/bootstrap-multiselect/bootstrap-multiselect'],
        // distpicker
        'distpicker': ['plugs/distpicker/distpicker'],

        // nanoscroller
        'nanoscroller': ['plugs/nanoscroller/jquery.nanoscroller.min']
    },
    shim: {
        // open-source
        'websocket': {deps: [baseRoot + 'plugs/socket/swfobject.min.js']},
        // jquery
        'jquery.ztree': {deps: ['css!' + baseRoot + 'plugs/ztree/zTreeStyle/zTreeStyle.css']},
        // bootstrap
        'bootstrap.typeahead': {deps: ['bootstrap']},
        'bootstrap.multiselect': {deps: ['bootstrap', 'css!' + baseRoot + 'plugs/bootstrap-multiselect/bootstrap-multiselect.css']},
        'distpicker': {deps: [baseRoot + 'plugs/distpicker/distpicker.data.js']},
        'nanoscroller': {deps: ['css!' + baseRoot + 'plugs/nanoscroller/nanoscroller.css']}
    },
    deps: ['json', 'bootstrap'],
    // 开启debug模式，不缓存资源
    // urlArgs: "ver=" + (new Date()).getTime()
});

// 注册jquery到require模块
define('jquery', function () {
    return layui.$;
});