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
 * 菜单模型
 * Class Menu
 * @package app\common\model
 * @author zhangkx
 * @date 2019/4/19
 */
class AdminMenu extends Base {
	
	public $type = [
		1 => '权限认证+菜单',
		2 => '只作为菜单',
	];

	public $status = [
		1 => '显示',
		2 => '隐藏',
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
			$info['rule'] = '';
			$info['url'] = '';
			if ($info['module'] && $info['controller'] && $info['action']) {
				//附带参数
				$params = $info['param'] ? '?' . htmlspecialchars_decode($info['param']) : '';
				$info['rule'] = strtolower($info['module']."/".$info['controller']."/".$info['action'].$params);
				$info['url'] = url("{$info['module']}/{$info['controller']}/{$info['action']}{$params}");
			}
		}
		$info = $this->formatInfo($info);
		return $info;
	}
	
}