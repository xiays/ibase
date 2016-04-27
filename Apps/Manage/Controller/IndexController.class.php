<?php
// +----------------------------------------------------------------------
// | Ioacrm [ Simple Efficient Excellent ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016 http://www.ioacrm.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: zhouxin <http://www.ioacrm.com>
// +----------------------------------------------------------------------
namespace Manage\Controller;
use Think\Controller;
use Common\Resource\ManageMenu;
/**
 * 后台默认控制器
 * @author zhouxin 
 */
class IndexController extends ManageController{
    /**
     * 默认方法
     * @author zhouxin 
     */
    public function index(){
        //计算统计图日期       
        
        $this->assign('meta_title', "首页");
        $info=D('UserQuickmenu')->where(['uid'=>$this->loginuser['uid']])->find();
        if ($info==null)
        {        	
        	D('UserQuickmenu')->add(['uid'=>$this->loginuser['uid'],'items'=>'1,2,3,4,5,6,7,8']);
        	$info['items']='1,2,3,4,5,6,7,8';
        }        
        $items=explode(',', $info['items']);     
        $allquick= ManageMenu::quick();
        $quick=array();
        foreach ($items as $item)
        {
        	$info=ManageMenu::getquickbyid($item);
        	if ($info!=null)
        	{
        		$quick[]=$info;
        	}        	
        }
        $unquick = array();        
        foreach ($allquick as  $value) {
        	        	
        	if(!in_array($value,$quick)){
        		$unquick[]=$value;
        	}
        }     
        $buquick = array();
        for($i=count($quick);$i<8;$i++)
        {
        	$buquick[]="0";
        }
        $notice=D('Notice')->limit('6')->select();
        //$quick= array_slice(ManageMenu::quick(),0,8);        
        //$unquick= array_slice(ManageMenu::quick(),8);
        $this->assign('meta_title', "首页");
        $this->assign('__quick__', $quick);        
        $this->assign('__allquick__', $allquick);
        $this->assign('__unquick__', $unquick);
        $this->assign('__buquick__', $buquick);
        $this->assign('noticelist', $notice);
        $this->display('');
    }
    public function savemenu(){
    	//dump(implode(',',$_POST['mod']));
    	$info=D('UserQuickmenu')->where(['uid'=>$this->loginuser['uid']])->find();
    	$info['items']=implode(',',$_POST['mod']);
    	D('UserQuickmenu')->save($info);
    	echo '{"isSuccess":true}';
    	exit;
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
    		$user_object = D('User');
    		$user=D('User')->where(['username'=>$username])->find();
    		$uid = $user_object->login($username, $password, null);
    		if(0 < $uid){
    			$this->success('登录成功！', U('Manage/Index/index'));
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
    	session('user_auth', null);
    	session('user_auth_sign', null);
    	$this->success('退出成功！', U('Manage/index/login'));
    }
}
