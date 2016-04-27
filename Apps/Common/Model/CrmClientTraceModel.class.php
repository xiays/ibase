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
class CrmClientTraceModel extends Model{
    /**
     * 自动验证规则
     * @author zhouxin
     */
    protected $_validate = array(        
    		array('intent', 'require', '客户意向不能为空', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
    		array('type', 'require', '跟进类型不能为空', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
    		array('uid', 'require', '用户不能为空', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
    		array('clientid', 'require', '客户不能为空', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
    );

    /**
     * 自动完成规则
     * @author zhouxin
     */
    protected $_auto = array(   
    	array('ctime', NOW_TIME, self::MODEL_INSERT),    	
    );
    
    /**
     * 用户性别
     * @author zhouxin
     */
    public function type($id){
    	$list[1] = '电话联系';
    	$list[2] = '上门服务';
    	$list[3] = 'QQ联系';
    	$list[4] = 'Email联系';
    	$list[5] = '其它方式';
    	return $id ? $list[$id] : $list;
    }
   
}