<?php
// +----------------------------------------------------------------------
// | Ioacrm [ Simple Efficient Excellent ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016 http://www.ioacrm.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: zhouxin <http://www.ioacrm.com>
// +----------------------------------------------------------------------
namespace Manage\Controller;
use Common\Controller\CommonController;
use Common\Resource\ManageMenu;
use Common\Resource\ManageFreePage;
/**
 * 后台公共控制器
 * 为什么要继承AdminController？
 * 因为AdminController的初始化函数中读取了顶部导航栏和左侧的菜单，
 * 如果不继承的话，只能复制AdminController中的代码来读取导航栏和左侧的菜单。
 * 这样做会导致一个问题就是当AdminController被官方修改后AdminController不会同步更新，从而导致错误。
 * 所以综合考虑还是继承比较好。
 * @author zhouxin 
 */
class ManageController extends CommonController{
	protected  $loginuser;
	protected  $uid;
    /**
     * 初始化方法
     * @author zhouxin 
     */
    protected function _initialize(){
    	
    	if (D("User")->isLogin()=='0')
    	{    		
    		if (strtolower(ACTION_NAME)!="login")
    			$this->redirect("manage/index/login");
    	}    	
    	
    	//获取菜单到Tree()    	
    	$current=ManageMenu::current();
    	$currentname=ManageMenu::currentname($current);    	
    	$this->assign('__CURRENT__', $current); //所有菜单
    	$this->assign('__CURRENTName__', $currentname); //所有菜单    	
    	$this->_currentmenu=$current;
    	
    	//当前登录信息 
    	$this->loginuser=D("User")->logininfo();
    	$this->uid=$this->loginuser['uid'];
    	$this->_currentgroup=D("UserGroup")->find($this->loginuser['group']);  
    	$this->_currentauth=$this->_currentgroup['menu_auth'];
    	$this->assign('__USERNAME__', $this->loginuser['username']);
    	$this->assign('__USERINFO__', $this->loginuser);
    	
    	//获取一级菜单
    	//$first=ManageMenu::parent(0);
    	$tree=ManageMenu::tree();
    	$tree2=ManageMenu::tree2($current);
    	//当前用户权限
    	//$first=ManageMenu::getmenu($this->_currentgroup, $first);
    	//$tree=ManageMenu::getmenu($this->_currentgroup, $tree);
    	//$this->assign('__FIRST_MENU__', $first); //一级菜单
    	$this->assign('__TREE_MENU__', $tree); //所有菜单
    	$this->assign('__TREE_MENU2__', $tree2); //所有菜单
    	
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
    	if (ManageFreePage::check($gruop))
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
    	$info=ManageMenu::getsub($info);
    	 
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
}
