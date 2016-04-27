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
 * 后台菜单
 * @author zhouxin
 */
class AdminMenu {
	
	/**
	 * 数据必需保证顺序
	 * @return multitype:multitype:number string
	 */
	public static function  all()
	{
		$configmax=19;
		$config=array(
				['id'=>1,'pid'=>0,'name'=>'首页','level'=>1,'url'=>'admin/home/index','ico'=>'glyphicon-home'],
					['id'=>3,'pid'=>1,'name'=>'后台首页','level'=>2,'url'=>'admin/home/index','ico'=>'glyphicon-paperclip'],
				['id'=>2,'pid'=>0,'name'=>'系统','level'=>1,'url'=>'admin/SystemConfig/group','ico'=>'glyphicon glyphicon-cog'],
					['id'=>3,'pid'=>2,'name'=>'系统配置','level'=>2,'url'=>'admin/SystemConfig/group','ico'=>'glyphicon-paperclip'],				
				['id'=>4,'pid'=>0,'name'=>'用户','level'=>1,'url'=>'admin/UserMessage/index','ico'=>'glyphicon-user'],
					['id'=>10,'pid'=>4,'name'=>'用户','level'=>2,'url'=>'admin/User/index','ico'=>'glyphicon-user','sub'=>'default'],
					['id'=>12,'pid'=>4,'name'=>'用户部门','level'=>2,'url'=>'admin/Department/index','ico'=>'glyphicon-align-justify','sub'=>''],
					['id'=>11,'pid'=>4,'name'=>'用户权限','level'=>2,'url'=>'admin/UserGroup/index','ico'=>'glyphicon-lock','sub'=>''],					
					['id'=>14,'pid'=>4,'name'=>'用户职位','level'=>2,'url'=>'admin/Position/index','ico'=>'glyphicon-lock','sub'=>''],
					['id'=>13,'pid'=>4,'name'=>'管理员','level'=>2,'url'=>'admin/Manager/index','ico'=>'glyphicon-user','sub'=>''],
					['id'=>14,'pid'=>4,'name'=>'管理员权限','level'=>2,'url'=>'admin/ManagerGroup/index','ico'=>'glyphicon-lock','sub'=>''],
					
				['id'=>5,'pid'=>0,'name'=>'网站管理','level'=>1,'url'=>'admin/recommend/index','ico'=>'glyphicon-globe'],
					['id'=>18,'pid'=>5,'name'=>'推荐位','level'=>2,'url'=>'admin/recommend/index','ico'=>'glyphicon-indent-right','sub'=>''],
					['id'=>17,'pid'=>5,'name'=>'栏目管理','level'=>2,'url'=>'admin/category/index','ico'=>'glyphicon-list-alt','sub'=>''],
					['id'=>16,'pid'=>5,'name'=>'文章管理','level'=>2,'url'=>'admin/document/index','ico'=>'glyphicon-book','sub'=>''],
					['id'=>19,'pid'=>5,'name'=>'公告管理','level'=>2,'url'=>'admin/notice/index','ico'=>'glyphicon-book','sub'=>''],
				
		);		
		return $config;		
	}
	
	/**
	 * 获取默认子菜单
	 * @return 
	 */
	public static function  defaultsub()
	{
		$config[1]=['id'=>1,'name'=>'新增','act'=>'add'];
		$config[2]=['id'=>2,'name'=>'修改','act'=>'edit'];
		$config[3]=['id'=>3,'name'=>'删除','act'=>'delete'];
		$config[4]=['id'=>4,'name'=>'查询','act'=>'view']	;		
		return $config;
	}
	
	/**
	 * 获取自定义
	 * @return 
	 */
	public static function  allsub()
	{	
		$config[10]=['id'=>10,'name'=>'本部门信息','act'=>'']	;
		$config[11]=['id'=>11,'name'=>'本人信息','act'=>'']	;
		$config[12]=['id'=>12,'name'=>'自定义3','act'=>'add'];
		$config[13]=['id'=>13,'name'=>'自定义4','act'=>'add'];
		return $config;
	}
	
	/**
	 * 根据父级ID获取菜单信息
	 * @param  $id
	 * @return 
	 */
	public static function  parent($id)
	{
		$config=self::all();
		$newconfig=array();
		foreach ($config as $c)
		{
			if ($c['pid']==$id)
				$newconfig[]=$c;				
		}
		return $newconfig;
	}
	/**
	 * 获取当前菜单信息
	 * @return 
	 */
	public static function  current()
	{
		//按地址直接查找
		$url=  MODULE_NAME.'/'.CONTROLLER_NAME.'/'.ACTION_NAME;
		$url=strtolower($url);
		$config=self::all();
		$newconfig=null;
		foreach (array_reverse($config) as $c)
		{
			if (strtolower($c['url'])==$url)
			{
				$newconfig=$c;
				return $newconfig;				
			}
		}
		//按控制器查找
		$url= MODULE_NAME.'/'.CONTROLLER_NAME.'/index';
		$url=strtolower($url);
		foreach (array_reverse($config) as $c)
		{
			if (strtolower($c['url'])==$url)
			{
				$newconfig=$c;
				return $newconfig;
			}
		}
		//按白名单查找
		$url=  MODULE_NAME.'/'.CONTROLLER_NAME.'/'.ACTION_NAME;
		$url=strtolower($url);
		$config=AdminFreePage::all();
		foreach ( $config as $info ) {
			if (strtolower($url) == strtolower($info['url']) || ($info['type']=='1' && strpos(strtolower($url),strtolower($info['url']))!==false ))
			{
				$newconfig=self::getid($info['id']);				
				return $newconfig;
			}
		}
		return $newconfig;
	}
	/**
	 * 根据当前菜单 获取路径
	 * @param  $info
	 * @return 
	 */
	public static function  currentname($info)
	{
		$name=$info['name'];	
		$config=self::all();
		if ($info['pid']==0)
			return $name;
		$l1=-1;
		$l2=-1;
		$l3=-1;
		foreach (array_reverse($config) as $c)
		{
			if ($c['id']==$info['pid'])
			{
				$name=$c['name'].' / '.$name;
				$l1=$c['id'];
			}else
			if ($c['id']==$l1)
			{						
				$name=$c['name'].' / '.$name;
				$l2=$c['id'];
			}else
			if ($c['id']==$l2)
			{
				$name=$c['name'].' / '.$name;
				$l3=$c['id'];
			}else
			if ($c['id']==$l3)
			{
				$name=$c['name'].' / '.$name;				
			}
			
		}
		return $name;
	}
	
