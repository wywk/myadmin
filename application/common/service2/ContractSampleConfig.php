<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2017 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: zhangj <matengfei2000@gmail.com>
// +----------------------------------------------------------------------

namespace app\common\service;

use app\common\model\Base;

/**
 * 合同示例配置类
 *
 * @author 	zhangjun
 * @date 	2017-08-017
 * @version 1.0
 */
class ContractSampleConfig extends Base {
	
	private $model;
	
	/**
	 * 自定义初始化
	 */
	protected function initialize(){
		parent::initialize();
		$this->model = model('contract_sample_config');
	}
	
}