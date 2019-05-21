<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2017 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: zhangjun <zhang.jun@njswtz.com>
// +----------------------------------------------------------------------

namespace app\common\model;

use app\common\model\Base;

/**
 * 贷款模型
 * 
 * @author zhangjun
 * @date 2017-8-15
 * @version 1.0
 */
class Loan extends Base {
	//贷款；类型
	public $loan_type = [
		'1'	=> '车辆抵押贷',
		'2'	=> '二押',
		'3'	=> '质押',
	];

	//业务类型
	public $business_type = [
		'1' => '新增',
		'2' => '追加',
		'3' => '展期',
		'4' => '续贷',
		'5' => '先转等',
		'6' => '二押转GPS',
		'7' => '质押转GPS',
		'8' => '二押转质押',
	];
	
	/**
	 * 自定义初始化
	 */
	public function initialize(){
		parent::initialize();
	}
}