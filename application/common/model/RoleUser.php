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
 * 用户角色对应模型
 *
 * @author	matengfei
 * @date    2017-08-01
 * @version 1.0
 */
class RoleUser extends Base {
	
	protected $pk = ['role_id', 'user_id'];
	
	// 创建时间字段
    protected $createTime = '';
    // 更新时间字段
    protected $updateTime = '';
	
	/**
	 * 自定义初始化
	 */
	protected function initialize(){
		parent::initialize();
	}
	
}