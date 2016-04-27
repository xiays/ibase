<?php
// +----------------------------------------------------------------------
// | ibase [ Simple Efficient Excellent ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016 http://www.ioacrm.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: zhouxin <xiays@163.com> <http://www.ioacrm.com>
// +----------------------------------------------------------------------


/**
 * PHP版本检查
 */
if(version_compare(PHP_VERSION,'5.3.0','<')) die('require PHP > 5.3.0 !');

/**
 * PHP报错设置
 */
error_reporting(E_ALL^E_NOTICE^E_WARNING);

/**
 * 开发模式环境变量前缀
 */
define('ENV_PRE', 'CT_');

/**
 * 应用目录设置
 * 安全期间，建议安装调试完成后移动到非WEB目录
 */
define('APP_PATH', dirname(__DIR__).'/Apps/');

/**
 * 缓存目录设置
 * 此目录必须可写，建议移动到非WEB目录
 */
define('RUNTIME_PATH', dirname(__DIR__).'/Runtime/');

/**
 * 静态缓存目录设置
 * 此目录必须可写，建议移动到非WEB目录
 */
define('HTML_PATH', RUNTIME_PATH.'Html/');

/**
 * 系统安装及开发模式检测
 */
define('APP_DEBUG', $_SERVER[ENV_PRE.'APP_DEBUG']? : true); //系统调试设置, 项目正式部署后请设置为false

/**
 * 引入核心入口
 * ThinkPHP亦可移动到WEB以外的目录
 */
require '../ThinkPHP/ThinkPHP.php';
