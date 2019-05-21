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

namespace app\admin\validate;

use app\common\validate\Base;

/**
 * 后台管理员验证类
 * @author	luffy
 * @date    2019-04-23
 * @version 1.0
 */
class Store extends Base {

    protected $regex = [
        'name_regular'      => '/^[a-zA-Z][a-zA-Z0-9_-]{4,10}$/',
        'phone_regular'     => '/^1\d{10}$/',
        'telephone_regular' => '/0\d{2,3}-\d{7,8}/'
    ];

	protected $rule = [
        ['name'     , 'require|unique:Store' , '店铺名称不能为空|店铺名称已存在，请重新输入！'],
        ['phone_regular'    , 'unique:Store|regex:phone_regular', '手机号码已存在，请重新输入！|请输入正确的手机号码'],
	];

	protected $scene = [
		'add'       => 'name,',
		'edit'      => 'name',
	];
	
}