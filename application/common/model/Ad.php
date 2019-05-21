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
 * 广告模型
 *
 * @author	matengfei
 * @date 	2017-08-01
 * @version 1.0
 */
class Ad extends Base {
	
	//广告格式
	public $filetype = [
		1 => '图片',
		2 => 'flash',
		3 => '文字',
	];

	/**
	 * 自定义初始化
	 */
	protected function initialize(){
		parent::initialize();
	}
	
	/**
	 * 缓存基本信息
	 *
	 * @author 	matengfei
	 * @param 	int $id
	 * @return 	array
	 */
	protected function _cacheInfo($id){
		$info = $this::get($id)->toArray();
		if ($info) {
			$sortInfo = model('ad_sort')->_cacheInfo($info['sort_id']);
			$info['type']      = $sortInfo['type'];
			$info['item_id']   = $sortInfo['item_id'];
			$info['cate_id']   = $sortInfo['cate_id'];
			$info['type_name'] = $sortInfo['type_name'];
			$info['item_name'] = $sortInfo['item_name'];
			$info['cate_name'] = $sortInfo['cate_name'];
			$info['sort_name'] = $sortInfo['name'];
			//广告格式
			$info['filetype_name'] = $this->filetype[$info['filetype']];
			//广告规格
			$info['size'] = $info['width'] . '×' . $info['height'];
			//广告附件
			$info['src_url'] = $info['src'] ?  UPLOAD_URL . $info['src'] : '';
		}
		$info = $this->formatInfo($info);
		return $info;
	}
	
}