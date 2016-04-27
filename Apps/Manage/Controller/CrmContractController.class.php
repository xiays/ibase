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
class CrmContractController extends ManageController{
    /**
     * 列表
     * @author zhouxin
     */
    public function index(){
        //搜索
                //搜索数据		
        $data_list = D('CrmContract')->page(!empty($_GET["p"])?$_GET["p"]:1, C('ADMIN_PAGE_ROWS'))->where($map)->order('id asc')->select();
        $page = new \Common\Util\Page(D('CrmContract')->where($map)->count(), C('ADMIN_PAGE_ROWS'));
        
        //使用Builder快速建立列表页面。
        $builder = new \Common\Engines\ListEngines();
        $builder->setMetaTitle('合同列表') //设置页面标题
                ->addTopButton('addnew')  //添加新增按钮  
                ->addTableColumn('name', '合同名称')
                ->addTableColumn('clientname', '客户名称')
                ->addTableColumn('projectname', '项目名称')                ->setCheck(false)
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
        	$_POST['uid']=$this->loginuser['uid'];
        	$_POST['clientname']=D('CrmClient')->find($_POST['clientid'])['clientname'];
        	if ($_POST['project_id']!=null)
        	{
        		$Project=D('Project')->find($_POST['project_id']);
        		$_POST['projectname']=$Project['name'];
        	}
            $object = D('CrmContract');
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
            $info=array('state'=>1,'status'=>1);
            //使用FormBuilder快速建立表单页面。            
            $builder = new \Common\Engines\FormEngines();
            $this->binditem($builder,$info);
            $builder->setMetaTitle('新增-合同') //设置页面标题
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
            $object = D('CrmContract');  
            $result=$object->save($_POST);
            if ($result>0 || $result ===0) {            
                $this->success('更新成功', U('index'));
            }else{
                $this->error('更新失败', $object->getError());
            }
        }else{
            $object = D('CrmContract');
            $info = $object->find($id);
            //使用FormBuilder快速建立表单页面。
            $builder = new \Common\Engines\FormEngines();
            $this->binditem($builder,$info,0);
            $builder->setMetaTitle('编辑-合同') //设置页面标题
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
     	$client="";
     	if ($info['clientid']>0)
     	{
     		$clientinfo=D("CrmClient")->find($info['clientid']);
     		if ($clientinfo!=null)
     		{
     			$client=$clientinfo['clientname'];
     		}
     	}
     		$builder->addFormItemStyle('name', 'text', '合同名称', '')
     				->addFormItemStyle('code', 'text', '合同号', '')
                    ->addFormItemStyle('uid', 'hidden', '员工id', '')
                    ->addFormItemStyle('clientid', 'selectid', '客户', '',['unit'=>'<i class="glyphicon-user"></i>','url'=>U('CrmClient/select'),'value'=>$client])
                    ->addFormItem('project_id', 'select', '客户项目', '',select_list_as_array(D('Project')->select(), 'id', 'name'))
                    ->addFormItemStyle('starttime', 'date', '合同生效日期', '')
                    ->addFormItemStyle('endtime', 'date', '合同终止日期', '')
                    ->addFormItemStyle('order_amount', 'text', '合同金额', '')
                    ->addFormItemStyle('clientname', 'hidden', '客户名称', '')
                    ->addFormItemStyle('projectname', 'hidden', '项目名称', '')
                    ->addFormItem('state', 'radio', '付款状态', '',D('CrmContract')->state())
                    ->addFormItem('status', 'radio', '禁用状态', '',D('CrmContract')->status())
                    ->addFormItemStyle('remark', 'textarea', '备注信息', '')                    
                    ->setFormData($info);
     }  
     /**
     * 删除
     * @author zhouxin
     */
     public function delete(){
    	$ids    = I('request.ids');    	
    	$map['id'] = $ids;
    	$result = D('CrmContract')->where($map)->delete();
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
    	$object = D('CrmContract');
    	$info = $object->find($id);
    	$builder = new \Common\Engines\FormEngines();
    	$builder->setMetaTitle('查看-合同') //设置页面标题
    	->setTemplate('_Engines/view')
    	
                    ->addFormItemStyle('name', 'viewlabel', '合同名称', '')
                    ->addFormItemStyle('uid', 'viewlabel', '员工id', '')
                    ->addFormItemStyle('clientid', 'viewlabel', '客户ID', '')
                    ->addFormItemStyle('project_id', 'viewlabel', '项目ID', '')
                    ->addFormItemStyle('code', 'viewlabel', '合同号', '')
                    ->addFormItemStyle('ctime', 'viewlabel', '创建日期', '')
                    ->addFormItemStyle('utime', 'viewlabel', '修改日期', '')
                    ->addFormItemStyle('starttime', 'viewlabel', '合同生效日期', '')
                    ->addFormItemStyle('endtime', 'viewlabel', '合同终止日期', '')
                    ->addFormItemStyle('order_amount', 'viewlabel', '合同金额', '')
                    ->addFormItemStyle('clientname', 'viewlabel', '客户名称', '')
                    ->addFormItemStyle('projectname', 'viewlabel', '项目名称', '')
                    ->addFormItemStyle('state', 'viewlabel', '付款状态', '')
                    ->addFormItemStyle('status', 'viewlabel', '禁用状态', '')
                    ->addFormItemStyle('remark', 'viewlabel', '备注信息', '')    
    	->setFormData($info)
    	->display();
    }
}