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
class CrmClientController extends ManageController{
    /**
     * 列表
     * @author zhouxin
     */
    public function index(){
        //搜索
                //搜索数据		
        $data_list = D('CrmClient')->page(!empty($_GET["p"])?$_GET["p"]:1, C('ADMIN_PAGE_ROWS'))->where($map)->order('id asc')->select();
        $page = new \Common\Util\Page(D('CrmClient')->where($map)->count(), C('ADMIN_PAGE_ROWS'));
        
        //使用Builder快速建立列表页面。
        $builder = new \Common\Engines\ListEngines();
        $builder->setMetaTitle('客户列表') //设置页面标题
        		//->setCheck(false)
                ->addTopButton('addnew')  //添加新增按钮  
                ->addTableColumn('clientname', '客户名称')
                ->addTableColumn('tel', '电话')
                ->addTableColumn('level', '客户等级','list',D('CrmClient')->level())
                ->addTableColumn('state', '','status')
                ->setTableDataListKey('id')      
                ->addTableColumn('right_button', '操作', 'btn')
                ->setTableDataList($data_list) //数据列表
                ->setTableDataPage($page->show()) //数据列表分页
                ->addRightButton('edit')   //添加编辑按钮                
                ->addRightButton('delete') //添加删除按钮
                ->addRightButton('view') //添加删除按钮
                //->addRightButton('self',['title'=>'添加联系人','href'=>U('CrmContact/add',['clientid'=>'__data_id__'])]) //添加删除按钮
                ->display();
    }
    
    /**
     * 列表
     * @author zhouxin
     */
    public function select(){
    	//搜索
    	//搜索数据
    	$map=array();
    	$data_list = D('CrmClient')->page(!empty($_GET["p"])?$_GET["p"]:1, 8)->where($map)->order('id asc')->select();
    	$page = new \Common\Util\Page(D('CrmClient')->where($map)->count(), 8);
    	//使用Builder快速建立列表页面。
    	$builder = new \Common\Engines\ListEngines();
    	$builder->setMetaTitle('客户列表') //设置页面标题
    	->setTemplate('_Engines/listpop')    	
    	->setSearch('请输入客户名称', '')
    	->setCheck(false)
    	->addTableColumn('clientname', '客户名称','hidden')
    	->addTableColumn('tel', '电话')
    	->setTableDataListKey('id')
    	->addTableColumn('right_button', '操作', 'btn')
    	->setTableDataList($data_list) //数据列表
    	->setTableDataPage($page->show()) //数据列表分页    	
    	->addRightButton('self',['title'=>'选择','href'=>'javascript:save(__data_id__,\'clientname\')']) //添加删除按钮
    	->display();
    }

