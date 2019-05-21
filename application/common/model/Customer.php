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
 * 客户
 * 
 * @author daixiantong
 * @date 2017-8-7
 * @version 1.0
 */
class Customer extends Base {
	
	protected $insert = [];
	protected $update = [];
	
	public $sex = [
		1 => '男',
		2 => '女',
	];
	
	public $is_company_customer = [
		1 => '否',
		2 => '是',
	];
	
	public $education_level = [
		1 => '小学 ',
		2 => '初中 ',
		3 => '高中',
		4 => '中专',
		5 => '大专',
		6 => '本科',
		7 => '硕士',
		8 => '博士',
	];
	
	public $marital_status = [
		1 => '未婚 ',
		2 => '已婚 ',
		3 => '离异',
		4 => '丧偶',
	];
	
	public $have_children = [
		1 => '无', 
		2 => '有',
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
	 * 缓存
	 */
	protected function _cacheInfo($id){
		$info = parent::_cacheInfo($id);
		
		$info['format_sex'] = getter($info, 'sex') ? getter($this->sex, $info['sex']) : null;
		$info['format_is_company_customer'] = getter($info, 'is_company_customer') ? getter($this->is_company_customer, $info['is_company_customer']) : null;
		$info['format_education_level'] = getter($info, 'education_level') ? getter($this->education_level, $info['education_level']) : null;
		$info['format_marital_status'] = getter($info, 'marital_status') ? getter($this->marital_status, $info['marital_status']) : null;
		$info['format_have_children'] = getter($info, 'have_children') ? getter($this->have_children, $info['have_children']) : null;
		
		return $info;
	}
	
	public function relationInfo($info) {
		if ( getter($info, 'id') ) {
			// store salesman kefu 信息
			$relation_info = db('customer_store')->where(['customer_id'=>$info['id'], 'status'=>1])->order('id', 'desc')->find();
			if ($store_id=getter($relation_info, 'store_id')) {
				$store_info = model('store')->getInfo($relation_info['store_id']);
				$info['store_id'] = $store_id;
				$info['store_name'] = $store_info['name'];
			}
			if ($salesman_id=getter($relation_info, 'salesman_id')) {
				$salesman_info = model('user')->getInfo($salesman_id);
				$info['salesman_id'] = $salesman_id;
				$info['salesman_name'] = $salesman_info['realname'];
			}
			if ($kefu_id=getter($relation_info, 'kefu_id')) {
				$kefu_info = model('user')->getInfo($kefu_id);
				$info['kefu_id'] = $kefu_id;
				$info['kefu_name'] = $kefu_info['realname'];
			}
		}
		
		if ($city_id=getter($info, 'city_id')) {
			$city_info = model('city')->getInfo($city_id);
			$info['city_name'] = $city_info['name'];
		}
		
		if ($province_id=getter($info, 'province_id')) {
			$province_info = model('city')->getInfo($province_id);
			$info['province_name'] = $province_info['name'];
		}
		
		
		return $info;
	}
	
	
}