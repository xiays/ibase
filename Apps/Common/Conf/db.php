<?php
// +----------------------------------------------------------------------
// | Ioacrm [ Simple Efficient Excellent ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016 http://www.ioacrm.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: zhouxin <http://www.ioacrm.com>
// +----------------------------------------------------------------------

/**
 * CoreThink数据库连接配置文件
 */
return array(
    //数据库配置
    'DB_TYPE'   => $_SERVER[ENV_PRE.'DB_TYPE'] ? : 'mysql', //数据库类型
    'DB_HOST'   => $_SERVER[ENV_PRE.'DB_HOST'] ? : '192.168.1.64', //服务器地址
    'DB_NAME'   => $_SERVER[ENV_PRE.'DB_NAME'] ? : 'ibase', //数据库名
    'DB_USER'   => $_SERVER[ENV_PRE.'DB_USER'] ? : 'root', //用户名
    'DB_PWD'    => $_SERVER[ENV_PRE.'DB_PWD']  ? : '123456',  //密码
    'DB_PORT'   => $_SERVER[ENV_PRE.'DB_PORT'] ? : '3306', //端口
    'DB_PREFIX' => $_SERVER[ENV_PRE.'DB_PREFIX'] ? : 'ibase_', //数据库表前缀
    'DB_PARAMS' => array(\PDO::ATTR_CASE => \PDO::CASE_NATURAL), //如果数据表字段名采用大小写混合需配置此项
);
