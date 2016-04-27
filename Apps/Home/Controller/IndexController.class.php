<?php
// +----------------------------------------------------------------------
// | Ioacrm [ Simple Efficient Excellent ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016 http://www.ioacrm.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: zhouxin <http://www.ioacrm.com>
// +----------------------------------------------------------------------
namespace Home\Controller;
use Think\Controller;
use Common\Controller\CommonController;
/**
 * 后台默认控制器
 * @author zhouxin ioacrm
 */
class IndexController extends CommonController{
	public function index(){		
		$this->redirect('/manage/');
		exit;
	}
}