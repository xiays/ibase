<?php
// +----------------------------------------------------------------------
// | Ibase [ Simple Efficient Excellent ]
// +----------------------------------------------------------------------
// | Copyright (c) 2015. http://www.ioacrm.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: zhouxin
// +----------------------------------------------------------------------
namespace Common\Model;
use Think\Model;
/**
 * 消息模型
 * @author zhouxin
 */
class RecommendModel extends Model{
    /**
     * 自动验证规则
     * @author zhouxin
     */
    protected $_validate = array(        
          
    );

    /**
     * 自动完成规则
     * @author zhouxin
     */
    protected $_auto = array(        
    );
    
    /**
     * 用户性别
     * @author zhouxin 
     */
    public function type($id){
    	$list[1] = '文字';
    	$list[2] = '图片';
    	$list[3] = '图文';
    	$list[4] = '脚本 ';
    	$list[5] = '直接推荐';
    	return $id ? $list[$id] : $list;
    }   
   
}