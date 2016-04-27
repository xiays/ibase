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
class NoticeController extends ManageController{
    /**
     * 列表
     * @author zhouxin
     */
    public function index(){
        //搜索
                //搜索数据		
        $data_list = D('Notice')->page(!empty($_GET["p"])?$_GET["p"]:1, C('ADMIN_PAGE_ROWS'))->where($map)->order('id asc')->select();
        $page = new \Common\Util\Page(D('Notice')->where($map)->count(), C('ADMIN_PAGE_ROWS'));
        
        //使用Builder快速建立列表页面。
        $builder = new \Common\Engines\ListEngines();
        $builder->setMetaTitle('列表') //设置页面标题
        		->setCheck(false)
                ->addTopButton('addnew')  //添加新增按钮                  ->setCheck(false)
                ->addTableColumn('title', '标题')    
                ->addTableColumn('ctime', '发布时间','date')
                ->setTableDataListKey('id')      
                ->addTableColumn('right_button', '操作', 'btn')
                ->setTableDataList($data_list) //数据列表
                ->setTableDataPage($page->show()) //数据列表分页
                ->addRightButton('view')   //添加编辑按钮             
                ->display();
    }


    
    
     
     /**
     * 查看
     * @author zhouxin
     */
    public function view($id){
    	$object = D('Notice');
    	$info = $object->find($id);
    	$builder = new \Common\Engines\FormEngines();
    	$builder->setMetaTitle('公告查看') //设置页面标题
    	->setTemplate('_Engines/view')
                    ->addFormItemStyle('title', 'viewdiv', '公告', '',['rowend'=>0,'width'=>8])
                    ->addFormItemStyle('ctime', 'viewdate', '发布时间', '',['rowbegin'=>0])
                    ->addFormItemStyle('content', 'viewdiv', '公告内容', '',['width'=>12])
                    
    	->setFormData($info)
    	->display();
    }
}