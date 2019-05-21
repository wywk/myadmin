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
 * 文章分表模型
 *
 * @author	matengfei
 * @date 	2017-08-01
 * @version 1.0
 */
class ArticleInfo extends Base {

	/**
	 * 自定义初始化
	 */
	protected function initialize(){
		parent::initialize();
	}
	
	public function article(){
		return $this->belongsTo('article');
	}
	
	/**
	 * 缓存基本信息
	 *
	 * @author matengfei
	 * @param int $id
	 * @return array
	 */
	protected function _cacheInfo($id){
		$info = $this::get($id)->toArray();
		if ($info['imgs']) {
			$info['imgs'] = unserialize($info['imgs']);
		}
		//编辑器显示内容
		if (strpos($info['content'], '{PIC_URL}') !== false) {
			$info['content'] = str_replace(['{PIC_URL}'], [PIC_URL], $info['content']);
		}
		//PC文章显示内容
		$info['vcontent'] = $info['vcontent'] ? $info['vcontent'] : $info['content'];
		if (strpos($info['vcontent'], '{IMG_URL}') !== false) {
			$info['vcontent'] = str_replace('{IMG_URL}', IMG_URL, $info['vcontent']);
		}
		//WAP文章显示内容
		$info['mcontent'] = $info['mcontent'] ? $info['mcontent'] : $info['content'];
		if (strpos($info['mcontent'], '{IMG_URL}') !== false) {
			$info['mcontent'] = str_replace('{IMG_URL}', IMG_URL, $info['mcontent']);
		}
		$info = $this->formatInfo($info);
		return $info;
	}
	
}