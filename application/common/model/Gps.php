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

/**
 * GPS基础信息
 * 
 * @author daixiantong
 * @date 2017-8-7
 * @version 1.0
 */
class GpsBasicInfo extends Base {
	
	protected $insert = [];
	protected $update = [];
	
	public $gps_brand = [
		1	=> '小卫兵',
		2	=> '微凡',
		3	=> '中科',
	];
	
	public $gps_type = [
		1	=> '有线',
		2	=> '无线',
	];
	
	/**
	 * 自定义初始化
	 */
	public function initialize(){
		parent::initialize();
		$this->insert = ['create_user'=>session('user_auth.uid'), 'update_user'=>session('user_auth.uid')];
		$this->update = ['update_user'=>session('user_auth.uid')];
	}
	
	public function getStoreInfo($id) {
		$info = $this->getInfo($id);
// 		dump($info);exit;

		// 地区 
		$area_period_info = Period::CurrentInfo(Period::STORE_AREA, $id)->find();
		$info['area_begin_time'] = getter($area_period_info, 'begin_time'); // 当前地区生效时间
		$info['format_area_begin_time'] = $info['area_begin_time'] ? date('Y-m-d', $info['area_begin_time']) : null; // 当前地区生效时间
		$new_area_period_info = Period::FutureInfo(Period::STORE_AREA, $id)->find();
		$info['new_area_id'] = getter($new_area_period_info, 'slave_id'); // 新地区ID
		$info['new_area_begin_time'] = getter($new_area_period_info, 'begin_time'); // 新地区生效时间
		$info['format_new_area_begin_time'] = $info['new_area_begin_time'] ? date('Y-m-d',$new_area_period_info['begin_time']) : null; // 新地区生效时间
		$info['new_area_id'] && $new_area_info=db('area')->find($info['new_area_id']);
		$info['new_area_name'] = isset($new_area_info) ? getter($new_area_info, 'name') : null; // 新地区名称

		// 负责人
		$charge_member_period_info = Period::CurrentInfo(Period::STORE_CHARGEMEMBER, $id)->find();
		$info['charge_member_begin_time'] = getter($charge_member_period_info, 'begin_time'); // 当前负责人生效时间
		$info['format_charge_member_begin_time'] = $info['charge_member_begin_time'] ? date('Y-m-d', $info['charge_member_begin_time']) : null; // 当前负责人生效时间
		$new_charge_member_period_info = Period::FutureInfo(Period::STORE_CHARGEMEMBER, $id)->find();
		$info['new_charge_member_id'] = getter($new_charge_member_period_info, 'slave_id'); // 新负责人ID
		$info['new_charge_member_begin_time'] = getter($new_charge_member_period_info, 'begin_time'); // 新负责人生效时间
		$info['format_new_charge_member_begin_time'] = $info['new_charge_member_begin_time'] ? date('Y-m-d',$new_charge_member_period_info['begin_time']) : null; // 新负责人生效时间
		$info['new_charge_member_id'] && $new_charge_member_info=db('user')->find($info['new_charge_member_id']);
		$info['new_charge_member_name'] = isset($new_charge_member_info) ? getter($new_charge_member_info, 'realname') : null; // 新负责人名称
		
		// 薪资方案
		$salary_type_period_info = Period::CurrentInfo(Period::STORE_SALARYTYPE, $id)->find();
		$info['salary_type_begin_time'] = getter($salary_type_period_info, 'begin_time'); // 当前地区生效时间
		$info['format_salary_type_begin_time'] = $info['salary_type_begin_time'] ? date('Y-m-d', $info['salary_type_begin_time']) : null; // 当前地区生效时间
		$new_salary_type_period_info = Period::FutureInfo(Period::STORE_SALARYTYPE, $id)->find();
		$info['new_salary_type'] = getter($new_salary_type_period_info, 'value'); // 新地区ID
		$info['new_salary_type_begin_time'] = getter($new_salary_type_period_info, 'begin_time'); // 新地区生效时间
		$info['format_new_salary_type_begin_time'] = $info['new_salary_type_begin_time'] ? date('Y-m-d',$new_salary_type_period_info['begin_time']) : null; // 新地区生效时间
		$info['format_new_salary_type'] = isset($info['new_salary_type']) ? getter($this->salary_type, $info['new_salary_type']) : null; // 新地区名称
		
		return $info;
	}
	
	/**
	 * 缓存门店数据及数据格式化
	 */
	protected function _cacheInfo($id){
		$info = parent::_cacheInfo($id);
		
// 		$info['format_gps_brand'] = getter($info, 'gps_brand') ? getter($this->gps_brand, $info['gps_brand']) : null;
		$info['format_gps_type'] = getter($info, 'gps_type') ? getter($this->gps_type, $info['gps_type']) : null;
		
		return $info;
	}
	
	
}