    /**
     * 新增
     * @author zhouxin
     */
    public function add(){
        if(IS_POST){
            $object = D('CrmClient');
            $_POST['uid']=$this->uid;
            $_POST['iscontract']=0;
            $data = $object->create();
            if($data){
                $id = $object->add();
                if($id){
                    $this->success('新增成功', U('index'));
                }else{
                    $this->error('新增失败');
                }
            }else{
                $this->error($object->getError());
            }
        }else{            
            $info=array();
            //使用FormBuilder快速建立表单页面。            
            $builder = new \Common\Engines\FormEngines();
            $this->binditem($builder,$info);
            $builder->setMetaTitle('新增客户') //设置页面标题
                    ->setPostUrl(U('add')) //设置表单提交地址                    
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
            $count=D('CrmContact')->where(['clientid'=>$_POST['id']])->count();
        	if ($count>0)
        	{
        		$_POST['iscontract']=1;
        	}
        	else
        	{
        		$_POST['iscontract']=0;
        	}        	
            $object = D('CrmClient');
            $result=$object->save($_POST);
            if ($result>0 || $result ===0) {            
                $this->success('更新成功', U('index'));
            }else{
                $this->error('更新失败', $object->getError());
            }
        }else{
            $object = D('CrmClient');
            $info = $object->find($id);
            //使用FormBuilder快速建立表单页面。
            $builder = new \Common\Engines\FormEngines();
            $this->binditem($builder,$info,0);
            $builder->setMetaTitle('编辑-客户') //设置页面标题
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
     			$builder->setTemplate('_Engines/formrow')    	
     				->addFormItemStyle('clientname', 'text', '客户名称', '',['width'=>6])
                    ->addFormItemStyle('email', 'text', '邮箱', '',['rowend'=>0,'width'=>6])
                    ->addFormItemStyle('zipcode', 'text', '邮编', '',['rowbegin'=>0])
                    ->addFormItemStyle('tel', 'text', '电话', '',['rowend'=>0,'width'=>5])
                    ->addFormItemStyle('fax', 'text', '传真', '',['rowbegin'=>0,'width'=>5])                    
                    ->addFormItemSimple('level', 'select', '客户等级', '',D('CrmClient')->level(),['rowend'=>0])
                    ->addFormItemSimple('intent', 'select', '客户意向', '',D('CrmClient')->intent(),['rowbegin'=>0])
                    ->addFormItemStyle('address', 'textarea', '地址', '',['width'=>8])
                    ->addFormItemStyle('remark', 'simditor', '备注', '',['width'=>8])
                    ->addFormItemSimple('openshare', 'radio', '是否共享', '',D('CrmClient')->openshare(),['width'=>8])
                    ->addFormItemSimple('state', 'radio', '客户状态', '',D('CrmClient')->state(),['width'=>8])
                    //->addFormItem('iscontract', 'text', '是否有联系人', '')
                    //->addFormItem('uid', 'hidden', '负责人', '')                    
                    ->setFormData($info);
     }  
     /**
     * 删除
     * @author zhouxin
     */
     public function delete(){
    	$ids    = I('request.ids');    	
    	$map['id'] = $ids;
    	$result = D('CrmClient')->where($map)->delete();
    	if($result){
    		$this->success('删除成功，不可恢复！');
    	}else{
    		$this->error('删除失败');
    	}
    } 
     /**
     * 查看
     * @author zhouxin
     */
    public function view($id){
    	$object = D('CrmClient');
    	$info = $object->find($id);
    	$builder = new \Common\Engines\FormEngines();
    	$builder->setMetaTitle('查看-客户') //设置页面标题
    			->setTemplate('_Engines/view')    	
                    ->addFormItemStyle('clientname', 'viewdiv', '客户名称', '',['rowend'=>0])                   
                    ->addFormItemSimple('intent', 'viewdiv', '客户意向', '',D('CrmClient')->intent(),['rowbegin'=>0,'rowend'=>0])
                    ->addFormItemStyle('tel', 'viewlabel', '电话', '',['rowbegin'=>0])
                    ->addFormItemStyle('fax', 'viewdiv', '传真', '',['rowend'=>0])                    
                    ->addFormItemSimple('level', 'viewdiv', '客户等级', '',D('CrmClient')->level(),['rowbegin'=>0,'rowend'=>0])
                    ->addFormItemStyle('zipcode', 'viewlabel', '邮编', '',['rowbegin'=>0])
                    ->addFormItemStyle('email', 'viewdiv', '邮箱', '',['rowend'=>0,'width'=>6])
                    ->addFormItemSimple('openshare', 'viewdiv', '是否共享', '',D('CrmClient')->openshare(),['rowbegin'=>0,'width'=>6])
                    ->addFormItemSimple('state', 'viewdiv', '客户状态', '',D('CrmClient')->state(),['rowend'=>0])
                    ->addFormItemSimple('iscontract', 'viewdiv', '是否有联系人', '',D('CrmClient')->iscontract(),['rowbegin'=>0,'rowend'=>0])
                    ->addFormItemStyle('uid', 'viewdiv', '负责人', '',['rowbegin'=>0])
                    ->addFormItemStyle('address', 'viewdiv', '地址', '',['width'=>8])
                    ->addFormItemStyle('remark', 'viewdiv', '备注', '',['width'=>12])                        
    	->setFormData($info)
    	->display();
    }
}
