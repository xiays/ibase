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
 * 后台控制器
 * @author zhouxin
 */
class ManagerController extends AdminController{
    /**
     * 列表
     * @author zhouxin
     */
    public function index(){
        //搜索
                //搜索数据		
        $data_list = D('Manager')->page(!empty($_GET["p"])?$_GET["p"]:1, C('ADMIN_PAGE_ROWS'))->where($map)->order('id asc')->select();
        $page = new \Common\Util\Page(D('Manager')->where($map)->count(), C('ADMIN_PAGE_ROWS'));
        
        //使用Builder快速建立列表页面。
        $builder = new \Common\Engines\ListEngines();
        $builder->setMetaTitle('列表') //设置页面标题
                ->addTopButton('addnew')  //添加新增按钮  
                ->addTableColumn('username', '用户名或昵称')
                ->addTableColumn('mobile', '手机号')                
                ->setTableDataListKey('id')      
                ->addTableColumn('right_button', '操作', 'btn')
                ->setTableDataList($data_list) //数据列表
                ->setTableDataPage($page->show()) //数据列表分页
                ->addRightButton('edit')   //添加编辑按钮                
                ->addRightButton('delete') //添加删除按钮                
                ->display();
    }

    /**
     * 新增
     * @author zhouxin
     */
    public function add(){
        if(IS_POST){
            $user_object = D('Manager');
            $login_salt = rand(11111, 99999);
            $password= $_POST['password'];
            $password=md5(md5($password).$login_salt);
            $_POST['login_salt']=$login_salt;
            $_POST['password']=$password;
            $data = $user_object->create();
            if($data){
                $id = $user_object->add();
                if($id){
                    $this->success('新增成功', U('index'));
                }else{
                    $this->error('新增失败');
                }
            }else{
                $this->error($user_object->getError());
            }
        }else{            
            $info=array(['status'=>1]);
            //使用FormBuilder快速建立表单页面。            
            $builder = new \Common\Engines\FormEngines();
            $this->binditem($builder,$info);
            $builder->setMetaTitle('新增') //设置页面标题
                    ->setPostUrl(U('add')) //设置表单提交地址                    
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
    		$_POST['id']=$this->_currentmanager['uid'];
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
    
    		$user_object = D('Manager');
    		$result=$user_object->save($_POST);
    		if ($result>0 || $result ===0) {
    			$user=$user_object->find($_POST['id']);
    			//更新用户头像及名称
    			D('Manager')->autoLogin($user);
    			$this->success('更新成功', U('index'));
    		}else{
    			$this->error('更新失败', $user_object->getError());
    		}
    	}else{
    		$user_object = D('Manager');
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
     * 编辑
     * @author zhouxin
     */
    public function edit($id){
        //获取用户信息    
        if(IS_POST){
            $user_object = D('Manager');  
            $info = D('Manager')->find($id);
            if($_POST['password'] == '' || $info['password'] == $_POST['password']){
            	unset($_POST['password']);
            }else{
            	$login_salt = rand(11111, 99999);
	            $password= $_POST['password'];
	            $password=md5(md5($password).$login_salt);
	            $_POST['login_salt']=$login_salt;
	            $_POST['password']=$password; 
            }
            //不允许更改超级管理员用户组
            if($_POST['id'] == 1){
            	unset($_POST['group']);
            }            
            $result=$user_object->save($_POST);
            if ($result>0 || $result ===0) {            
                $this->success('更新成功', U('index'));
            }else{
                $this->error('更新失败', $user_object->getError());
            }
        }else{
            $user_object = D('Manager');
            $info = $user_object->find($id);
            //使用FormBuilder快速建立表单页面。
            $builder = new \Common\Engines\FormEngines();
            $this->binditem($builder,$info,0);
            $builder->setMetaTitle('编辑') //设置页面标题
                    ->setPostUrl(U('edit')) //设置表单提交地址
                    ->addFormItem('id','hidden','','')
                    ->display();
        }
    }
    /**
     * 表单项目
     * @author zhouxin
     */
     public function binditem($builder,$info,$create=1){   
     		$user_object = D('Manager');
            $builder->addFormItem('usertype', 'radio', '用户类型', '',$user_object->user_type())
                    ->addFormItem('username', 'text', '用户名', '')
                    ->addFormItem('password', 'password', '用户密码', '')
                    ->addFormItem('email', 'text', '用户邮箱', '')
                    ->addFormItem('mobile', 'text', '手机号', '')
                    ->addFormItem('group', 'select', '权限', '',select_list_as_tree('ManagerGroup'))
                    ->addFormItem('avatar', 'picture', '用户头像', '用户头像')
                    ->addFormItem('realname', 'text', '真实姓名', '真实姓名')
                    ->addFormItem('extend', 'textarea', '用户信息扩展', '用户信息扩展')
                    ->addFormItem('status', 'radio', '状态', '状态',$user_object->state())                    
                    ->setFormData($info);
                   
     }   
}