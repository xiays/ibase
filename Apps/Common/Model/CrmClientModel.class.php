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
class CrmClientModel extends Model{
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
    		array('ctime', NOW_TIME, self::MODEL_INSERT),
    		array('utime', NOW_TIME, self::MODEL_BOTH),
    );
   
    /**
     * 客户共享
     * @author zhouxin 
     */
    public function openshare($id){
    	$list[1] = '是';
    	$list[0] = '否';
    	return $id ? $list[$id] : $list;
    }
    
    /**
     * 是否有联系人
     * @author zhouxin
     */
    public function iscontract($id){
    	$list[1] = '是';
    	$list[0] = '否';
    	return $id ? $list[$id] : $list;
    }
    
    /**
     * 客户意向
     * @author zhouxin
     */
    public function intent($id){
    	$list[1] = '有一定意向';
    	$list[2] = '意向明确';
    	$list[3] = '不明确';
    	$list[4] = '未知';
    	return $id ? $list[$id] : $list;
    }
    
    /**
     * 状态
     * @author zhouxin
     */
    public function state($id){
    	$list[1] = '开启';
    	$list[0] = '关闭';
    	return $id ? $list[$id] : $list;
    }
    
    /**
     * 客户级别
     * @author zhouxin
     */
    public function level($id){
    	$list[1] = '★';
    	$list[2] = '★★';
    	$list[3] = '★★★';
    	$list[4] = '★★★★';
    	$list[5] = '★★★★★';
    	return $id ? $list[$id] : $list;
    }
}