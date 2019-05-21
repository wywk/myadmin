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
 * 用户公告模型
 *
 * @author	matengfei
 * @date 	2017-08-01
 * @version 1.0
 */
class UserNotice extends Base
{
	public $type = [
		1 => '通知',
		2 => '公告',
	];

    public $status = [
    	1 => '已发布',
    	2 => '未发布',
	];

	public $is_timing = [
		1 => '是',
		0 => '否',
	];

    /**
	 * 自定义初始化
	 */
	protected function initialize(){
		parent::initialize();
	}

}