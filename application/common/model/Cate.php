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
 * 栏目模型
 *
 * @author	matengfei
 * @date 	2017-08-01
 * @version 1.0
 */
class Cate extends Base {
	
	//是否显示
	public $is_show = [
		1 => '显示',
		2 => '隐藏',
	];
	
	//有无子节点
	public $has_child = [
		1 => '有',
		2 => '无',
	];
	
	//是否推荐
	public $is_recom = [
		1 => '推荐',
		2 => '取消',
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
			//是否显示
			$info['is_show_name'] = $info['is_show'] ? $this->is_show[$info['is_show']] : '';
			//有无子节点
			$info['has_child_name'] = $info['has_child'] ? $this->has_child[$info['has_child']] : '';
			//是否推荐
			$info['is_recom_name'] = $info['is_recom'] ? $this->is_recom[$info['is_recom']] : '';
		}
		$info = $this->formatInfo($info);
		return $info;
	}
	
}