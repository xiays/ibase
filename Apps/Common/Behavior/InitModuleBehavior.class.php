<?php
// +----------------------------------------------------------------------
// | Ioacrm [ Simple Efficient Excellent ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016 http://www.ioacrm.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: zhouxin <http://www.ioacrm.com>
// +----------------------------------------------------------------------
namespace Common\Behavior;
use Think\Behavior;
defined('THINK_PATH') or exit();
/**
 * 初始化允许访问模块信息
 * @author zhouxin 
 */
class InitModuleBehavior extends Behavior{
    /**
     * 行为扩展的执行入口必须是run
     * @author zhouxin 
     */
    public function run(&$content){
        //安装模式下直接返回
        //if(defined('BIND_MODULE') && BIND_MODULE === 'Install') return;

        //允许访问模块列表加上安装的功能模块
        //$module_name_list = D('SystemModule')->where(array('status' => 1))->getField('name', true);
        //$module_allow_list = array_merge(C('MODULE_ALLOW_LIST'), $module_name_list);
        //C('MODULE_ALLOW_LIST', $module_allow_list);

        //URL_MODEL必须在app_init阶段就从数据库读取出来应用，不然系统就会读取config.php中的配置导致后台的配置失效
        $config['URL_MODEL'] = D('SystemConfig')->getFieldByName('URL_MODEL', 'value');
        
        C($config);
    }
}
