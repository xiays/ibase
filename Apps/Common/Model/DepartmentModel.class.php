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
class DepartmentModel extends Model{
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
    public function isbranch($id){
    	$list[1] = '是';
    	$list[0] = '否';
    	return $id ? $list[$id] : $list;
    }
    
    
    public function tree()
    {
    	$all=$this->select();
    	$temp=array();
    	foreach ($all as $info)
    	{
    		if ($info['pid']==0)
    		{
    			$temp[]=$info;
    			foreach ($all as $info1)
    			{
    				if ($info1['pid']===$info['id'])
    				{
    					$info1['title']='　　┝'.$info1['title'];
    					$temp[]=$info1;
    					foreach ($all as $info2)
    					{
    						if ($info2['pid']===$info1['id'])
    						{
    							$info2['title']='　　　　┝'.$info2['title'];
    							$temp[]=$info2;
    						}
    					}
    				}
    			}
    		}
    	}
    	return $temp;
    }
}