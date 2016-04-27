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
class ManageMenu {
	
/**
	 * 数据必需保证顺序
	 * @return multitype:multitype:number string
	 */
	public static function  all()
	{
		$idmax=10;
		$config=array(
				['id'=>1,'pid'=>0,'name'=>'首页','level'=>1,'url'=>'manage/index/index','ico'=>'glyphicon-home'],
				['id'=>2,'pid'=>0,'name'=>'管理','level'=>1,'url'=>'manage/index/index','ico'=>'glyphicon-th','showtop'=>1],
				['id'=>3,'pid'=>2,'name'=>'消息','level'=>2,'url'=>'manage/UserMessage/index','ico'=>'glyphicon glyphicon-user','showtop'=>1],
				['id'=>4,'pid'=>-1,'name'=>'用户','level'=>2,'url'=>'manage/User/index','ico'=>'glyphicon glyphicon-list','sub'=>'default'],						 
				['id'=>7,'pid'=>0,'name'=>'客户管理','level'=>1,'url'=>'manage/CrmClient/index','ico'=>'glyphicon-th-list','sub'=>''],
					['id'=>8,'pid'=>7,'name'=>'客户信息','level'=>2,'url'=>'manage/CrmClient/index','ico'=>'glyphicon-list-alt','sub'=>''],
					['id'=>9,'pid'=>7,'name'=>'联系人信息','level'=>2,'url'=>'manage/CrmContact/index','ico'=>'glyphicon-user','sub'=>''],
					['id'=>10,'pid'=>7,'name'=>'跟单记录','level'=>2,'url'=>'manage/CrmClientTrace/index','ico'=>'glyphicon-list','sub'=>''],
					['id'=>11,'pid'=>7,'name'=>'项目管理','level'=>2,'url'=>'manage/Project/index','ico'=>'glyphicon-flag','sub'=>''],
					['id'=>12,'pid'=>7,'name'=>'合同管理','level'=>2,'url'=>'manage/CrmContract/index','ico'=>'glyphicon-book','sub'=>''],
		);
		return $config;		
	}
	
	public static function  quick()
	{
		$idmax=10;
		$config=array(
				['id'=>1,'name'=>'邮件','label'=>'email','url'=>'manage/index/index','ico'=>'icon1.png'],
				['id'=>2,'name'=>'公告','label'=>'diary','url'=>'manage/notice/index','ico'=>'icon2.png'],
				['id'=>3,'name'=>'总结','label'=>'report','url'=>'manage/UserMessage/index','ico'=>'icon3.png'],
				['id'=>4,'name'=>'日程','label'=>'calendar','url'=>'manage/User/index','ico'=>'icon4.png'],
				['id'=>5,'name'=>'新闻','label'=>'"article"','url'=>'manage/CrmClient/index','ico'=>'icon5.png'],
				['id'=>6,'name'=>'公文','label'=>'officialdoc','url'=>'manage/CrmClient/index','ico'=>'icon6.png'],
				['id'=>7,'name'=>'微博','label'=>'weibo','url'=>'manage/CrmContact/index','ico'=>'icon7.png'],
				['id'=>8,'name'=>'任务指派','label'=>'assignment','url'=>'manage/CrmClientTrace/index','ico'=>'icon8.png'],
				['id'=>9,'name'=>'招聘','label'=>'email','url'=>'manage/Project/index','ico'=>'icon9.png'],
				['id'=>10,'name'=>'合同管理','label'=>'email','url'=>'manage/CrmContract/index','ico'=>'icon10.png'],
		);
		return $config;
	}
	
	public static function  getquickbyid($id)
	{
		$config=self::quick();
		$newconfig=array();
		foreach ($config as $c)
		{
			if ($c['id']==$id)
				return $c;
		}
		return null;
	}
	public static function  defaultsub()
	{
		$config[1]=['id'=>1,'name'=>'新增','act'=>'add'];
		$config[2]=['id'=>2,'name'=>'修改','act'=>'edit'];
		$config[3]=['id'=>3,'name'=>'删除','act'=>'delete'];
		$config[4]=['id'=>4,'name'=>'查询','act'=>'view']	;			
		
		return $config;
	}
	
	public static function  allsub()
	{		
		$config[10]=['id'=>10,'name'=>'自定义1','act'=>'add'];
		$config[11]=['id'=>11,'name'=>'自定义2','act'=>'add'];
		$config[12]=['id'=>12,'name'=>'自定义3','act'=>'add'];
		$config[13]=['id'=>13,'name'=>'自定义4','act'=>'add'];
		return $config;
	}
	
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
	
	public static function  current()
	{
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
	
	public static function  getpath($info)
	{
		$newconfig=array();
		$newconfig[]=$info;
		if ($info['pid']!=0)
		{
			$info1=self::getid($info['pid']);
			$newconfig[]=$info1;
			if ($info1['pid']!=0)
			{
				$info2=self::getid($info1['pid']);
				$newconfig[]=$info2;					
			}
		}
		return array_reverse($newconfig);
	}
			
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
			if ($c['level']==4)
			{
				if ($c['pid']==$t2['id'])
				{
					$c=self::getsub($c);
					$t2=$c;
					$i=count($newconfig[$m]['child'])-1;
					$j=count($newconfig[$m]['child'][$i]['child'])-1;
					$newconfig[$m]['child'][$i]['child'][$j]['child'][]=$c;
				}
			}
				
		}
		//$newconfig[]=$t;
		return $newconfig;
	}
	/**
	 * 获取 当前地址的二\三级菜单
	 * @param unknown $info
	 * @return Ambigous <multitype:multitype: , multitype:, \Common\Resource\multitype:multitype:number, multitype:multitype:number string  >
	 */
	public static function  tree2($info)
	{
		$info1=$info;
		$id=$info['pid'];
		if ($info['level']==3)
		{
			$info1=self::getid($info['pid']);
			$id=$info1['pid'];
		}		
		$config=self::all();
		$newconfig=array();
		$t=array();
		$t1=array();
		$t2=array();
		foreach ($config as $c)
		{
			$m=count($newconfig)-1;
			if ($c['pid']==$id)
			{
				if ($info1['id']==$c['id'] || $info['id']==$c['id'])
				{
					$c['active']=1;
				}
				$c['child']=array();
				$t=$c;
				$newconfig[]=$t;
			} 
			if ($c['level']==3)
			{
				if ($c['pid']==$t['id'])
				{
					if ($info['id']==$c['id'])
					{
						$c['active']=1;
					}
					$t1=$c;
					$newconfig[$m]['child'][]=$c;
				}
			}			
		}
		return $newconfig;
	}
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
			if ($c['level']==4)
			{
				if ($c['pid']==$t2['id'])
				{
					$t2=$c;
					$i=count($newconfig[$m]['child'])-1;
					$j=count($newconfig[$m]['child'][$i]['child'])-1;
					$newconfig[$m]['child'][$i]['child'][$j]['child'][]=$c;
				}
			}	
			
		}
		//$newconfig[]=$t;
		return $newconfig;
	}
}