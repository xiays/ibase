<?php
// +----------------------------------------------------------------------
// | Ioacrm [ Simple Efficient Excellent ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016 http://www.ioacrm.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: zhouxin <http://www.ioacrm.com>
// +----------------------------------------------------------------------

namespace Manage\Controller;
/**
 * 后台控制器
 * @author zhouxin
 */
class UserController extends ManageController{
   
    
	public function index(){
		$this->redirect('edit');		 
	}
	
    /**
     * 列表
     * @author zhouxin
     */
    public function select(){
    	//搜索
    	//搜索数据
    	$map=array();
    	$data_list = D('User')->page(!empty($_GET["p"])?$_GET["p"]:1, 8)->where($map)->order('id asc')->select();
    	$page = new \Common\Util\Page(D('CrmClient')->where($map)->count(), 8);
    	//使用Builder快速建立列表页面。
    	$builder = new \Common\Engines\ListEngines();
    	$builder->setMetaTitle('客户列表') //设置页面标题
    	->setTemplate('_Engines/listpop')
    	->setSearch('请输入用户名称', '')
    	->setCheck(false)
    	->addTableColumn('username', '登录名称','hidden')
    	->addTableColumn('realname', '员工名称')
    	->setTableDataListKey('id')
    	->addTableColumn('right_button', '操作', 'btn')
    	->setTableDataList($data_list) //数据列表
    	->setTableDataPage($page->show()) //数据列表分页
    	->addRightButton('self',['title'=>'选择','href'=>'javascript:save(__data_id__,\'username\')']) //添加删除按钮
    	->display();
    }
    
    /**
     * 编辑
     * @author zhouxin
     */
    public function edit(){
        //获取用户信息    
        if(IS_POST){            		
        	$_POST['id']=$this->loginuser['uid'];        	
        	$user_object = D('User');        	
            $result=$user_object->save($_POST);
            if ($result>0 || $result ===0) {
            	$user=$user_object->find($_POST['id']);
            	//更新用户头像及名称
            	D('User')->autoLogin($user);
                $this->success('更新成功', U('edit'));
            }else{
                $this->error('更新失败', $user_object->getError());
            }
        }else{
            $user_object = D('User');
            $info = $user_object->find($this->loginuser['uid']);
            //使用FormBuilder快速建立表单页面。
            $builder = new \Common\Engines\FormEngines();
            $this->binditem($builder,$info,0);
            $builder->setMetaTitle('个人资料') //设置页面标题
                    ->setPostUrl(U('edit')) //设置表单提交地址
                    ->addFormItem('id','hidden','','')
                    ->display();
        }
    }
    
    
    /**
     * 编辑
     * @author zhouxin
     */
    public function pwd(){
    	//获取用户信息
    	if(IS_POST){    
    		$_POST['id']=$this->loginuser['uid'];    	
    		if ($_POST['password']!=$_POST['repassword'])
    		{
    			$this->error('两次输入的密码不一致');
    			exit;
    		}
    		if($_POST['password'] == ''){
                unset($_POST['password']);
                $this->error('密码不能为空');
                exit;
            }else{
               	$login_salt = rand(11111, 99999);
	            $password= $_POST['password'];
	            $password=md5(md5($password).$login_salt);
	            $_POST['login_salt']=$login_salt;
	            $_POST['password']=$password; 
            }
            
    		$user_object = D('User');
    		$result=$user_object->save($_POST);
    		if ($result>0 || $result ===0) {
    			$user=$user_object->find($_POST['id']);
    			//更新用户头像及名称
    			D('User')->autoLogin($user);
    			$this->success('更新成功', U('pwd'));
    		}else{
    			$this->error('更新失败', $user_object->getError());
    		}
    	}else{
    		$user_object = D('User');
    		$info = $user_object->find($this->loginuser['uid']);
    		$info['password']='';
    		//使用FormBuilder快速建立表单页面。
    		$builder = new \Common\Engines\FormEngines();
    		$builder->addFormItem('password', 'password', '密码', '')
    				->addFormItem('repassword', 'password', '重复密码', '')
    				->setFormData($info);
    		$builder->setMetaTitle('修改密码') //设置页面标题
    		->setPostUrl(U('pwd')) //设置表单提交地址
    		->addFormItem('id','hidden','','')
    		->display();
    	}
    }
    
    /**
     * 表单项目
     * @author zhouxin
     */
     public function binditem($builder,$info,$create=1){     		
     		$builder->addFormItem('username', 'label', '用户名称', '')
     				->addFormItem('usertype', 'radio', '用户类型', '',D('User')->user_type())                    
                    ->addFormItem('email', 'text', '用户邮箱', '')
                    ->addFormItem('dept_id', 'text', '部门ID', '')
                    ->addFormItem('mobile', 'text', '手机号', '')
                    ->addFormItem('avatar', 'picture', '用户头像', '')
                    ->addFormItem('sex', 'radio', '用户性别', '',D('User')->user_sex())
                    ->addFormItem('age', 'slider', '年龄', '')
                    ->addFormItem('birthday', 'date', '生日', '')                    
                    ->addFormItem('realname', 'text', '真实姓名', '')
                    ->addFormItem('idcard_no', 'text', '身份证号码', '')                    
                    ->addFormItem('reg_type', 'text', '注册方式', '')                                        
                    ->setFormData($info);
     		
     }  
    
    
    public function view(){
    	$user_object = D('User');
    	$info = $user_object->find($this->loginuser['uid']);
    	$builder = new \Common\Engines\FormEngines();
    	$builder->setMetaTitle('查看资料') //设置页面标题
    	->setTemplate('_Engines/view')    	
    	->setPostUrl(U('edit')) //设置表单提交地址    	
    	->addFormItemStyle('username', 'viewlabel', '用户名称', '',['rowend'=>0])
    	->addFormItemSimple('usertype', 'viewlabel', '用户类型', '',D('User')->user_type(),['rowbegin'=>0,'rowend'=>0])
    	->addFormItemStyle('email', 'viewlabel', '用户邮箱', '',['rowbegin'=>0])
    	->addFormItemStyle('dept_id', 'viewlabel', '部门ID', '',['rowend'=>0,'width'=>6])
    	->addFormItemStyle('mobile', 'viewlabel', '手机号', '',['rowbegin'=>0,'width'=>6])
    	->addFormItemStyle('avatar', 'viewpicture', '用户头像', '')
    	->addFormItemStyle('sex', 'viewlabel', '用户性别', '',D('User')->user_sex())
    	->addFormItemStyle('age', 'viewlabel', '年龄', '')
    	->addFormItemStyle('birthday', 'viewlabel', '生日', '')
    	->addFormItemStyle('realname', 'viewlabel', '真实姓名', '')
    	->addFormItemStyle('idcard_no', 'viewlabel', '身份证号码', '')
    	->addFormItemStyle('reg_type', 'viewlabel', '注册方式', '')
    	->setFormData($info)
    	->display();
    }
}