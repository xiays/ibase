<?php
// +----------------------------------------------------------------------
// | Ioacrm [ Simple Efficient Excellent ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016 http://www.ioacrm.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: zhouxin <http://www.ioacrm.com>
// +----------------------------------------------------------------------
namespace Manage\Controller;
use Think\Controller;
use Think\Storage;
/**
 * 后台上传控制器
 * @author zhouxin ioacrm
 */
class PublicUploadController extends Controller{
    

    /**
     * 上传
     * @author zhouxin ioacrm
     */
    public function upload(){
        exit(D('PublicUpload')->myupload());
    }
    
    /**
     * 上传
     * @author zhouxin ioacrm
     */
    public function myupload(){
    	exit(D('PublicUpload')->myupload());
    }

    /**
     * KindEditor编辑器下载远程图片
     * @author zhouxin ioacrm
     */
    public function downremoteimg(){
        exit(D('PublicUpload')->downremoteimg());
    }

    /**
     * KindEditor编辑器文件管理
     * @author zhouxin ioacrm
     */
    public function fileManager(){
        exit(D('PublicUpload')->fileManager());
    }
}
