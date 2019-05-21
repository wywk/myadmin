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
 * 区域管理模型
 * 
 * @author daixiantong
 * @date 2017-8-10
 * @version 1.0
 */
class Area extends Base {
	
	/**
	 * 自定义初始化
	 */
	public function initialize(){
		parent::initialize();
	}
	
	/**
	 * 缓存基本信息
	 * 
	 * @author daixiantong
	 * @date 2017-8-10
	 */
	public function formatInfo($info){
		if ($info) {
			//负责人
			$areaManagerModel = model('areaManagerChangeLog');
			$managerInfo = $areaManagerModel
						-> where('area_id', $info['id'])
						-> where('mark', 1)
						-> where('start_time', '<=', time())
						-> where('end_time', ['=', 0], ['>', time()], 'or')
						-> order('id', 'desc')
						-> limit(1)
						-> find();
			
			//辖区门店
			$storeModel = model('store');
			$areaStoreRelationModel = model('areaStoreRelation');
			$storeInfo = collection($areaStoreRelationModel
						-> where('area_id', $info['id'])
						-> where('mark', 1)
						-> where('begin_time', '<=', time())
						-> where('end_time', ['eq', 0], ['>', time()], 'or')
						-> field('store_id')
						-> select())
						-> toArray();
			$stores = [];
			if ($storeInfo) {
				foreach ($storeInfo as $s){
					$store = $storeModel->where('id',$s['store_id'])->where('mark', 1)->field('name')->find();
					if ($store) {
						$stores[] = $store['name'];
					}
				}
			}
			
			$info['change_log_id']		= $managerInfo['id'];			//当前负责人记录ID
			$info['manager_id']			= $managerInfo['manager_id'];	//当前负责人ID
			$info['manager_name']		= $managerInfo['manager_name']; //当前负责人姓名
			$info['start_time']			= $managerInfo['start_time'];	//当前负责人生效时间
			$info['format_start_time']	= date('Y-m-d', $managerInfo['start_time']);	//格式化当前负责人生效时间
			$info['store_names']		= implode(',', $stores);		//当前大区辖区门店
		}
		
		$info = parent::formatInfo($info);
		
		return $info;
	}
}