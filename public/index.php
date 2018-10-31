<?php

// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
// [ 应用入口文件 ]
// 定义应用目录
define('APP_PATH', __DIR__ . '/../application/');

//http协议
define('HTTP', ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https')) ? 'https://' : 'http://');
//当前域名
define('ROOT_URL', HTTP . $_SERVER['SERVER_NAME']);

//跨域处理
if ( isset( $_SERVER['HTTP_ORIGIN'] ) ) {
    header( 'Access-Control-Allow-Origin: ' . $_SERVER['HTTP_ORIGIN'] );
}
header( 'Access-Control-Allow-Headers: Origin, Accept, Content-Type, Authorization, ISCORS' );
header( 'Access-Control-Allow-Credentials: true' );
header( 'Access-Control-Allow-Methods: POST, GET, PUT, OPTIONS, DELETE' );

// 加载框架引导文件
require __DIR__ . '/../thinkphp/start.php';
