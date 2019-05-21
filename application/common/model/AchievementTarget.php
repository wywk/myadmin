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
 * 营销目标业绩模型
 *
 * @author	luffy
 * @date 	2017-08-18
 * @version 1.0
 */
class AchievementTarget extends Base {

	/**
	 * 自定义初始化
	 */
	protected function initialize(){
		parent::initialize();
	}

	/**
	 * 数据转换
	 * @author	luffy
	 * @date 	2017-08-19
	 */
	public function moreInfo($info) {
		//职位
		if($info['position_id']){
			$position_info = model('position')->getInfo($info['position_id']);
			$info['format_position_name'] = $position_info['name'];
		}
		//门店
		if($info['store_id']){
			$store_info = model('store')->getInfo($info['store_id']);
			$info['format_store_name'] = $store_info['name'];
		}
		$info['new_charge_member_name'] = $info['realname'];
		$info['new_charge_member_id'] = $info['member_id'];
		//月份
		$info['format_target_date'] = date('Y-m', $info['target_date']);
		return $info;
	}
}