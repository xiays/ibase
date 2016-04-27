<?php
// +----------------------------------------------------------------------
// | Ioacrm [ Simple Efficient Excellent ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016 http://www.ioacrm.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: zhouxin <http://www.ioacrm.com>
// +----------------------------------------------------------------------
namespace Admin\Controller;
use Common\Controller\CommonController;
use Common\Resource;
use Common\Resource\AdminMenu;
use Common\Resource\AdminFreePage;
/**
 * 后台公共控制器
 * 为什么要继承AdminController？
 * 因为AdminController的初始化函数中读取了顶部导航栏和左侧的菜单，
 * 如果不继承的话，只能复制AdminController中的代码来读取导航栏和左侧的菜单。
 * 这样做会导致一个问题就是当AdminController被官方修改后AdminController不会同步更新，从而导致错误。
 * 所以综合考虑还是继承比较好。
 * @author zhouxin ioacrm
 */
class AdminController extends CommonController{
	protected $_currentmanager;
	protected $_currentgroup;
	protected $_currentauth;
	protected $_currentmenu;
    /**
     * 初始化方法
     * @author zhouxin ioacrm
     */
    protected function _initialize(){
    	
    	if (D("Manager")->isLogin()=='0')
    	{
    		if (strtolower(ACTION_NAME)!="login")
    			$this->redirect("admin/index/login");
    	}
    	//获取一级菜单
    	//dump(AdminMenu::tree());
    	//exit;
    	//获取菜单到Tree()    	
    	$current=AdminMenu::current();
    	$currentname=AdminMenu::currentname($current);    	
    	$this->assign('__CURRENT__', $current); //所有菜单
    	$this->assign('__CURRENTName__', $currentname); //所有菜单    	
    	$this->_currentmenu=$current;
    	//当前登录信息
    	//登录ID
    	//管理员信息
    	//权限信息    	
    	$this->_currentmanager=D("Manager")->logininfo();
    	$this->_currentgroup=D("ManagerGroup")->find($this->_currentmanager['group']);  
    	$this->_currentauth=$this->_currentgroup['menu_auth'];
    	$this->assign('__USERNAME__', $this->_currentmanager['username']);
    	
    	if(!$this->checkauth()){
    		$this->error('权限不足！');
    	}
    }
     
    /**
     * 权限是
     * @return boolean
     */
    protected function checkauth(){
    	//获取当前权限 
    	$gruop=$this->_currentgroup;
    	//判断地址白名单
    	if (AdminFreePage::check($gruop))
    		return true;
    	$menu_auth=','.$gruop['menu_auth'].',';
    	if ($gruop['id']==1)    		
    		return true;    	
    	if (strtolower(CONTROLLER_NAME)=="index" && strtolower(ACTION_NAME)=="index")
    	{ 
    		return true;
    	}
    	$info=$this->_currentmenu;    	
    	
    	//判断菜单权限ID     	
    	if (strpos($menu_auth, ','.(string)$info['id'].',')===false)
    	{
    		return false;
    	}    	
    	if ($info['sub']==null ||$info['sub']=='')
    		return true;
    	$url=  MODULE_NAME.'/'.CONTROLLER_NAME.'/'.ACTION_NAME;
    	$url=strtolower($url);
    	if (strtolower($info['url'])==$url)
    		return true;    	
    	$info=AdminMenu::getsub($info);    	
    	
    	foreach ($info['child'] as $child)
    	{
    		if (strtolower(ACTION_NAME)==strtolower($child['act']))	
    		{
    			if (strpos($menu_auth, ','.(string)$child['id'].',')!==false)    			
    				return true;
    		}
    	}    	
    	return false;
    }
    
    
       /**
        * 保存列表数据 ,表单数据名称已经固定不可更改. 
        * @param unknown $user_object
        * @param string $pkid
        */
    protected function saveDatalist($user_object, $pkid='id'){    		
    		$postdata = $_POST['postdata'];
    		$newpostdata = $_POST['newpostdata'];
    		$removeId = $_POST['removeId'];    		
    		foreach ( $postdata as $id => $code ) {
    			$code[$pkid]=$id;
    			$user_object->where([$pkid=>$id])->save($code);
    		}
    		foreach ( $newpostdata as $newCode ) {
                $user_object->add($newCode);
            }
            if ($removeId!=null &&  $removeId !='' ) {
            	
            	$user_object->where([$pkid=>['in',$removeId]])->delete();
            }
    }
    
    
    protected function getmap(&$map,$name){
    	if(I($name, '', 'string')!=''){
    		$map[$name] = I($name, '', 'string');
    	}
    }
    
    protected function checkpost($names){
    	$post=array();
    	foreach (explode(',', $names) as $key )
    	{
    		$post[$key]=$_POST[$key];
    	}
    	return $post;
    }
}
