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

namespace app\common\service;

use app\common\model\Base;

/**
 * 图片管理服务类
 *
 * @author 	matengfei
 * @date 	2017-08-01
 * @version 1.0
 */
class Image extends Base {
	
	/**
	 * 自定义初始化
	 */
	protected function initialize(){
		parent::initialize();
	}
	
	/**
	 * 从远程内容中获取图片、Falsh、视频、音频
	 *
	 * @author	matengfei
	 * @param	string $content
	 * @param	string $dir
	 * @return	array
	 */
	function getImgByContent($content, $dir='article'){
		$old = $content;
		$image = [];
	
		$content = strip_tags($content, '<img>');
		$preg = '/<\s*img\s+[^>]*?src\s*=\s*(\'|\")(.*?)\\1[^>]*?\/?\s*>/i';
		preg_match_all($preg, $content, $arrImage, PREG_PATTERN_ORDER);
		$array = array_unique($arrImage[2]);
		if ($array) {
			$imgNot = [];
			foreach ($array as $key => $val) {
				$pos = strpos($val, UPLOAD_TEMP_URL);
				if ($pos !== false) { //临时目录图片
					$path = str_replace(UPLOAD_URL, '', $val);
				} else {
					$pos1 = strpos($val, UPLOAD_URL . "{$dir}/");
					if ($pos1 === false) { //网络图片
						$path = getImgByUrl($val, UPLOAD_TEMP_PATH, '', true); //获取网络图片
					} else { //已上传图片
						$path = str_replace(UPLOAD_URL, '', $val);
					}
				}
				if ($path) {
					$image[$val] = $path;
				} else {
					$imgNot[] = $key;
				}
			}
			if ($image) {
				$old = str_replace(array_keys($image), array_values($image), $old);
			}
			if ($imgNot) {
				foreach ($imgNot as $val) {
					$old = str_replace($arrImage[0][$val], '', $old);
				}
			}
		}
		$return = [
			'content' => $old,
			'image'   => $image,
		];
		return $return;
	}

}