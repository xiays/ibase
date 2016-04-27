<?php
// +----------------------------------------------------------------------
// | Ioacrm [ Simple Efficient Excellent ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016 http://www.ioacrm.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: zhouxin <http://www.ioacrm.com>
// +----------------------------------------------------------------------
namespace Common\Resource;
/**
 * 后台地址白名单
 * @author zhouxin
 */
class ManageFreePage {
	
	/**
	 * 数据必需保证顺序
	 * @return multitype:multitype:number string
	 */
	public static function  all()
	{
		$configmax=17;
		$config=array(
				['id'=>0,'url'=>'manage/home/index'],
				['id'=>0,'url'=>'manage/index/login'],
				['id'=>0,'url'=>'manage/index/login'],
				['id'=>0,'url'=>'manage/index/logout'],
				['id'=>0,'url'=>'manage/UserMessage/index'],
				['id'=>0,'url'=>'manage/UserMessage/index'],
				['id'=>0,'url'=>'manage/UserMessage/index'],				
		);		
		return $config;		
	}
	
	public static function check($gruop){
		$menu_auth=','.$gruop['menu_auth'].',';
		$url = MODULE_NAME . '/' . CONTROLLER_NAME . '/' . ACTION_NAME;
		$url = strtolower($url);		
		$config = self::all();
		foreach ( $config as $info ) {
			if (strtolower($url) == strtolower($info['url']) || ($info['type']=='1' && strpos(strtolower($url),strtolower($info['url']))!==false ))
			{
				if ($info['id']==0)
					return true;
				//判断模糊匹配
				if (strpos($menu_auth, ','.(string)$info['id'].',')!==false)
				{
					return true;
				}
			}
		}
		return false;
	}
	
	
}