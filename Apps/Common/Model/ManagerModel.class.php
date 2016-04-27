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
class ManagerModel extends Model{
    /**
     * 自动验证规则
     * @author zhouxin
     */
    protected $_validate = array(        
    		array('password', 'require', '密码不能为空', self::MUST_VALIDATE, 'regex', self::MODEL_INSERT),
    		//array('password', '6,30', '密码长度为6-30位', self::MUST_VALIDATE, 'length', self::MODEL_INSERT),
    		//array('password', '/(?!^(\d+|[a-zA-Z]+|[~!@#$%^&*()_+{}:"<>?\-=[\];\',.\/]+)$)^[\w~!@#$%^&*()_+{}:"<>?\-=[\];\',.\/]+$/', '密码至少由数字、字符、特殊字符三种中的两种组成', self::MUST_VALIDATE, 'regex', self::MODEL_INSERT),
    		//array('repassword', 'password', '两次输入的密码不一致', self::EXISTS_VALIDATE, 'confirm', self::MODEL_INSERT),
    		
    		//验证用户名
    		array('username', 'require', '用户名不能为空', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
    		array('username', '3,32', '用户名长度为1-32个字符', self::MUST_VALIDATE, 'length', self::MODEL_BOTH),
    		array('username', '', '用户名被占用', self::MUST_VALIDATE, 'unique', self::MODEL_BOTH),
    		//array('username', 'checkIP', '注册太频繁请稍后再试', self::MUST_VALIDATE, 'callback', self::MODEL_INSERT), //IP限制
    		array('username', 'checkUsername', '该用户名禁止使用', self::MUST_VALIDATE, 'callback', self::MODEL_BOTH), //用户名敏感词检测
    		array('username', '/^(?!_)(?!\d)(?!.*?_$)[\w\一-\龥]+$/', '用户名只可含有汉字、数字、字母、下划线且不以下划线开头结尾，不以数字开头！', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
    		
    );

    /**
     * 自动完成规则
     * @author zhouxin
     */
    protected $_auto = array(      
    		array('birthday', 'strtotime', self::MODEL_BOTH, 'function'),
    		array('group', '0', self::MODEL_INSERT),
    		array('ctime', NOW_TIME, self::MODEL_INSERT),
    		array('utime', NOW_TIME, self::MODEL_BOTH),
    		array('sort', '0', self::MODEL_INSERT),
    		array('status', '1', self::MODEL_INSERT),
    		array('reg_type', '', self::MODEL_UPDATE, 'ignore'),
    		array('email', '', self::MODEL_UPDATE, 'ignore'),
    		array('mobile', '', self::MODEL_UPDATE, 'ignore'),    		
    		array('group', '', self::MODEL_UPDATE, 'ignore'),
    		array('realname', '', self::MODEL_BOTH, 'ignore'), 
    		
    );
    
    /**
     * 用户类型
     * @author zhouxin 
     */
    public function user_type($id){
    	$list[0] = '总部';
    	$list[1] = '分公司';
    	return $id ? $list[$id] : $list;
    }
    
    
    /**
     * 用户类型
     * @author zhouxin 
     */
    public function state($id){
    	$list[1] = '开启';
    	$list[0] = '关闭';
    	return $id ? $list[$id] : $list;
    }
    
    /**
     * 用户登录
     * @author zhouxin 
     */
    public function login($username, $password, $map){
    	//去除前后空格
    	$username = trim($username);    
    	//匹配登录方式
    	if(preg_match("/^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/", $username)){
    		$map['email'] = array('eq', $username); //邮箱登陆
    	}elseif(preg_match("/^1\d{10}$/", $username)){
    		$map['mobile'] = array('eq', $username); //手机号登陆
    	}else{
    		$map['username'] = array('eq', $username); //用户名登陆
    	}
    	$map['status'] = array('eq', 1);
    	$user = $this->where($map)->find(); //查找用户
    	
    	if(!$user){
    		$this->error = '用户不存在或被禁用！';
    	}else{
    		$login_salt=$user['login_salt'];
    		if(md5(md5($password).$login_salt) !== $user['password']){
    			$this->error = '密码错误！';
    		}else{
    			//更新登录信息
    			$data = array(
    					'id'              => $user['id'],
    					'login'           => array('exp', '`login`+1'),
    					'last_login_time' => NOW_TIME,
    					'last_login_ip'   => get_client_ip(1),
    			);
    			$this->save($data);
    			$this->autoLogin($user);
    			return $user['id'];
    		}
    	}
    	return false;
    }
    
    public function logininfo(){
    	return session('manager_auth');
    }
    
    /**
     * 设置登录状态
     * @author zhouxin 
     */
    public function autoLogin($user){
    	//记录登录SESSION和COOKIES
    	$auth = array(
    			'uid'             => $user['id'],
    			'username'        => $user['username'],
    			'avatar'          => $user['avatar'],
    			'last_login_time' => $user['last_login_time'],
    			'group' => $user['group'],
    			'last_login_ip'   => get_client_ip(1),
    	);
    	session('manager_auth', $auth);
    	session('manager_auth_sign', $this->dataAuthSign($auth));
    }
    
    /**
     * 用户名敏感词检测
     * @return boolean ture 正常，false 敏感词
     * @author zhouxin 
     */
    protected function checkUsername(){
    	$deny = explode(',', C('SENSITIVE_WORDS'));
    	foreach($deny as $k=> $v){
    		if(stristr(I('post.username'), $v)){
    			return false;
    		}
    	}
    	return true;
    }
    
    /**
     * 检测同一IP注册是否频繁
     * @return boolean ture 正常，false 频繁注册
     * @author zhouxin 
     */
    protected function checkIP(){
    	$limit_time = C('LIMIT_TIME_BY_IP');
    	$map['ctime'] = array('GT', time()-(int)$limit_time);
    	$reg_ip = $this->where($map)->getField('reg_ip', true);
    	$key = array_search(get_client_ip(1), $reg_ip);
    	if($reg_ip && $key !== false){
    		return false;
    	}
    	return true;
    }
    
    
    /**
     * 数据签名认证
     * @param  array  $data 被认证的数据
     * @return string       签名
     * @author zhouxin 
     */
    public function dataAuthSign($data) {
    	//数据类型检测
    	if(!is_array($data)){
    		$data = (array)$data;
    	}
    	ksort($data); //排序
    	$code = http_build_query($data); //url编码并生成query字符串
    	$sign = sha1($code); //生成签名
    	return $sign;
    }
    
    /**
     * 检测用户是否登录
     * @return integer 0-未登录，大于0-当前登录用户ID
     * @author zhouxin 
     */
    public function isLogin(){
    	$user = session('manager_auth');
    	if (empty($user)) {
    		return 0;
    	}else{
    		return session('manager_auth_sign') == $this->dataAuthSign($user) ? $user['uid'] : 0;
    	}
    }
   
}