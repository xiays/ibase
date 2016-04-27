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
class CrmClientTraceController extends ManageController{
    /**
     * 列表
     * @author zhouxin
     */
    public function index(){
        //搜索
                //搜索数据		
    	$map['pid']=0;
        $data_list = D('CrmClientTrace')->page(!empty($_GET["p"])?$_GET["p"]:1, C('ADMIN_PAGE_ROWS'))->where($map)->order('id asc')->select();
        $page = new \Common\Util\Page(D('CrmClientTrace')->where($map)->count(), C('ADMIN_PAGE_ROWS'));
        
        //使用Builder快速建立列表页面。
        $builder = new \Common\Engines\ListEngines();
        $builder->setMetaTitle('跟单记录') //设置页面标题
        		->setCheck(false)
                ->addTopButton('addnew')  //添加新增按钮  
                ->addTableColumn('clientname', '客户名')
                ->addTableColumn('projectname', '项目名称')
                ->setTableDataListKey('id')      
                ->addTableColumn('right_button', '操作', 'btn')
                ->setTableDataList($data_list) //数据列表
                ->setTableDataPage($page->show()) //数据列表分页
                ->addRightButton('edit',['class'=>'cbtn o-plus','href'=>U('add',['id'=>'__data_id__'])])   //添加编辑按钮
                ->addRightButton('view') //添加删除按钮
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
        	$_POST['pid']=0;
        	$_POST['main']=1;
        	$Client=D('CrmClientTrace')->where(['clientid'=>$_POST['clientid'],'pid'=>0])->find();
        	if ($Client!=null)
        	{
        		$_POST['pid']=$Client['id'];
        		$_POST['main']=0;
        	}
        	if ($_POST['project_id']!=null)
        	{
        		$Project=D('Project')->find($_POST['project_id']);
        		$_POST['projectname']=$Project['name'];
        	}        	
            $object = D('CrmClientTrace');
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
        	if(I('get.id'))
        	{
        		$id=I('get.id');
        		$object = D('CrmClientTrace');
        		$info = $object->find($id);   
        		$info=['pid'=>$id,'clientid'=>$info['clientid'],'utime'=>date('Y-m-d')];
        	}
            //$info=array('utime'=>date('Y-m-d'));
            //使用FormBuilder快速建立表单页面。            
            $builder = new \Common\Engines\FormEngines();
            $this->binditem($builder,$info);
            $builder->setMetaTitle('新增-跟单记录') //设置页面标题
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
        	$_POST['uid']=$this->loginuser['uid'];
        	$_POST['clientname']=D('CrmClient')->find($_POST['clientid'])['clientname'];
        	$_POST['pid']=0;
        	$_POST['main']=1;
        	$Client=D('CrmClientTrace')->where(['clientid'=>$_POST['clientid'],'pid'=>0])->find();
        	if ($Client!=null)
        	{
        		$_POST['pid']=$Client['id'];
        		$_POST['main']=0;
        	}
        	if ($_POST['project_id']!=null)
        	{
        		$Project=D('Project')->find($_POST['project_id']);
        		$_POST['projectname']=$Project['name'];
        	}
            $object = D('CrmClientTrace');  
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
            $object = D('CrmClientTrace');
            $info = $object->find($id);
            $info=['pid'=>$id,'clientid'=>$info['clientid'],'utime'=>date('Y-m-d')];
            //使用FormBuilder快速建立表单页面。
            $builder = new \Common\Engines\FormEngines();
            $this->binditem($builder,$info,0);
            $builder->setMetaTitle('编辑-跟单记录') //设置页面标题
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
                    ->addFormItem('project_id', 'select', '客户项目', '',select_list_as_array(D('Project')->select(), 'id', 'name'))
                    ->addFormItem('intent', 'select', '客户意向', '',D('CrmClient')->intent())
                    ->addFormItem('type', 'select', '跟进类型', '',D('CrmClientTrace')->type())
                    ->addFormItemStyle('contacttype', 'text', '联系方式', '')
                    ->addFormItemStyle('utime', 'ddate', '到访时间', '')
                    ->addFormItemStyle('feedback', 'textarea', '客户反馈', '',['width'=>6])
                    ->addFormItemStyle('remark', 'textarea', '员工备注', '',['width'=>6])
                    ->addFormItemStyle('main', 'hidden', '是否为主贴', '')
                    ->addFormItemStyle('pid', 'hidden', '父级ID', '')
                    ->addFormItemStyle('clientname', 'hidden', '客户名', '')
                    ->addFormItemStyle('projectname', 'hidden', '项目名称', '')
                    ->setFormData($info);
     }  
     /**
     * 删除
     * @author zhouxin
     */
     public function delete(){
    	$ids    = I('request.ids');    	
    	$map['id'] = $ids;
    	$result = D('CrmClientTrace')->where($map)->delete();
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
    	$object = D('CrmClientTrace');
    	$info = $object->find($id);
    	$builder = new \Common\Engines\FormEngines();
    	$builder->setMetaTitle('查看-跟单记录') //设置页面标题
    				->setTemplate('_Engines/view')
                    ->addFormItemStyle('clientname', 'viewdiv', '客户名', '',['rowend'=>0])
                    ->addFormItemSimple('intent', 'viewdiv', '客户意向', '',D('CrmClient')->intent(),['rowbegin'=>0])
                    ->addFormItemStyle('utime', 'viewdiv', '跟进时间', '',['rowend'=>0,'width'=>3])
                    ->addFormItemStyle('ctime', 'viewdate', '创建时间', '',['rowbegin'=>0,'rowend'=>0,'width'=>3])
                    ->addFormItemStyle('projectname', 'viewdiv', '项目名称', '',['rowbegin'=>0,'width'=>6])
                    ->addFormItemStyle('contacttype', 'viewdiv', '联系方式', '',['rowend'=>0,'width'=>6])
                    ->addFormItemSimple('type', 'viewdiv', '跟进类型', '',D('CrmClientTrace')->type(),['rowbegin'=>0,'width'=>6])
                    ->addFormItemStyle('feedback', 'viewdiv', '客户反馈', '',['width'=>12])
                    ->addFormItemStyle('remark', 'viewdiv', '备注', '',['width'=>12])  ;                                                          
    	$list=$object->where(['pid'=>$id])->select();
    	$i=1;
    	foreach ($list as $m)
    	{
    		$info['ctime'.$i]=$m['ctime'];
    		$info['intent'.$i]=$m['intent'];
    		$info['utime'.$i]=$m['utime'];
    		$info['contacttype'.$i]=$m['contacttype'];
    		$info['type'.$i]=$m['type'];
    		$info['intent'.$i]=$m['intent'];
    		$info['feedback'.$i]=$m['feedback'];
    		$info['remark'.$i]=$m['remark'];
    		$builder->addFormItemStyle('feedback', 'viewlabel', $i.'楼 ('.date('Y-m-d',$m['ctime']).')  <a title="" class="cbtn o-trash ajax-get confirm" data-model="CrmContact" href="'.U('delete',['ids'=>$m['id']]).'"></a>', '',['width'=>12,'position'=>0])
		    		->addFormItemStyle('utime'.$i, 'viewlabel', '跟进时间', '',['rowend'=>0,'width'=>4])    		
		    		->addFormItemSimple('intent'.$i, 'viewdiv', '客户意向', '',D('CrmClient')->intent(),['rowbegin'=>0,'rowend'=>0,'width'=>4])
		    		->addFormItemSimple('type'.$i, 'viewdiv', '跟进类型', '',D('CrmClientTrace')->type(),['rowbegin'=>0,'width'=>4]);
    		if ($m['feedback']!='')
    			$builder->addFormItemStyle('feedback', 'viewdiv', '客户反馈', '',['width'=>12]);
    		if ($m['remark']!='')
    		$builder->addFormItemStyle('remark', 'viewdiv', '备注', '',['width'=>12]);
    		$i++;
    	}
    	$builder->setFormData($info)
    			->display();
    	
    	
    	
    }
}
