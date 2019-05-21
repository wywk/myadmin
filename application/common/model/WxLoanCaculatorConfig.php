<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2017 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: luffy <1549626108@qq.com>
// +----------------------------------------------------------------------

namespace app\common\model;

use app\common\model\Base;

/**
 * 微信计算器配置管理模型
 *
 * @author	luffy
 * @date 	2017-08-18
 * @version 1.0
 */
class  WxLoanCaculatorConfig extends Base {

	public $repay_style = [	 //还款方式
		1 => '等额本息',
		2 => '先息后本',
	];

	public $type = [	//类别
		1 => '低息贷',
		2 => '高额贷',
	];

	/**
	 * 自定义初始化
	 */
	protected function initialize(){
		parent::initialize();
	}

	/**
	 * 数据转换
	 * @author	luffy
	 * @date 	2017-08-19
	 */
	public function moreInfo($info) {
		//还款方式
		if($info['repay_style']){
			$info['format_repay_style'] = $this->repay_style[$info['repay_style']];
		}
		//类型
		if($info['type']){
			$info['format_type'] = $this->type[$info['type']];
		}
		return $info;
	}
}