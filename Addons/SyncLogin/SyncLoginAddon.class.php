<?php
// +----------------------------------------------------------------------
// | Ioacrm [ Simple Efficient Excellent ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016 http://www.ioacrm.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: zhouxin <http://www.ioacrm.com>
// +----------------------------------------------------------------------
namespace Addons\SyncLogin;
use Common\Controller\Addon;
/**
 * 同步登陆插件
 * @author zhouxin 
 */
class SyncLoginAddon extends Addon{
    /**
     * 插件信息
     * @author zhouxin 
     */
    public $info = array(
        'name' => 'SyncLogin',
        'title' => '第三方账号登陆',
        'description' => '第三方账号登陆',
        'status' => 1,
        'author' => 'CoreThink',
        'version' => '0.1'
    );

    /**
     * 自定义插件后台
     * @author zhouxin 
     */
    //public $custom_adminlist = './Addons/SyncLogin/admin.html';

    /**
     * 插件后台数据表配置
     * @author zhouxin 
     */
    public $admin_list = array(
        '1' => array(
            'title' => '第三方登录列表',
            'model' => 'sync_login',
        )
    );

    /**
     * 插件安装方法
     * @author zhouxin 
     */
    public function install(){
        return true;
    }

    /**
     * 插件卸载方法
     * @author zhouxin 
     */
    public function uninstall(){
        return true;
    }

    /**
     * 登录按钮钩子
     * @author zhouxin 
     */
    public function SyncLogin($param){
        $this->assign($param);
        $config = $this->getConfig();
        $this->assign('config',$config);
        $this->display('login');
    }

    /**
     * meta代码钩子
     * @author zhouxin 
     */
    public function PageHeader($param){
        $platform_options = $this->getConfig();
        echo $platform_options['meta'];
    }
}
