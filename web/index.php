<?php
// +----------------------------------------------------------------------
// | YihaoCMS [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://diao.info All rights reserved.
// +----------------------------------------------------------------------
// | Author: molong <molong@tensent.cn> <http://diao.info>
// +----------------------------------------------------------------------

// [ 应用入口文件 ]
if(version_compare(PHP_VERSION,'7.0.0','<'))  die('require PHP > 7.0.0 !');


// 定义应用目录
define('APP_PATH', __DIR__ . '/../application/');
define('BASE_PATH', substr($_SERVER['SCRIPT_NAME'], 0, -10));

/**
 * 缓存目录设置
 * 此目录必须可写，建议移动到非WEB目录
 */
define ( 'RUNTIME_PATH', __DIR__ . '/../data/' );

// 加载框架引导文件
require __DIR__ . '/../framework/start.php';
