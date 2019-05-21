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
 * 系统管理 - 组织架构 - 门店管理 模型
 * 
 * @author daixiantong
 * @date 2017-8-7
 * @version 1.0
 */
class VehicleConfig extends Base {
	
	protected $insert = [];
	protected $update = [];
	
	public $appraise = [
		1	=> '优',
		2	=> '良',
		3	=> '中',
		4	=> '差',
	];
	
	public $insurance = [
		1	=> '是',
		2	=> '否',
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
		
		// 购车年限区间
		$temp = [];
		getter($info, 'lower_age')!=null && $temp[] = getter($info, 'lower_age');
		getter($info, 'lower_age_relation') && $temp[] = getter($info, 'lower_age_relation');
		$temp[] = 'Y';
		getter($info, 'upper_age_relation') && $temp[] = getter($info, 'upper_age_relation');
		getter($info, 'upper_age')!=null && $temp[] = getter($info, 'upper_age');
		$info['format_age'] = $temp ? implode(' ', $temp) : null;
		// 公里数区间
		$temp = [];
		getter($info, 'lower_distance')!=null && $temp[] = getter($info, 'lower_distance');
		getter($info, 'lower_distance_relation') && $temp[] = getter($info, 'lower_distance_relation');
		$temp[] = 'S';
		getter($info, 'upper_distance_relation') && $temp[] = getter($info, 'upper_distance_relation');
		getter($info, 'upper_distance')!=null && $temp[] = getter($info, 'upper_distance');
		$info['format_distance'] = $temp ? implode(' ', $temp) : null;
		// 是否出险
		$info['format_insurance'] = getter($this->insurance, getter($info ,'insurance'));
		// 车况标准
		$info['format_appraise'] = getter($this->appraise, getter($info ,'appraise'));
		
		return $info;
	}
	
	
}