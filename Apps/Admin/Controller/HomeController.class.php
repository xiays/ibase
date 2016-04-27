<?php
// +----------------------------------------------------------------------
// | Ioacrm [ Simple Efficient Excellent ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016 http://www.ioacrm.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: zhouxin <http://www.ioacrm.com>
// +----------------------------------------------------------------------
namespace Admin\Controller;
use Think\Controller;
/**
 * 后台默认控制器
 * @author zhouxin ioacrm
 */
class HomeController extends AdminController{
    /**
     * 默认方法
     * @author zhouxin ioacrm
     */
    public function index(){
        //计算统计图日期         
        $this->assign('meta_title', "首页");
        $this->display('Home/index');
    }
   
}
