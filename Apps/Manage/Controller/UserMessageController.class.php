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
class UserMessageController extends ManageController{
    /**
     * 列表
     * @author zhouxin
     */
    public function index($group=0){
        //搜索
        $tab_list[0]['title'] = '全部';
    	$tab_list[0]['href']  = U('index', array('group' => 0));
    	$tab_list[1]['title'] = '未读';
    	$tab_list[1]['href']  = U('index', array('group' => 1));    	
    	if ($group==1)
    	{
    		$map['is_read']=0;
    	}
    	$map['to_uid']=$this->uid;
        $data_list = D('UserMessage')->page(!empty($_GET["p"])?$_GET["p"]:1, C('ADMIN_PAGE_ROWS'))->where($map)->order('id asc')->select();
        $page = new \Common\Util\Page(D('UserMessage')->where($map)->count(), C('ADMIN_PAGE_ROWS'));
        //if ($group==1)
        //{
        //	D('UserMessage')->where(['to_uid'=>$this->uid,'is_read'=>0])->save(['is_read'=>1]);
        //}
        //使用Builder快速建立列表页面。
        $builder = new \Common\Engines\ListEngines();
        $builder->setMetaTitle('消息列表') //设置页面标题
                //->addTopButton('addnew')  //添加新增按钮
                ->setCheck(false)
                ->setTabNav($tab_list, $group)
                ->addTableColumn('title', '消息标题')                
                ->addTableColumn('is_read', '已读','status')
                ->setTableDataListKey('id')      
                ->addTableColumn('right_button', '操作', 'btn')
                ->setTableDataList($data_list) //数据列表
                ->setTableDataPage($page->show()) //数据列表分页                                
        		->addRightButton('view') //添加删除按钮
                ->addRightButton('delete') //添加删除按钮                
                ->display();
    }

    public function readcount(){
    	$unread_message=D('UserMessage')->where(['to_uid'=>$this->uid,'is_read'=>0])->count();
    	$result='{"status":1,"data":{"unread_notify":0,"unread_atme":0,"unread_comment":0,"unread_message":'.$unread_message.',"new_folower_count":0,"unread_total":'.$unread_message.'}}';
    	echo $result;
    	exit;
    	//    	
    }

    /**
     * 新增
     * @author zhouxin
     */
    public function add(){
    	if (I('get.return')=="back")
    	{
    		$return ="<script>window.parent.$.artDialog({id: 'pm_dialog'}).close();</script>";
    		echo $return;
    		exit;
    	}
        if(IS_POST){
            $user_object = D('UserMessage');
           $_POST['to_uid']=implode($_POST['to_uid'],','); 
            $data = $user_object->create();
            if($data){
                $id = $user_object->add();
                if($id){
                    $this->success('新增成功', U('add',['return'=>'back']));
                }else{
                    $this->error('新增失败');
                }
            }else{
                $this->error($user_object->getError());
            }
        }else{            
            $info=array('type'=>2,'from_uid'=>$this->loginuser['uid']);
            //使用FormBuilder快速建立表单页面。            
            $builder = new \Common\Engines\FormEngines();
            $this->binditem($builder,$info);
            $builder->setMetaTitle('新增-消息') //设置页面标题
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
            $user_object = D('UserMessage'); 
            $data = $user_object->create();
            
            $result=$user_object->save($_POST);
            if ($result>0 || $result ===0) {            
                $this->success('更新成功', U('index'));
            }else{
                $this->error('更新失败', $user_object->getError());
            }
        }else{
            $user_object = D('UserMessage');
            $info = $user_object->find($id);
            //使用FormBuilder快速建立表单页面。
            $builder = new \Common\Engines\FormEngines();
            $this->binditem($builder,$info,0);
            $builder->setMetaTitle('编辑-消息') //设置页面标题
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
     		if ($info['to_uid']>0)
     		{
     			$clientinfo=D("User")->find($info['to_uid']);
     			if ($clientinfo!=null)
     			{
     				$client=$clientinfo['username'];
     			}
     		}
     		$builder->addFormItemStyle('to_uid', 'selectid', '客户', '',['unit'=>'<i class="glyphicon-user"></i>','url'=>U('User/select'),'value'=>$client])
     			    ->addFormItemStyle('title', 'text', '消息标题', '',['width'=>8])
                    ->addFormItemStyle('content', 'simditor', '消息内容', '',['width'=>8])
                    ->addFormItem('type', 'hidden', '0系统消息,1评论消息,2私信消息', '')
                    ->addFormItem('from_uid', 'hidden', '私信消息发信用户ID', '')                           
                    ->setFormData($info);
     }  
     /**
     * 删除
     * @author zhouxin
     */
     public function delete(){
    	$ids    = I('request.ids');    	
    	$map['id'] = $ids;
    	$map['uid']=$this->uid;
    	$result = D('UserMessage')->where($map)->delete();
    	if($result){
    		$this->success('删除成功，不可恢复！');
    	}else{
    		$this->error('删除失败');
    	}
    } 
    
    /**
     * 
     */
    public function view($id){
    	$user_object = D('UserMessage');
    	$info = $user_object->find($id);
    	$info['is_read']=1;
    	$user_object->save($info);
    	$builder = new \Common\Engines\FormEngines();
    	$builder->setMetaTitle('查看-消息') //设置页面标题
    	->setTemplate('_Engines/view')    	
    	->addFormItemStyle('title', 'viewdiv', '消息标题', '',['width'=>11])
    	->addFormItemStyle('content', 'viewdiv', '消息内容', '',['width'=>11])    	
    	->setFormData($info)
    	->display();
    	
    }
}