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