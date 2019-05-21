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
 * 配置模型
 *
 * @author	matengfei
 * @date 	2017-08-01
 * @version 1.0
 */
class Config extends Base {
	
	public $group = [
		1 => '系统设置',
		2 => '网站设置',
		3 => '邮件设置',
	];

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
	
}