<?php
// +----------------------------------------------------------------------
// | Ioacrm [ Simple Efficient Excellent ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016 http://www.ioacrm.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: zhouxin <http://www.ioacrm.com>
// +----------------------------------------------------------------------

namespace Admin\Controller;
/**
 * 后台控制器
 * @author zhouxin
 */
class DocumentController extends AdminController{
    /**
     * 列表
     * @author zhouxin
     */
    public function index(){
        //搜索
                //搜索数据		
    	$map=array();
    	$this->getmap($map,'cid');
    	if (I('get.keyword'))   
    		$map['title']=['like','%'.I('get.keyword').'%'];
        $data_list = D('Document')->page(!empty($_GET["p"])?$_GET["p"]:1, C('ADMIN_PAGE_ROWS'))->where($map)->order('id asc')->select();
        $page = new \Common\Util\Page(D('Document')->where($map)->count(), C('ADMIN_PAGE_ROWS'));
        $category=select_list_as_tree('Category');
        //使用Builder快速建立列表页面。
        $builder = new \Common\Engines\ListEngines();
        $builder->setMetaTitle('列表') //设置页面标题
                ->addTopButton('addnew')  //添加新增按钮
                ->setSearch('请输入文章标题', U('index'))
                ->addSearch('cid', 'select', '分类名称','',$category)
                ->addTableColumn('title', '标题')  
                ->addTableColumn('cid', '分类','list',$category)
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
            $user_object = D('Document');
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
            $info=array('status'=>1,'sort'=>0);
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
    public function edit($id){
        //获取用户信息    
        if(IS_POST){
            $user_object = D('Document');  
            $result=$user_object->save($_POST);
            if ($result>0 || $result ===0) {            
                $this->success('更新成功', U('index'));
            }else{
                $this->error('更新失败', $user_object->getError());
            }
        }else{
            $user_object = D('Document');
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
     		$builder->addFormItem('cid', 'select', '分类ID', '',select_list_as_tree('Category'))                                      
                    ->addFormItem('title', 'text', '标题', '')                    
                    ->addFormItem('abstract', 'textarea', '简介', '')
                    ->addFormItem('tags', 'tags', '标签', '')
                    ->addFormItem('sort', 'text', '排序', '')
                    ->addFormItemStyle('content', 'kindeditor', '正文内容', '',['width'=>8])                    
                    ->addFormItem('status', 'radio', '状态', '',D('Document')->status())
                    
                    ->setFormData($info);
     }  
     /**
     * 删除
     * @author zhouxin
     */
     public function delete(){
    	$ids    = I('request.ids');    	
    	$map['id'] = $ids;
    	$result = D('Document')->where($map)->delete();
    	if($result){
    		$this->success('删除成功，不可恢复！');
    	}else{
    		$this->error('删除失败');
    	}
    } 
}