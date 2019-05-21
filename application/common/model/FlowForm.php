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
class FlowForm extends Base {
	
	protected $insert = [];
	protected $update = [];
	
	public $status = [
		1	=> '开启',
		2	=> '停用',
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
		
		$info['format_status'] = getter($info, 'status') ? getter($this->status, $info['status']) : null;
		
		return $info;
	}
	
	
}