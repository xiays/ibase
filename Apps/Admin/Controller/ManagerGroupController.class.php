<?php
// +----------------------------------------------------------------------
// | ibase [ Simple Efficient Excellent ]
// +----------------------------------------------------------------------
// | Copyright (c) 2015. http://www.ioacrm.com All rights reserved.
// +----------------------------------------------------------------------
// |  Author: zhouxin <http://www.ioacrm.com>
// +----------------------------------------------------------------------
namespace Admin\Controller;
use Think\Controller;
use Common\Resource\AdminMenu;
/**
 * 部门控制器
 * @author zhouxin 
 */
class ManagerGroupController extends AdminController{
    /**
     * 部门列表
     * @author zhouxin
     */
    public function index(){
        //搜索
        $keyword = I('keyword', '', 'string');
        $condition = array('like','%'.$keyword.'%');
        $map['id|title'] = array($condition, $condition, '_multi'=>true); //搜索条件

        //获取所有部门
        $map['status'] = array('egt', '0'); //禁用和正常状态
        $data_list = D('ManagerGroup')->where($map)->order('sort asc, id asc')->select();

        //转换成树状列表
        $tree = new \Common\Util\Tree();
        $data_list = $tree->toFormatTree($data_list);

        //使用Builder快速建立列表页面。
        $builder = new \Common\Engines\ListEngines();
        $builder->setMetaTitle('权限列表') //设置页面标题
                ->addTopButton('addnew')  //添加新增按钮
                ->addTopButton('resume')  //添加启用按钮
                ->addTopButton('forbid')  //添加禁用按钮
                ->addTopButton('delete')  //添加删除按钮
                ->setSearch('请输入ID/部门名称', U('index'))
                ->addTableColumn('id', 'ID')
                ->addTableColumn('title_show', '标题')                
                ->addTableColumn('sort', '排序')
                ->addTableColumn('status', '状态', 'status')
                ->addTableColumn('right_button', '操作', 'btn')
                ->setTableDataList($data_list) //数据列表
                ->addRightButton('edit')   //添加编辑按钮                
                ->addRightButton('delete') //添加删除按钮
                ->display();
    }

    /**
     * 新增部门
     * @author zhouxin
     */
    public function add(){
        if(IS_POST){
            $user_group_object = D('ManagerGroup');
            $_POST['menu_auth']= implode(',', I('post.menu_auth'));
            $_POST['category_auth']= implode(',', I('post.category_auth'));
            $data = $user_group_object->create();
            if($data){
                $id = $user_group_object->add();
                if($id){
                    $this->success('新增成功', U('index'));
                }else{
                    $this->error('新增失败');
                }
            }else{
                $this->error($user_group_object->getError());
            }
        }else{
            //获取现有部门
            $map['status'] = array('egt', 0);
            $tree = new \Common\Util\Tree();           
            $all_menu =AdminMenu::treesub();
            
            //获取栏目分类权限节点（系统权限节点直接使用AdminController里的__ALL_MENU_LIST__）
            $this->assign('all_menu', $all_menu);           
            $this->meta_title = '新增权限';
            $this->display('add_edit');
        }
    }

    
    /**
     * 编辑部门
     * @author zhouxin
     */
    public function edit($id){
        if(IS_POST){        	
            $user_group_object = D('ManagerGroup');
            $_POST['pid']=0;
            $_POST['icon']='';            
            $_POST['menu_auth']= implode(',', I('post.menu_auth'));
            $data = $user_group_object->create();
            if($data){
            	$result=$user_group_object->save();  
            	if ($result>0 || $result ===0) {                
                    $this->success('更新成功', U('index'));
                }else{
                    $this->error('更新失败'.$result);
                }
            }else{
                $this->error($user_group_object->getError());
            }
        }else{
            //获取部门信息
            $info = D('ManagerGroup')->find($id);           
            $info['menu_auth'] = explode(',', $info['menu_auth']);
            //获取现有部门
            $map['status'] = array('egt', 0);
            $tree = new \Common\Util\Tree();
            $all_menu =AdminMenu::treesub();           
            //获取栏目分类权限节点（系统权限节点直接使用AdminController里的__ALL_MENU_LIST__）          
          	
            $this->assign('all_menu', $all_menu);           
            $this->assign('info', $info);
            $this->assign('meta_title', '编辑权限');
            $this->display('add_edit');
        }
    }
    
    public function delete(){
    	$ids    = I('request.ids');
    	if ($ids=="1")
    	{
    		$this->error('系统管理员权限不能删除!');
    		exit;
    	}   
    	//查找相关管理员 如果有,不能删除 	
    	$count=D('Manager')->where(['group'=>$ids])->count();
    	if ($count<1)
    	{
    		$this->error('该权限有正在在使用的用户不能删除!');
    		exit;
    	}
    	$map['id'] = $ids;
    	$result = D('ManagerGroup')->where($map)->delete();
    	if($result){
    		$this->success('删除成功，不可恢复！');
    	}else{
    		$this->error('删除失败');
    	}
    }
}
