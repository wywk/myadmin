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
 * 市场业绩规则配置模型
 *
 * @author	zhangjun
 * @date 	2017-08-11
 * @version 1.0
 */
class Performance extends Base {
	//关系
	public $logic = [
		1	=> '<',
		2	=> '<=',
	];

	//职位
	public $position = [
		'10'=>	'业务员',
		'9'	=>	'团队经理',
		'8'	=>	'门店经理',
	];

	//转正时间
	public $full_time = [
		'1'	=>	'1个月',
		'2'	=>	'2个月',
		'3'	=>	'3个月',
	];

	//关系
	public $relation = [
		'1'	=>	'或',
		'2'	=>	'且',
	];

	//级别
	public $level = [
		'1'	=>	'初级',
		'2'	=>	'中级',
		'3'	=>	'高级',
		'4'	=>	'资深',
	];

	//晋升路径
	public $promote_path = [
		'1'	=>	'晋升中级',
		'2'	=>	'晋升高级',
		'3'	=>	'晋升资深',
	];

	//考核路径
	public $assess_path = [
		'1'	=>	'淘汰、降级',
		'2'	=>	'降为初级',
		'3'	=>	'降为中级',
		'4'	=>	'降为高级',
	];

	//状态
	public $state = [
		'1'	=>	'开启',
		'2'	=>	'关闭',
	];

	//消息
	public $tip = [
		'1'	=> [
			'纯新增业绩指标',
			'转正单量指标',
		],
		'2'	=> [
			'三个月业绩总额',
			'每月保底任务',
		],
		'3'	=> [
			'三个月业绩总额'
		],
	];

	/**
	 * 自定义初始化
	 */
	protected function initialize(){
		parent::initialize();
	}
	
}