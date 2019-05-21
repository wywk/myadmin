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
 * 权限规则模型
 *
 * @author	matengfei
 * @date 	2017-08-01
 * @version 1.0
 */
class AuthRule extends Base {
	
	const type = 1; //权限规则分类：1-url;2-主菜单
	
	/**
	 * 自定义初始化
	 */
	protected function initialize(){
		parent::initialize();
	}
	
}