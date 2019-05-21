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
 * 频道模型
 *
 * @author	matengfei
 * @date 	2017-08-01
 * @version 1.0
 */
class Item extends Base {
	
	//二级域名
	public $is_domain = [
		1 => '是',
		2 => '否',
	];
	
	//状态
	public $status = [
		1 => '运行',
		2 => '关闭',
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
			//二级域名
			$info['is_domain_name'] = $info['is_domain'] ? $this->is_domain[$info['is_domain']] : '';
			//状态
			$info['status_name'] = $info['status'] ? $this->status[$info['status']] : '';
		}
		$info = $this->formatInfo($info);
		return $info;
	}
	
}