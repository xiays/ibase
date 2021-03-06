<?php
// +----------------------------------------------------------------------
// | ibase [ Simple Efficient Excellent ]
// +----------------------------------------------------------------------
// | Copyright (c) 2014 http://www.corethink.cn All rights reserved.
// +----------------------------------------------------------------------
// |  Author: zhouxin <http://www.ioacrm.com>
// +----------------------------------------------------------------------
namespace Admin\Controller;
use Think\Controller;
/**
 * 消息控制器
 * @author zhouxin 
 */
class UserMessageController extends AdminController{
	
	public $datasource='UserMessage';

    /**
     * 默认方法
     * @param $type 消息类型
     * @author zhouxin 
     */
    public function index(){
        //搜索
        $keyword = I('keyword', '', 'string');
        $condition = array('like','%'.$keyword.'%');
        $map['id|title'] = array($condition, $condition,'_multi'=>true);

        //获取所有消息
        $map['status'] = array('egt', '0'); //禁用和正常状态
        $user = session('user_auth');
       	$map['to_uid']= $user['uid'];
       	$map['utime']=['elt',time()];
        $data_list = D('UserMessage')->page(!empty($_GET["p"])?$_GET["p"]:1, C('ADMIN_PAGE_ROWS'))
                                     ->order('sort desc,id desc')
                                     ->where($map)
                                     ->select();
        $page = new \Common\Util\Page(D('UserMessage')->where($map)->count(), C('ADMIN_PAGE_ROWS'));

        //使用Builder快速建立列表页面。
        $builder = new \Common\Engines\ListEngines();
        $builder->setMetaTitle('消息列表') //设置页面标题
                 ->addTopButton('addnew')  //添加新增按钮
//                 ->addTopButton('resume')  //添加启用按钮
//                 ->addTopButton('forbid')  //添加禁用按钮
//                 ->addTopButton('delete')  //添加删除按钮
        		->setCheck(false)
                ->setSearch('请输入ID/消息标题', U('index'))                
                ->addTableColumn('title', '消息')
                ->addTableColumn('ctime', '创建时间', 'time')                
                ->addTableColumn('status', '状态', 'status')
                ->addTableColumn('right_button', '操作', 'btn')
                ->setTableDataList($data_list) //数据列表
                ->setTableDataPage($page->show()) //数据列表分页
                ->addRightButton('delete') //添加删除按钮
                ->display();
    }

    /**
     * 新增消息
     * @author zhouxin 
     */
    public function add(){
        if(IS_POST){
            $user_message_object = D('UserMessage');
            $result = $user_message_object->sendMessage($_POST);
            if($result){
                 $this->success('发送消息成功', U('index'));
            }else{
                $this->error('发送消息失败'.$user_message_object->getError());
            }
        }else{
            //使用FormBuilder快速建立表单页面。
            $builder = new \Common\Engines\FormEngines();
            $builder->setMetaTitle('新增消息') //设置页面标题
                    ->setPostUrl(U('add')) //设置表单提交地址
                    ->addFormItem('to_uid', 'num', '消息收信用户', '收信用户ID')
                    ->addFormItem('title', 'textarea', '消息标题', '消息标题')
                    ->addFormItem('content', 'kindeditor', '消息内容', '消息内容')
                    ->addFormItem('sort', 'num', '排序', '用于显示的顺序')
                    ->display();
        }
    }

    /**
     * 编辑消息
     * @author zhouxin 
     */
    public function edit($id){
        if(IS_POST){
            $user_message_object = D('UserMessage');
            $data = $user_message_object->create();
            if($data){
                if($user_message_object->save()!== false){
                    $this->success('更新成功', U('index'));
                }else{
                    $this->error('更新失败');
                }
            }else{
                $this->error($user_message_object->getError());
            }
        }else{
            //使用FormBuilder快速建立表单页面。
            $builder = new \Common\Engines\FormEngines();
            $builder->setMetaTitle('编辑消息') //设置页面标题
                    ->setPostUrl(U('edit')) //设置表单提交地址
                    ->addFormItem('id', 'hidden', 'ID', 'ID')
                    ->addFormItem('title', 'textarea', '消息标题', '消息标题')
                    ->addFormItem('content', 'kindeditor', '消息内容', '消息内容')
                    ->addFormItem('sort', 'num', '排序', '用于显示的顺序')
                    ->setFormData(D('UserMessage')->find($id))
                    ->display();
        }
    }

}
