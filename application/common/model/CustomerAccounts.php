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
 * 客户-放款账户
 * 
 * @author lyl
 * @date 2017-8-7
 * @version 1.0
 */
class CustomerAccounts extends Base {
	
	protected $insert = [];
	protected $update = [];
	
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
		return $info;
	}
	
	public function relationInfo($info) {
		return $info;
	}
	
	
}