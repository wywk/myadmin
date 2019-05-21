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

namespace app\common\controller;

/**
 * 上传管理
 *
 * @author	matengfei
 * @date    2017-08-01
 * @version 1.0
 */
class Upload extends Base {
	
	/**
	 * 上传文件
	 * 
	 */
	public function upload($name='file'){
		$upload_type = input('?get.type') ? input('get.type', 'images', 'trim') : 'images';
		$config      = $this->$upload_type();
		// 获取表单上传文件 例如上传了001.jpg
		$file = request()->file($name);
		$temp_path = UPLOAD_PATH . 'temp';
		$validate = [
			'size' => $config['maxSize'], //20M
			'ext'  => $config['exts'],
		];
		$info = $file->validate($validate)->move($temp_path, true, false);
		if ($info) {
			$data = [];
			$data['file'] = $info->getPathname();
			$data['file'] = str_replace(UPLOAD_PATH, '', $data['file']);
			$data['file'] = str_replace('\\', '/', $data['file']);
			$data['file_url'] = UPLOAD_URL . $data['file'];
			return $this->success('上传成功！', '', $data);
		} else {
			$data = [];
			$data['file'] = '';
			$data['file_url'] = '';
			$msg = $file->getError();
			return $this->error($msg, '', $data);
		}
	}
	
	/**
	 * 裁剪文件
	 *
	 */
	public function crop($name='Filedata'){
		$upload_type = input('?get.type') ? input('get.type', 'images', 'trim') : 'images';
		$config      = $this->$upload_type();
		// 获取表单上传文件 例如上传了001.jpg
		$file = request()->file($name);
		$temp_path = UPLOAD_PATH . 'temp';
		$validate = [
			'size' => $config['maxSize'], //20M
			'ext'  => $config['exts'],
		];
		$info = $file->validate($validate)->move($temp_path, true, false);
		if ($info) {
			$data = [];
			$data['file'] = $info->getPathname();
			$data['file'] = str_replace(UPLOAD_PATH, '', $data['file']);
			$data['file'] = str_replace('\\', '/', $data['file']);
			$data['file_url'] = UPLOAD_URL . $data['file'];
			$return = [];
			$return['code'] = 1;
			$return['msg']  = '上传成功！';
			$return['data'] = $data;
			return $return;
		} else {
			$data = [];
			$data['file'] = '';
			$data['file_url'] = '';
			$msg = $file->getError();
			$return = [];
			$return['code'] = 1;
			$return['msg']  = $msg;
			$return['data'] = $data;
			return $return;
		}
	}
	
	/**
	 * 图片上传
	 * 
	 */
	protected function images(){
		return config('img_upload');
	}
	
	/**
	 * 文件上传
	 * 
	 */
	protected function file(){
		return config('file_upload');
	}
	
	/**
	 * flash上传
	 *
	 */
	protected function flash(){
		return config('flash_upload');
	}
	
	/**
	 * media上传
	 *
	 */
	protected function media(){
		return config('media_upload');
	}
	
}