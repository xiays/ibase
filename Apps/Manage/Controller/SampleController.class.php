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
/**
 * 后台用户控制器
 * @author zhouxin 
 */
class SampleController extends ManageController{
    /**
     * 用户列表
     * @author zhouxin 
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
        $group=1;
        $tab_list[1]['title'] = '待处理';
        $tab_list[1]['href']  = U('my', array('group' => 1));
        $tab_list[2]['title'] = '全部';
        $tab_list[2]['href']  = U('my', array('group' => 2));
        $builder = new \Common\Engines\ListEngines();
        $builder->setMetaTitle('用户列表') //设置页面标题                
                ->addTopButton('addnew')  //添加新增按钮
                ->addTopButton('addnew',['href'=>U('adddata'),'title'=>'测试添加列表'])  //添加启用按钮                
                ->addTopButton('delete')  //添加删除按钮
                ->setTabNav($tab_list, $group) //设置页面Tab导航
                ->setSearch('请输入ID/用户名/邮箱/手机号', U('index'))
               	->addSearch('companyname', 'text', '渠道名称', '')
                ->addSearch('level', 'select', '渠道级别','',D('User')->user_vip_level())
                ->addSearch('objectstatus', 'select', '签约状态','' )
                ->addTableColumn('id', 'UID')
                ->addTableColumn('usertype', '类型')
                ->addTableColumn('username', '用户名')
                ->addTableColumn('email', '邮箱')
                ->addTableColumn('mobile', '手机号')                
                ->addTableColumn('last_login_time', '最后登录时间时间', 'time')               
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
     * @author zhouxin 
     */
    public function add(){
    	
        if(IS_POST){
        	$_POST['realname']=implode($_POST['realname'],',');
            $user_object = D('User');
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
            $data_list=D('Message')->select();
            //使用FormBuilder快速建立表单页面。
            $builder = new \Common\Engines\FormEngines();
            $info=array();
            $this->binditem1($builder,$info);
            $builder->setMetaTitle('新增用户') //设置页面标题+
                    ->setPostUrl(U('add')) //设置表单提交地址
                    ->setTipHtml("aaa5555")//设置表单提交地址                    
            		->display();
        }
    }
    
    public function binditem1($builder,$info,$create=1){
    	$user_object = D('User');
    	$builder->addFormItemStyle('reg_type', 'hidden', '注册方式', '注册方式')
    	//三列示列
    	->addFormItemSimple('usertype', 'radio', '用户类型', '用户类型', $user_object->user_type(),['rowend'=>0])
    	->addFormItemStyle('username', 'text', '用户名', '用户名',['rowbegin'=>0,'rowend'=>0])
    	->addFormItemStyle('email', 'text', '邮箱', '邮箱',['rowbegin'=>0])
    	//二列示列
    	->addFormItemStyle('mobile', 'text', '手机号码', '手机号码',['rowend'=>0,'width'=>8])
    	->addFormItemStyle('password', 'password', '密码', '密码',['rowbegin'=>0])
    	//二列平衡示列
    	->addFormItemStyle('bbb','date', '日期选择','手机号码',['rowend'=>0,'width'=>6])
    	->addFormItemStyle('bbb1','time', '时间选择','',['rowbegin'=>0,'width'=>6])
    	//一列8宽示列
    	->addFormItemStyle('avatar3', 'slider', '进度条', '',['width'=>8])
    	->addFormItemStyle('avatar1', 'tags', '标签', '',['width'=>6])
    	->addFormItemStyle('avatar', 'picture', '上传图片', '',['width'=>8])
    	->addFormItemStyle('avatar2', 'pictures', '多图上传', '',['width'=>8])
    	->addFormItemSimple('vip', 'radio', 'VIP等级', 'VIP等级', $user_object->user_vip_level(),['width'=>8])
    	->addFormItemStyle('aaaa','kindeditor', '大编辑器','',['width'=>8])
    	->addFormItemStyle('bbb','simditor', '小编辑器','',['width'=>8])
    	->setFormData($info);
    	 
    }
    
    public function binditem($builder,$info,$create=1){   
    	$user_object = D('User');
    	$builder->setTemplate('_Engines/formrow')    	
    	->addFormItemStyle('reg_type', 'hidden', '注册方式', '注册方式')
    	//三列示列
    	->addFormItemSimple('usertype', 'radio', '用户类型', '用户类型', $user_object->user_type(),['rowend'=>0])
    	->addFormItemStyle('username', 'text', '用户名', '用户名',['rowbegin'=>0,'rowend'=>0])
    	->addFormItemStyle('email', 'text', '邮箱', '邮箱',['rowbegin'=>0])
    	//二列示列
    	->addFormItemStyle('mobile', 'text', '手机号码', '手机号码',['rowend'=>0,'width'=>8])
    	->addFormItemStyle('password', 'password', '密码', '密码',['rowbegin'=>0])
    	//二列平衡示列
    	->addFormItemStyle('bbb','date', '日期选择','手机号码',['rowend'=>0,'width'=>6])
    	->addFormItemStyle('bbb1','time', '时间选择','',['rowbegin'=>0,'width'=>6])
    	//一列8宽示列
    	->addFormItemStyle('avatar1', 'slider', '进度条', '',['width'=>8])
    	->addFormItemStyle('avatar12', 'tags', '标签', '',['width'=>6])
    	->addFormItemStyle('avatar', 'picture', '上传图片', '',['width'=>10])
    	->addFormItemStyle('avatar3', 'pictures', '多图上传', '',['width'=>10])
    	->addFormItemSimple('vip', 'radio', 'VIP等级', 'VIP等级', $user_object->user_vip_level(),['width'=>8])
    	->addFormItemStyle('aaaa','kindeditor', '大编辑器','',['width'=>10])    	
    	->addFormItemStyle('bbb','simditor', '小编辑器','',['width'=>8])
    	->setFormData($info);
    	
    }
    
    
    /**
     * 新增用户
     * @author zhouxin 
     */
    public function adddata(){
    	if(IS_POST){
    		$this->saveDatalist(D('Message'));
            $this->success('新增成功', U('index'));    		
    	}else{
    		$user_object = D('User');
    		$data_list=D('Message')->where('id in (4,5,6)')->select();
    		//使用FormBuilder快速建立表单页面。
    		$builder = new \Common\Engines\FormEngines();
    		$builder->setMetaTitle('新增用户') //设置页面标题+
    		->setPostUrl(U('adddata')) //设置表单提交地址
    		->addFormItemStyle('postdata', 'datalist', '列表数据', '列表数据',['width'=>8])
    		->addTableColumn('name', '姓名')
    		->addTableColumn('phone', '手机号')
    		->addTableColumn('sex', '性别','checkbox')
    		->setTableDataList($data_list) //数据列表    		
    		->display();
    	}
    }

    /**
     * 编辑用户
     * @author zhouxin 
     */
    public function edit($id){
    	
        //获取用户信息
        $info = D('User')->find($id);

        if(IS_POST){
        	exit;
            $user_object = D('User');
            //不修改密码时销毁变量
            if($_POST['password'] == '' || $info['password'] == $_POST['password']){
                unset($_POST['password']);
            }else{
                $_POST['password'] = user_md5($_POST['password']);
            }
            //不允许更改超级管理员用户组
            if($_POST['id'] == 1){
                unset($_POST['group']);
            }
            if($_POST['extend']){
                $_POST['extend'] = json_encode($_POST['extend']);
            }
            if($user_object->save($_POST)){
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
                    ->display();
        }
    }
}

