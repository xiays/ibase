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
class NoticeModel extends Model{
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
   
}