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
class CrmContractModel extends Model{
    /**
     * 自动验证规则
     * @author zhouxin
     */
    protected $_validate = array(   
    		array('uid', 'require', '用户不能为空', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
    		array('clientid', 'require', '客户不能为空', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
    		array('project_id', 'require', '项目不能为空', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
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
     * 消息类型
     * @author zhouxin
     */
    public function state($id){
    	$list[1] = '支付定金';
    	$list[2] = '全额支付';
    	$list[3] = '退款';
    	return $id ? $list[$id] : $list;
    }
    
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