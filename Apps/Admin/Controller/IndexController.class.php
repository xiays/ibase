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
use Common\Resource;
use Common\Resource\AdminMenu;
/**
 * 后台默认控制器
 * @author zhouxin ioacrm
 */
class IndexController extends AdminController{
    /**
     * 默认方法
     * @author zhouxin ioacrm
     */
    public function index(){
        //计算统计图日期         
    	$first=AdminMenu::parent(0);
    	$tree=AdminMenu::tree();  
    	$first=AdminMenu::getmenu($this->_currentgroup, $first);
    	$tree=AdminMenu::getmenu($this->_currentgroup, $tree);
    	$this->assign('__FIRST_MENU__', $first); //一级菜单
    	$this->assign('__TREE_MENU__', $tree); //所有菜单    	
        $this->assign('meta_title', "首页");
        $this->display('');
    }

    /**
     * 完全删除指定文件目录
     * @author zhouxin ioacrm
     */
    public function rmdirr($dirname = RUNTIME_PATH){
        $file = new \Common\Util\File();
        $result = $file->del_dir($dirname);
        if($result){
            $this->success("缓存清理成功");
        }else{
            $this->error("缓存清理失败");
        }
    }
    
    /**
     * 默认方法
     * @author zhouxin ioacrm
     */
    public function login(){
     if(IS_POST){
            $username = I('username');
            $password = I('password');           
            //$map['group'] = array('egt', 1); //后台部门
            $user_object = D('Manager');
            $user=D('Manager')->where(['username'=>$username])->find();           
            $uid = $user_object->login($username, $password, null);
            if(0 < $uid){
                $this->success('登录成功！', U('Admin/Index/index'));
            }else{
                $this->error($user_object->getError());
            }
            exit;
        }else{
            $this->assign('meta_title', '用户登录');
            $this->assign('__CONTROLLER_NAME__', strtolower(CONTROLLER_NAME)); //当前控制器名称
            $this->assign('__ACTION_NAME__', strtolower(ACTION_NAME)); //当前方法名称
            $this->display();
        }
    }
    
    /**
     * 默认方法
     * @author zhouxin ioacrm
     */
    public function logout(){    	
      	session('manager_auth', null);
        session('manager_auth_sign', null);
        $this->success('退出成功！', U('admin/index/login'));
    }
    
}
