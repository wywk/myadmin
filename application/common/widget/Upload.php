<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2017 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: matengfei <matengfei2000@gmail.com>
// +----------------------------------------------------------------------

namespace app\common\widget;

use think\Controller;

/**
 * 上传插件
 *
 * @author	matengfei
 * @date    2017-08-01
 * @version 1.0
 */
class Upload extends Controller {
	
	/**
	 * 上传图片
	 * 
	 */
	public function image($filename='image'){
		$this->assign('filename', $filename);
		$this->view->engine->layout(false);
		return $this->fetch('upload/image');
	}
	
	/**
	 * 上传文件
	 * @date: 2017-8-11
	 * @author: cc
	 */
	public function file($upload_container_id = 'file', $file_input_name = 'attach_path', $manager_id = 'manager', $view_attach_pk_value = '', $attach_field = ''){
	    $config = config('file_upload');
	    $allow_ext = $config['exts'];
	    foreach ($allow_ext as &$val){
	        $val = '.' . $val;
	    }
	    $allow_ext = implode(',', $allow_ext);
	    $maxsize = $config['maxSize'];
	    //允许的文件后缀
	    $this->assign('allow_ext', $allow_ext);
	    //最大的文件大小
	    $this->assign('maxsize', $maxsize);
	    //文件上传的容器id
	    $this->assign('upload_container_id', $upload_container_id);
	    //存放上传文件路径值input框name
	    $this->assign('file_input_name', $file_input_name);
	    //查看附件按钮的id值
	    $this->assign('manager_id', $manager_id);
	    //获取附件所需的主键值
	    $this->assign('view_attach_pk_value', $view_attach_pk_value);
	    //需要查看的附件在数据库里的字段名
	    $this->assign('attach_field', $attach_field);
	    return $this->fetch('upload/file');
	}
}