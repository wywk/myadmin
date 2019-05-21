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
 * 通用联动模型
 *
 * @author	matengfei
 * @date 	2017-08-01
 * @version 1.0
 */
class SystemLinkage extends Base {
	
	//有无子级
	public $has_child = [
		1 => '有',
		2 => '无',
	];
	
	//子级唯一
	public $child_only = [
		1 => '是',
		2 => '否',
	];
	
	//状态
	public $status = [
		1 => '正常',
		2 => '禁用',
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
			//有无子级
			$info['has_child_name'] = $info['has_child'] ? $this->has_child[$info['has_child']] : '';
			//子级唯一
			$info['child_only_name'] = $info['child_only'] ? $this->child_only[$info['child_only']] : '';
			//状态
			$info['status_name'] = $info['status'] ? $this->status[$info['status']] : '';
		}
		$info = $this->formatInfo($info);
		return $info;
	}
	
}