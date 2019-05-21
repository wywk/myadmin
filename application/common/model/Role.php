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
 * 角色模型
 *
 * @author	matengfei
 * @date 	2017-08-01
 * @version 1.0
 */
class Role extends Base {

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
	 * no cache
	 */
	public function relationInfo($info) {
		$total_user_num = db('role_user')
			->alias('role_user')
			->join('__USER__ user', 'user.id=role_user.user_id')
			->where(['user.status'=>['<>',2], 'user.mark'=>1, 'role_user.role_id'=>$info['id']])
			->count();
		$info['total_user_num'] = $total_user_num;
		return $info;
	}
	
}