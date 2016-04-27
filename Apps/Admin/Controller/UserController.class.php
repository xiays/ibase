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
 * 后台用户控制器
 * @author zhouxin ioacrm
 */
class UserController extends AdminController{
    /**
     * 用户列表
     * @author zhouxin ioacrm
     */
    public function index(){
        //搜索
        $keyword = I('keyword', '', 'string');
        $condition = array('like','%'.$keyword.'%');
        $map['id|username|email|mobile'] = array($condition, $condition, $condition, $condition,'_multi'=>true);

        //获取所有用户
        $map['status'] = array('egt', '0'); //禁用和正常状态
        $data_list = D('User')->page(!empty($_GET["p"])?$_GET["p"]:1, 2)->where($map)->order('sort desc,id desc')->select();
        $page = new \Common\Util\Page(D('User')->where($map)->count(), 2);

        //使用Builder快速建立列表页面。
        $builder = new \Common\Engines\ListEngines();
        $builder->setMetaTitle('用户列表') //设置页面标题
                ->addTopButton('addnew')  //添加新增按钮
                ->addTopButton('resume')  //添加启用按钮
                ->addTopButton('forbid')  //添加禁用按钮
                ->addTopButton('delete')  //添加删除按钮
                ->setSearch('请输入ID/用户名/邮箱/手机号', U('index'))
               	->addSearch('companyname', 'text', '渠道名称', '')
                ->addSearch('level', 'select', '渠道级别','')
                ->addSearch('objectstatus', 'select', '签约状态','' )
                ->addTableColumn('id', 'UID')
                ->addTableColumn('usertype', '类型')
                ->addTableColumn('username', '用户名')
                ->addTableColumn('email', '邮箱')
                ->addTableColumn('mobile', '手机号')
                ->addTableColumn('vip', 'VIP')
                ->addTableColumn('score', '积分')
                ->addTableColumn('money', '余额')
                ->addTableColumn('last_login_time', '最后登录时间时间', 'time')
                ->addTableColumn('reg_type', '注册方式')
                ->addTableColumn('sort', '排序', 'text')
                ->addTableColumn('status', '状态', 'status')
                ->addTableColumn('right_button', '操作', 'btn')
                ->setTableDataList($data_list) //数据列表
                ->setTableDataPage($page->show()) //数据列表分页
                ->addRightButton('edit')   //添加编辑按钮
                ->addRightButton('forbid') //添加禁用/启用按钮
                ->addRightButton('delete') //添加删除按钮
                ->display();
    }

    /**
     * 新增用户
     * @author zhouxin ioacrm
     */
    public function add(){
        if(IS_POST){
            $user_object = D('User');
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
            $user_object = D('User');    
            $info=array(['reg_type'=>1]);
            //使用FormBuilder快速建立表单页面。
            $builder = new \Common\Engines\FormEngines();
            $this->binditem($builder,$info);
            $builder->setMetaTitle('新增用户') //设置页面标题+                  
                    ->setPostUrl(U('add')) //设置表单提交地址                                     
                    ->setFormData(array('reg_type' => 1)) //注册方式为后台添加
                    ->display();
        }
    }

    /**
     * 编辑用户
     * @author zhouxin ioacrm
     */
    public function edit($id){
    	
        //获取用户信息
        $info = D('User')->find($id);

        if(IS_POST){
            $user_object = D('User');
            //不修改密码时销毁变量
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
            $result=$user_object->save($_POST);
            if ($result>0 || $result ===0) {      
                $this->success('更新成功', U('index'));
            }else{
                $this->error('更新失败', $user_object->getError());
            }
        }else{
            $user_object = D('User');
            $info = $user_object->find($id);
            //使用FormBuilder快速建立表单页面。
            $builder = new \Common\Engines\FormEngines();
            $this->binditem($builder,$info,0);
            $builder->setMetaTitle('编辑用户') //设置页面标题
                    ->setPostUrl(U('edit')) //设置表单提交地址
                    ->addFormItem('id', 'hidden', 'ID', 'ID') 
                    ->setFormData($info)
                    ->display();
        }
    }
    
    /**
     * 表单项目
     * @author zhouxin
     */
    public function binditem($builder,$info,$create=1){
    	$user_object = D('User');
    	$builder->addFormItem('username', 'text', '用户名', '')
    	->addFormItem('password', 'password', '用户密码', '')
    	->addFormItem('reg_type', 'hidden', '注册方式', '注册方式')
    	->addFormItem('usertype', 'radio', '用户类型', '用户类型', $user_object->user_type())
    	->addFormItem('email', 'text', '用户邮箱', '')
    	->addFormItem('mobile', 'text', '手机号', '')
    	->addFormItem('dept_id', 'select', '部门', '',select_list_as_tree('Department'))    	
    	->addFormItem('group', 'select', '权限', '',select_list_as_tree('UserGroup'))
    	->addFormItem('positionid', 'select', '职位', '',select_list_as_array(D('Position')->select(),'id','name'))
    	->addFormItem('avatar', 'picture', '用户头像', '用户头像')
    	->addFormItem('realname', 'text', '真实姓名', '真实姓名')
    	->addFormItem('extend', 'textarea', '用户信息扩展', '用户信息扩展')
    	->addFormItem('status', 'radio', '状态', '状态',$user_object->state())
    	->setFormData($info);
    	 
    }
}
