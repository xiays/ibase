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
class DocumentModel extends Model{
    /**
     * 自动验证规则
     * @author zhouxin
     */
    protected $_validate = array(     
    	array('cid', 'require', '分类不能为空', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),          
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
     * 用户类型
     * @author zhouxin 
     */
    public function status($id){
    	$list[1] = '开启';
    	$list[0] = '关闭';
    	return $id ? $list[$id] : $list;
    }
}