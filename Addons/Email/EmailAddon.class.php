<?php
// +----------------------------------------------------------------------
// | Ioacrm [ Simple Efficient Excellent ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016 http://www.ioacrm.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: zhouxin <http://www.ioacrm.com>
// +----------------------------------------------------------------------
namespace Addons\Email;
use Common\Controller\Addon;
/**
 * 邮件插件
 * @author zhouxin 
 */
class EmailAddon extends Addon{
    /**
     * 插件信息
     * @author zhouxin 
     */
    public $info = array(
        'name' => 'Email',
        'title' => '邮件插件',
        'description' => '实现系统发邮件功能',
        'status' => 1,
        'author' => 'CoreThink',
        'version' => '1.0'
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
}
