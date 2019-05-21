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

namespace app\common\model;

use app\common\model\Base;

/**
 * 切图配置模型
 *
 * @author	matengfei
 * @date 	2017-08-01
 * @version 1.0
 */
class ImageCut extends Base {
	
	public $type = [
		IMG_USER_AVATAR     => '用户头像图片',
		IMG_ARTICLE_CONTENT => '资讯内容图片',
		IMG_ARTICLE_IMAGE   => '资讯封面图片',
	];
	
	public $isMd5;
	
	public $salt;

	/**
	 * 自定义初始化
	 */
	protected function initialize(){
		parent::initialize();

		$this->isMd5 = true;
		$this->salt = config('authcode');
	}
	
	/**
	 * 切图加水印
	 * 
	 * @author	matengfei
	 * @param 	string $image  图片路径
	 * @param 	int $cut_type  切图类型
	 * @param 	bool $is_water 是否添加水印 
	 */
	public function crop($image_url, $cut_type, $is_water=false, $domain=true){
	if (false !== strpos($image_url, UPLOAD_URL)) {
			$image_url = str_replace(UPLOAD_URL, '', $image_url);
		}
		$image_path = UPLOAD_PATH . $image_url;
		if (!file_exists($image_path)) {
			return false;
		}
		$image_obj = \think\Image::open($image_path);
		$image_name = pathinfo($image_path, PATHINFO_FILENAME);
		$cond = "mark=1 AND type={$cut_type}";
		$cutList = $this->getList($cond, 'sort ASC', 'width,height');
		$data = [];
		foreach ($cutList as $key => $val) {
			if ($this->isMd5) {
				$new_image_name = imagename($image_name, $this->salt, $val['width'], $val['height']);
			} else {
				$new_image_name = $image_name;
			}
			$new_image_url  = str_replace($image_name, $new_image_name, $image_url);
			$new_image_path = str_replace($image_name, $new_image_name, $image_path);
			$new_image_dir  = dirname($new_image_path);
			if (!file_exists($new_image_dir)) {
				//mkdir($new_image_dir, 0755, true);
				ecm_mkdir($new_image_dir, UPLOAD_PATH);
			}
			$result = $image_obj->crop($val['width'], $val['height'])->save($new_image_path);
			if ($result) {
				$data["{$val['width']}_{$val['height']}"] = $domain ? UPLOAD_URL . $new_image_url : $new_image_url;
			}
		}
		return $data;
		
	}
	
	/**
	 * 获取切图
	 * 
	 * @author	matengfei
	 * @param 	string $image_url 图片路径
	 * @param 	int $cut_type 切图类型
	 * @param 	bool 返回图片路径是否包含域名
	 * @return 	array
	 */
	public function getCrop($image_url, $cut_type, $domain=true){
		if (false !== strpos($image_url, UPLOAD_URL)) {
			$image_url = str_replace(UPLOAD_URL, '', $image_url);
		}
		$image_path = UPLOAD_PATH . $image_url;
		if (!file_exists($image_path)) {
			return false;
		}
		$image_name = pathinfo($image_path, PATHINFO_FILENAME);
		$cond = "mark=1 AND type={$cut_type}";
		//$cutList = $this->getList($cond, 'sort ASC', 'width,height');
		$cutList = db('image_cut')->field('width,height')->where($cond)->select(); //直接查库，防止其他表缓存嵌套
		$data = [];
		foreach ($cutList as $key => $val) {
			if ($this->isMd5) {
				$new_image_name = imagename($image_name, $this->salt, $val['width'], $val['height']);
			} else {
				$new_image_name = $image_name;
			}
			$new_image_url  = str_replace($image_name, $new_image_name, $image_url);
			$new_image_path = str_replace($image_name, $new_image_name, $image_path);
			$new_image_dir  = dirname($new_image_path);
			if (!file_exists($new_image_dir)) {
				//mkdir($new_image_dir, 0755, true);
				ecm_mkdir($new_image_dir, UPLOAD_PATH);
			}
			$data["{$val['width']}_{$val['height']}"] = $domain ? UPLOAD_URL . $new_image_url : $new_image_url;		
		}
		return $data;
	}
	
}