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
class CrmContactModel extends Model{
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
     * 用户性别
     * @author zhouxin
     */
    public function sex($id){    	
    	$list[1] = '男';
    	$list[2] = '女';
    	return $id ? $list[$id] : $list;
    }
   
}