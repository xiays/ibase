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
class CrmContactController extends ManageController{
    /**
     * 列表
     * @author zhouxin
     */
    public function index(){
        //搜索
                //搜索数据		
        $data_list = D('CrmContact cc')->join('ibase_crm_client c on c.id=cc.clientid','left')->page(!empty($_GET["p"])?$_GET["p"]:1, C('ADMIN_PAGE_ROWS'))->where($map)->order('cc.id asc')->field('cc.*,c.clientname')->select();
        $page = new \Common\Util\Page(D('CrmContact')->where($map)->count(), C('ADMIN_PAGE_ROWS'));
        
        //使用Builder快速建立列表页面。
        $builder = new \Common\Engines\ListEngines();
        $builder->setMetaTitle('联系人') //设置页面标题
                ->addTopButton('addnew')  //添加新增按钮  
                ->setCheck(false)
                ->addTableColumn('contactname', '联系人')
                ->addTableColumn('clientname', '客户名称')
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
            $object = D('CrmContact');
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
            $info=['clientid'=>I('get.clientid'),'sex'=>1];
            //使用FormBuilder快速建立表单页面。            
            $builder = new \Common\Engines\FormEngines();
            $this->binditem($builder,$info);
            $builder->setMetaTitle('新增-联系人') //设置页面标题
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
        	
            $object = D('CrmContact');  
            $result=$object->save($_POST);
            if ($result>0 || $result ===0) {            
                $this->success('更新成功', U('index'));
            }else{
                $this->error('更新失败', $object->getError());
            }
        }else{
            $object = D('CrmContact');
            $info = $object->find($id);
            //使用FormBuilder快速建立表单页面。
            $builder = new \Common\Engines\FormEngines();
            $this->binditem($builder,$info,0);
            $builder->setMetaTitle('编辑-联系人') //设置页面标题
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
     		$builder->addFormItemStyle('clientid', 'selectid', '客户', '',['unit'=>'<i class="glyphicon-user"></i>','url'=>U('CrmClient/select'),'value'=>$client])
                    ->addFormItemStyle('contactname', 'text', '联系人', '')
                    ->addFormItem('sex', 'radio', '性别', '',D("CrmContact")->sex())
                    ->addFormItemStyle('post', 'text', '职位', '')
                    ->addFormItemStyle('qq', 'text', '腾讯QQ', '')
                    ->addFormItemStyle('wx', 'text', '微信', '')
                    ->addFormItemStyle('phone', 'text', '手机', '')
                    ->addFormItemStyle('tel', 'text', '联系电话', '')
                    ->addFormItemStyle('fax', 'text', '传真', '')
                    ->addFormItemStyle('email', 'text', '电子邮箱', '')
                    ->addFormItemStyle('birthday', 'ddate', '生日', '')
                    ->addFormItemStyle('content', 'simditor', '备注说明', '',['width'=>8])
                    ->addFormItemStyle('uid', 'hidden', '负责人', '')                    
                    ->setFormData($info);
     }  
     /**
     * 删除
     * @author zhouxin
     */
     public function delete(){
    	$ids    = I('request.ids');    	
    	$map['id'] = $ids;
    	$result = D('CrmContact')->where($map)->delete();
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
    	$object = D('CrmContact');
    	$info = $object->find($id);
    	$builder = new \Common\Engines\FormEngines();
    	$builder->setMetaTitle('查看-联系人') //设置页面标题
    	->setTemplate('_Engines/view')    	
                    ->addFormItemStyle('clientid', 'viewlabel', '客户编号', '')
                    ->addFormItemStyle('contactname', 'viewlabel', '联系人', '')
                    ->addFormItemStyle('sex', 'viewlabel', '性别', '')
                    ->addFormItemStyle('post', 'viewlabel', '职位', '')
                    ->addFormItemStyle('qq', 'viewlabel', '腾讯QQ', '')
                    ->addFormItemStyle('wx', 'viewlabel', '微信', '')
                    ->addFormItemStyle('phone', 'viewlabel', '手机', '')
                    ->addFormItemStyle('tel', 'viewlabel', '联系电话', '')
                    ->addFormItemStyle('fax', 'viewlabel', '传真', '')
                    ->addFormItemStyle('email', 'viewlabel', '电子邮箱', '')
                    ->addFormItemStyle('birthday', 'viewlabel', '生日', '')
                    ->addFormItemStyle('content', 'viewlabel', '备注说明', '')
                    ->addFormItemStyle('uid', 'viewlabel', '负责人', '')    
    	->setFormData($info)
    	->display();
    }
}