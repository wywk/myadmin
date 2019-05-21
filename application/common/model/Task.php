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

use app\common\model\Base;

/**
 * 角色模型
 *
 * @author	luffy
 * @date 	2018-04-26
 * @version 1.0
 */
class Task extends Base {

	//解决者
	public $phper = [
		1 => '路飞',
		2 => '可欣',
		3 => '润润',
		4 => '牧牧',
		5 => '硕硕',
		6 => '石保',
		7 => '璐璐',
		8 => '玉巧',
		9 => '盼盼',
		10 => '文文',
	];

	//关键字
	public $keywords_1 = [
		1 => '平台后台',
		2 => '院校后台',
		3 => '官网前台',
		4 => '入住企业',
		5 => '手机APP',
	];

	//任务状态
	public $status = [
		1 => '未进行',
		2 => '进行中',
		3 => '已完成',
	];

	//状态颜色
	public $color = [
		1 => '#D0D0D0',
		2 => '#97CBFF',
		3 => '#93FF93',
		4 => '#FF8040',
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