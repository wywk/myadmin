<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2017 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: daixiantong <dai.xiantong@njswtz.com>
// +----------------------------------------------------------------------

namespace app\common\model;

use app\common\model\Base;

/**
 * 团队管理模型
 * 
 * @author daixiantong
 * @date 2017-8-11
 * @version 1.0
 */
class Team extends Base {
	
	public $keyList;
	
	/**
	 * 自定义初始化
	 */
	public function initialize(){
		parent::initialize();
		
		$this->keyList = [
			'store_id' => $this->getStoreList()	
		];
		
	}
	
	/**
	 * 设置缓存
	 */
	public function formatInfo($info) {
		$info['update_user_name'] = '';
		$info['format_update_time'] = '';
		$info = parent::formatInfo($info);
		
		if ($info) {
			//门店名称
			$storeModel = model('store');
			if ($info['store_id'] > 0) {
				$storeInfo = $storeModel->where('id', $info['store_id'])->find();
				$info['store_name'] = $storeInfo['name'];
			}elseif ($info['is_elec'] == 1 && $info['store_id'] == -1) {
				$info['store_name'] = '电销';
			}else {
				$info['store_name'] = '';
			}
				
			//负责人
			$teamManagerModel = model('teamManagerChangeLog');
			$managerInfo = $teamManagerModel
						-> where('team_id', $info['id'])
						-> where('mark', 1)
						-> where('start_time', '<=', time())
						-> where('end_time', ['=', 0], ['>', time()], 'or')
						-> order('id', 'desc')
						-> limit(1)
						-> find();
			
			$info['manager_id']				= $managerInfo['manager_id'];
			$info['manager_name']			= $managerInfo['manager_name'];
			$info['manager_log_id']			= $managerInfo['id'];
			$info['start_time']				= $managerInfo['start_time'];	//生效时间
			$info['tenure_time']			= $managerInfo['tenure_time'];	//任职时间
			$info['quit_time']				= $managerInfo['quit_time'];	//离职时间
			$info['promotion_time']			= $managerInfo['promotion_time'];	//升职时间
			$info['demotion_time']			= $managerInfo['demotion_time'];	//降职时间
			$info['format_start_time']		= $managerInfo['start_time'] > 0 ? date('Y-m-d', $managerInfo['start_time']) : '';
			$info['format_tenure_time']		= $managerInfo['tenure_time'] > 0 ? date('Y-m-d', $managerInfo['tenure_time']) : '';
			$info['format_quit_time']		= $managerInfo['quit_time'] > 0 ? date('Y-m-d', $managerInfo['quit_time']) : '';
			$info['format_promotion_time']	= $managerInfo['promotion_time'] > 0 ? date('Y-m-d', $managerInfo['promotion_time']) : '';
			$info['format_demotion_time']	= $managerInfo['demotion_time'] > 0 ? date('Y-m-d', $managerInfo['demotion_time']) : '';
			
			//团队人数
			$userModel = model('user');
			$info['team_count'] = $userModel
								-> where('team_id', $info['id'])
								-> where('mark',1)
								-> where('status', 'IN', [1,3]) //在职、试用期
								-> count();
		}
		
		return $info;
	}
	
	/**
	 * 获取门店数据
	 */
	public function getStoreList(){
		$storeModel = model('store');
		$storeArr = collection($storeModel
					-> where([
							'mark'			=> ['eq', 1],
							'store_state'	=> ['IN', [1,2,3]]
						])
					-> field('id, name')
					-> order('id ASC')
					-> select())
					-> toArray();
		
		$storeList = [];
		foreach ($storeArr as $k => $v){
			$storeList[$v['id']] = $v['name'];
		}
		
		return $storeList;
	}
}