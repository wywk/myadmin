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
 * 汽车车系模型
 *
 * @author	matengfei
 * @date 	2017-08-01
 * @version 1.0
 */
class CarSeries extends Base {
	
	//是否推荐
	public $is_recom = [
		1 => '是',
		2 => '否',
	];
	
	//状态
	public $status = [
		1 => '在售',
		2 => '停售',
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
			//品牌名称
			$brandInfo = model('car_brand')->_cacheInfo($info['brand_id']);
			$info['brand_name'] = $brandInfo['name'];
			//是否推荐
			$info['is_recom_name'] = $info['is_recom'] ? $this->is_recom[$info['is_recom']] : '';
			//状态
			$info['status_name'] = $info['status'] ? $this->status[$info['status']] : '';
		}
		$info = $this->formatInfo($info);
		return $info;
	}
	
}