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
class ProjectController extends ManageController{
    /**
     * 列表
     * @author zhouxin
     */
    public function index(){
        //搜索
                //搜索数据		
        $data_list = D('Project')->page(!empty($_GET["p"])?$_GET["p"]:1, C('ADMIN_PAGE_ROWS'))->where($map)->order('id asc')->select();
        $page = new \Common\Util\Page(D('Project')->where($map)->count(), C('ADMIN_PAGE_ROWS'));
        
        //使用Builder快速建立列表页面。
        $builder = new \Common\Engines\ListEngines();
        $builder->setMetaTitle('项目管理') //设置页面标题
                ->addTopButton('addnew')  //添加新增按钮  
                ->setCheck(false)
                ->addTableColumn('name', '项目名称')                
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
            $object = D('Project');
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
            $info=array('status'=>1);
            //使用FormBuilder快速建立表单页面。            
            $builder = new \Common\Engines\FormEngines();
            $this->binditem($builder,$info);
            $builder->setMetaTitle('新增-项目') //设置页面标题
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
            $object = D('Project');  
            $result=$object->save($_POST);
            if ($result>0 || $result ===0) {            
                $this->success('更新成功', U('index'));
            }else{
                $this->error('更新失败', $object->getError());
            }
        }else{
            $object = D('Project');
            $info = $object->find($id);
            //使用FormBuilder快速建立表单页面。
            $builder = new \Common\Engines\FormEngines();
            $this->binditem($builder,$info,0);
            $builder->setMetaTitle('编辑-项目') //设置页面标题
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
     			$builder
                    ->addFormItemStyle('name', 'text', '项目名称', '')
                    ->addFormItemStyle('type', 'text', '项目类型', '')                                     
                    ->addFormItemStyle('address', 'textarea', '项目所在地', '')
                    //->addFormItemStyle('rebate', 'num', '返佣比例', '')
                    ->addFormItemStyle('price', 'num', '项目价格', '')
                    //->addFormItemStyle('contact', 'num', '合同和佣金', '')                    
                    //->addFormItemStyle('profit', 'num', '公司利润', '')
                    ->addFormItemStyle('content', 'simditor', '项目描述', '',['width'=>8])
                    ->addFormItem('status', 'radio', '状态', '', D('Project')->status())                          
                    ->setFormData($info);
     }  
     /**
     * 删除
     * @author zhouxin
     */
     public function delete(){
    	$ids    = I('request.ids');    	
    	$map['id'] = $ids;
    	$result = D('Project')->where($map)->delete();
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
    	$object = D('Project');
    	$info = $object->find($id);
    	$builder = new \Common\Engines\FormEngines();
    	$builder->setMetaTitle('查看-项目') //设置页面标题
    	->setTemplate('_Engines/view')    	
                    ->addFormItemStyle('name', 'viewlabel', '项目名称', '')
                    ->addFormItemStyle('type', 'viewlabel', '项目类型', '')
                    ->addFormItemStyle('rebate', 'viewlabel', '返佣比例', '')
                    ->addFormItemStyle('price', 'viewlabel', '项目价格', '')
                    ->addFormItemStyle('address', 'viewlabel', '项目所在地', '')
                    ->addFormItemStyle('contact', 'viewlabel', '合同和佣金', '')
                    ->addFormItemStyle('analysis', 'viewlabel', '项目利润分析', '')
                    ->addFormItemStyle('content', 'viewlabel', '项目描述', '')
                    ->addFormItemStyle('status', 'viewlabel', '状态 1 开启 0关闭', '')
                    ->addFormItemStyle('ctime', 'viewlabel', '创建时间', '')
                    ->addFormItemStyle('profit', 'viewlabel', '公司利润', '')
                    ->addFormItemStyle('from', 'viewlabel', '所属海外机构', '')
                    ->addFormItemStyle('comopnytype', 'viewlabel', '机构性质', '')    
    	->setFormData($info)
    	->display();
    }
}