	/**
	 * 获取信息
	 * @param unknown $id
	 * @return 
	 */
	public static function  getid($id)
	{
		$newconfig=flase;
		$config=self::all();
		$newconfig=flase;
		foreach ($config as $c)
		{
			if ($c['id']==$id)
			{
				$newconfig=$c;
				break;
			}
		}
		return $newconfig;
	}
	public static function  getmenu($auth,$list)
	{		
		$listcopy =array();
		foreach ($list as $info)
		{
			$listcopy[]=$info;
		}
		if ($auth['id']==1)
			return $list;
		
		$menu_auth=','.$auth['menu_auth'].',';
		$retrunlist=array();		
		foreach ($listcopy as $info)
		{			
			if (strpos($menu_auth, ','.$info['id'].',')!==false)			
			{
				
				foreach ($info['child'] as $k1=>$info1)
				{				
					if (strpos($menu_auth, ','.$info1['id'].',')===false)
					{						
						unset($info['child'][$k1]);
						continue;												
					}
					foreach ($info1['child'] as $k2=>$info2)
					{
						if (strpos($menu_auth, ','.$info2['id'].',')===false)
						{
							unset($info['child'][$k1]['child'][$k2]);
						}
					}
				}				
				$retrunlist[]=$info;
			}
		}
		return $retrunlist;
	}
	/**
	 * 根据菜单获取子菜单项目
	 * @param unknown $info
	 * @return 
	 */
	public static function  getsub($info)
	{		
		$info['child']=array();
		if ($info['sub']==null || $info['sub']=='no')
		{
			return $info;
		}
		$defultsub=[];
		foreach (self::defaultsub() as $defultinfo)
		{
			$definfo['id']=$info['id'].'_'.$defultinfo['id'];
			$definfo['name']=$defultinfo['name'];
			$definfo['act']=$defultinfo['act'];
			$definfo['type']='1';
			$defultsub[$defultinfo['id']]=$definfo;
		}
		if ($info['sub']=='default')
		{			
			$info['child']=$defultsub;
			return $info;
		}
		$tempsuball=[];
		foreach (self::allsub() as $defultinfo)
		{
			$definfo['id']=$info['id'].'_'.$defultinfo['id'];
			$definfo['name']=$defultinfo['name'];
			$definfo['act']=$defultinfo['act'];
			$definfo['type']='1';
			$tempsuball[$defultinfo['id']]=$definfo;
		}
		$suball=$defultsub+$tempsuball;		
		foreach (explode(',', $info['sub']) as $sid)
		{
			if (isset($suball[$sid]))
			{
				$info['child'][]=$suball[$sid];
			}
		}				
		return $info;
	}
	
	/**
	 * 获取菜单权限
	 * @return Ambigous 
	 */
	public static function  treesub()
	{
		$config=self::all();
		$newconfig=array();
		$t=array();
		$t1=array();
		$t2=array();
		foreach ($config as $c)
		{
			$m=count($newconfig)-1;
			if ($c['pid']==0)
			{
				$c=self::getsub($c);
				$t=$c;
				$newconfig[]=$t;
			}
			if ($c['level']==2)
			{
				if ($c['pid']==$t['id'])
				{
					$c=self::getsub($c);
					$t1=$c;
					$newconfig[$m]['child'][]=$c;
				}
			}
			if ($c['level']==3)
			{
				if ($c['pid']==$t1['id'])
				{
					$c=self::getsub($c);
					$t2=$c;
					$i=count($newconfig[$m]['child'])-1;
					$newconfig[$m]['child'][$i]['child'][]=$c;
				}
			}			
		}
		//$newconfig[]=$t;
		return $newconfig;
	}
	/**
	 * 获取菜单树
	 * @return Ambigous
	 */
	public static function  tree()
	{
		$config=self::all();
		$newconfig=array();
		$t=array();
		$t1=array();
		$t2=array();
		foreach ($config as $c)
		{
			$m=count($newconfig)-1;
			if ($c['pid']==0)
			{
				$c['child']=array();
				$t=$c;
				$newconfig[]=$t;
			} 
			if ($c['level']==2)
			{
				if ($c['pid']==$t['id'])
				{
					$t1=$c;
					$newconfig[$m]['child'][]=$c;
				}
			}
			if ($c['level']==3)
			{
				if ($c['pid']==$t1['id'])
				{
					$t2=$c;
					$i=count($newconfig[$m]['child'])-1;
					$newconfig[$m]['child'][$i]['child'][]=$c;
				}
			}						
		}		
		return $newconfig;
	}
	
}
