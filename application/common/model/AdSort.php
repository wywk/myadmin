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
 * 广告位模型
 *
 * @author	matengfei
 * @date 	2017-08-01
 * @version 1.0
 */
class AdSort extends Base {
	
	//站点类型
	public $type = [
		1 => '网站',
		2 => '手机站',
		3 => 'APP'
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
			//站点类型
			$info['type_name'] = '';
			if ($info['type']) {
				$info['type_name'] = $this->type[$info['type']];
			}
			//所属频道
			$info['item_name'] = '';
			if ($info['item_id']) {
				$itemInfo = model('item')->get($info['item_id'])->toArray();
				$info['item_name'] = $itemInfo['name'];
			}
			//所属栏目
			$info['cate_name'] = '';
			if ($info['cate_id']) {
				$cateInfo = model('cate')->get($info['cate_id'])->toArray();
				$info['cate_name'] = $cateInfo['name'];
			}
		}
		$info = $this->formatInfo($info);
		return $info;
	}
	
}