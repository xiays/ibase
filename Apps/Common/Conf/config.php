<?php
// +----------------------------------------------------------------------
// | Ibase [ Simple Efficient Excellent ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016 http://www.ioacrm.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: zhouxin <xiays@163.com> <http://www.ioacrm.com>
// +----------------------------------------------------------------------

/**
 * Ibase全局配置文件
 */
const THINK_ADDON_PATH = '../Addons/';
$_config = array (
    /**
     * 产品配置
     * 系统升级需要此配置
     * 根据CoreThink用户协议：
     * 免费版您可以免费用于项目开发
     * 但不允许更改本产品后台的版权信息，请您尊重我们的劳动成果及知识产权，违者追究法律责任。
     * 为了您更加方便使用本系统，后台特别设置了当前项目开发团队名称：DEVELOP_TEAM允许您自由更改来展示您的信息
     * 商业授权版可更改所有的产品名称及公司名称，授权联系：admin@ioacrm.cn
     */

    'PRODUCT_NAME'    => 'Ibase',                  //产品名称
    'CURRENT_VERSION' => '1.0.0',                      //当前版本
    'WEBSITE_DOMAIN'  => 'http://www.ioacrm.com',    //官方网址
    'UPDATE_URL'      => '/appstore/home/core/update', //官方更新网址
    'COMPANY_NAME'    => '北京欧悦家健康管理有限公司',   //公司名称
    'DEVELOP_TEAM'    => '北京欧悦家健康管理有限公司',   //当前项目开发团队名称［未授权版仅允许修改此项］

    //产品简介
    'PRODUCT_INFO'    => '',

    //公司简介
    'COMPANY_INFO'    => '',

    //系统主页地址配置
    'HOME_PAGE'       => 'http://'.$_SERVER['HTTP_HOST'].__ROOT__,

    //URL模式
    'URL_MODEL' => '2',

    //全局过滤配置
    'DEFAULT_FILTER' => '', //TP默认为htmlspecialchars

    //预先加载的标签库
    //'TAGLIB_PRE_LOAD' => 'Home\\TagLib\\Corethink',

    //URL配置
    'URL_CASE_INSENSITIVE' => true, //不区分大小写

    //应用配置
    'DEFAULT_MODULE'     => 'Home',
	'DEFAULT_CONTROLLER'=>'index',
    'MODULE_DENY_LIST'   => array('Common'),
    'MODULE_ALLOW_LIST'  => array('Home','Admin','Manage','Install'),
    'AUTOLOAD_NAMESPACE' => array('Addons' => THINK_ADDON_PATH), //扩展模块列表

    //模板相关配置
    'TMPL_PARSE_STRING'  => array (
        '__PUBLIC__'     => __ROOT__.'/Public',
        '__ADMIN_IMG__'  => __ROOT__.'/Public/admin/_Resource/img',
        '__ADMIN_CSS__'  => __ROOT__.'/Public/admin/_Resource/css',
        '__ADMIN_JS__'   => __ROOT__.'/Public/admin/_Resource/js',
        '__ADMIN_LIBS__' => __ROOT__.'/Public/admin/_Resource/libs',
        '__HOME_IMG__'   => __ROOT__.'/Public/home/_Resource/img',
        '__HOME_CSS__'   => __ROOT__.'/Public/home/_Resource/css',
        '__HOME_JS__'    => __ROOT__.'/Public/home/_Resource/js',
        '__HOME_LIBS__'  => __ROOT__.'/Public/home/_Resource/libs',
    	'__UPLOAD_IMG__'  => __ROOT__.'/Uploads',
    ),

    //文件上传默认驱动
    'UPLOAD_DRIVER' => 'Local',

    //文件上传相关配置
    'UPLOAD_CONFIG' => array(
        'mimes'    => '', //允许上传的文件MiMe类型
        'maxSize'  => 2*1024*1024, //上传的文件大小限制 (0-不做限制，默认为2M，后台配置会覆盖此值)
        'autoSub'  => true, //自动子目录保存文件
        'subName'  => array('date', 'Y-m-d'), //子目录创建方式，[0]-函数名，[1]-参数，多个参数使用数组
        'rootPath' => './Uploads/', //保存根路径
        'savePath' => '', //保存路径
        'saveName' => array('uniqid', ''), //上传文件命名规则，[0]-函数名，[1]-参数，多个参数使用数组
        'saveExt'  => '', //文件保存后缀，空则使用原后缀
        'replace'  => false, //存在同名是否覆盖
        'hash'     => true, //是否生成hash编码
        'callback' => false, //检测文件是否存在回调函数，如果存在返回文件信息数组
    ),
);

//返回合并的配置
return array_merge (
    $_config, //系统全局默认配置
    include APP_PATH.'/Common/Conf/db.php', //包含数据库连接配置
    include APP_PATH.'/Common/Engines/config.php' //包含Builder配置
);
