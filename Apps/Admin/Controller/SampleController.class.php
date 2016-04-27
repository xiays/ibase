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
class SampleController extends AdminController{
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
                ->addTopButton('addnew',['href'=>U('adddata'),'title'=>'测试添加列表'])  //添加启用按钮
                ->addTopButton('forbid')  //添加禁用按钮
                ->addTopButton('delete')  //添加删除按钮
                ->setSearch('请输入ID/用户名/邮箱/手机号', U('index'))
               	->addSearch('companyname', 'text', '渠道名称', '')
                ->addSearch('level', 'select', '渠道级别','',D('User')->user_vip_level())
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
            $data_list=D('Message')->select();
            //使用FormBuilder快速建立表单页面。
            $builder = new \Common\Engines\FormEngines();
            $builder->setMetaTitle('新增用户') //设置页面标题+ 
                    ->setPostUrl(U('add')) //设置表单提交地址                    
                    ->addFormItem('reg_type', 'hidden', '注册方式', '注册方式')
                    ->addFormItem('usertype', 'radio', '用户类型', '用户类型', $user_object->user_type())
                    ->addFormItem('username', 'text', '用户名', '用户名')
                    ->addFormItem('email', 'text', '邮箱', '邮箱')
                    ->addFormItem('mobile', 'text', '手机号码', '手机号码')
                    ->addFormItem('password', 'password', '密码', '密码')
                    ->addFormItemStyle('bbb','date', '日期选择','')
                    ->addFormItemStyle('bbb1','time', '时间选择','')
                    ->addFormItem('avatar', 'slider', '进度条', '')
                    ->addFormItem('avatar', 'tags', '标签', '')
                    ->addFormItem('avatar', 'picture', '上传图片', '')
                    ->addFormItem('avatar', 'pictures', '多图上传', '')
                    ->addFormItem('vip', 'radio', 'VIP等级', 'VIP等级', $user_object->user_vip_level())                    
                    ->addFormItemStyle('aaaa','kindeditor', '大编辑器','',['width'=>8])
                    ->addFormItemStyle('bbb','simditor', '小编辑器','',['width'=>6])                    
                    ->setFormData(array('reg_type' => 1)) //注册方式为后台添加
                    ->display();
        }
    }
    
    
    /**
     * 新增用户
     * @author zhouxin ioacrm
     */
    public function adddata(){
    	if(IS_POST){    		
    		//implode('')
    		$this->saveDatalist(D('Message'));
            $this->success('新增成功', U('index'));    		
    	}else{
    		$user_object = D('User');
    		$data_list=D('Message')->where('id in (4,5,6)')->select();
    		//使用FormBuilder快速建立表单页面。
    		$builder = new \Common\Engines\FormEngines();
    		$builder->setMetaTitle('新增用户') //设置页面标题+
    		->setPostUrl(U('adddata')) //设置表单提交地址
    		->addFormItem('reg_type', 'hidden', '注册方式', '注册方式')
    		->addFormItem('usertype', 'radio', '用户类型', '用户类型', $user_object->user_type())
    		->addFormItem('username', 'text', '用户名', '用户名')
    		->addFormItem('email', 'text', '邮箱', '邮箱')
    		->addFormItem('mobile', 'text', '手机号码', '手机号码')
    		->addFormItem('password', 'password', '密码', '密码')
    		->addFormItem('avatar', 'slider', '用户头像', '用户头像')
    		->addFormItem('vip', 'radio', 'VIP等级', 'VIP等级', $user_object->user_vip_level())
    		->addFormItemStyle('postdata', 'datalist', '用户头像', '用户头像',['width'=>10])
    		->addTableColumn('name', '姓名')
    		->addTableColumn('phone', '手机号')
    		->addTableColumn('sex', '性别','checkbox')
    		->setTableDataList($data_list) //数据列表
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
            $builder->setMetaTitle('编辑用户') //设置页面标题
                    ->setPostUrl(U('edit')) //设置表单提交地址
                    ->addFormItem('id', 'hidden', 'ID', 'ID')
                    ->addFormItem('usertype', 'radio', '用户类型', '用户类型', $user_object->user_type())
                    ->addFormItem('group', 'select', '部门', '所属部门', select_list_as_tree('UserGroup', null, '默认部门'))
                    ->addFormItem('username', 'text', '用户名', '用户名')
                    ->addFormItem('email', 'text', '邮箱', '邮箱')
                    ->addFormItem('mobile', 'text', '手机号码', '手机号码')
                    ->addFormItem('password', 'password', '密码', '密码')
                    ->addFormItem('avatar', 'picture', '用户头像', '用户头像')
                    ->addFormItem('vip', 'radio', 'VIP等级', 'VIP等级', $user_object->user_vip_level())                   
                    ->setFormData($info)
                    ->display();
        }
    }
    
    public function testchart(){
    	// 搜索
    	// 使用Builder快速建立列表页面。
    	$searchinfo = $_GET;
    	
    	$Client = M('CrmClient');
    	if (I('get.start')) {
    		$where['ct_client.ctime'] = [['gt',I('get.start')],['elt',I('get.end').' 23:59']];
    	}
    	else {
    		$searchinfo['start'] = date('Y-1-1');
    		$searchinfo['end'] = date('Y-12-31');
    		$where['ct_client.ctime'] = [['gt',date('Y-1-1')],['elt',date('Y-12-31')]];
    	}    	
    		$gruop = 'DATE_FORMAT(ctime,\'%Y-%m\')';
			if (I('get.type') == "1") {
				$gruop = 'DATE_FORMAT(ctime,\'%Y\')';
			}
			$list = $Client->where($where)->group($gruop)->field('count(*) v,' . $gruop . ' t')->select();
			$builder = new \Common\Engines\ChartEngines();
			$builder->setMetaTitle('客户数量统计') //设置页面标题			
			->addSearch('start', 'ddate', '起止日期')->addSearch('end', 'ddate', '至')->addSearch('type', 'select', '', '', ['0'=>'月','1'=>'年'], '', ' style="width:80px"')->setFormData($searchinfo)->setExtraHtml($this->getcolumn2d('客户数量统计', $list));
			$builder->display();
    }
}

