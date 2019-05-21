<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2017 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: luffy <1549626108@qq.com>
// +----------------------------------------------------------------------

namespace app\common\model;

/**
 * 后台菜单模型
 * @author	luffy
 * @date    2019-04-07
 * @version 1.0
 */
class AdminUser extends Base {

    public $status = [
        1 => '启用',
        2 => '停用'
    ];

	/**
	 * 自定义初始化
	 */
	protected function initialize(){
		parent::initialize();
	}
}