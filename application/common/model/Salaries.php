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

use app\common\model\Base;

/**
 * 薪酬配置管理模型
 * 
 * @author daixiantong
 * @date 2017-8-18
 * @version 1.0
 */
class Salaries extends Base {
	
	public $position_id;
	public $user_state;
	public $relation;
	public $type;
	public $compare;
	
	/**
	 * 自定义初始化
	 */
	public function initialize(){
		parent::initialize();
		
		//职位
		$this->position_id = [
				52	=> '业务专员',
				51	=> '团队经理',
				48	=> '门店经理',
				60	=> '区域负责人',
		];
		
		//状态
		$this->user_state = [
				1	=> '已转正',
				3	=> '试用期',
		];
		
		//额外标准关系
		$this->relation = [
				0	=> '...',
				1	=> '或',
				2	=> '且'
		];
		
		//额外标准
		$this->type = [
				1	=> '开单数',
				2	=> '2个月累计',
				3	=> '3个月累计',
				4	=> '首逾'
		];
		
		//额外标准比较关系
		$this->compare = [
				1	=> '>',
				2	=> '>=',
				3	=> '=',
				4	=> '<',
				5	=> '<='
		];
	}
	
	/**
	 * 数据格式化处理
	 */
	public function formatInfo($info){
		
		if ($info['user_state']){
			$info['user_state_name'] = $this->user_state[$info['user_state']];
		}else {
			$info['user_state_name'] = '';
		}
		
		if ($info['position_id']) {
			$info['position_name'] = $this->position_id[$info['position_id']];
		}else {
			$info['position_name'] = '';
		}
		
		//计算标准标记及单位
		if ($info['position_id'] == 60) {
			$tag = ' <= P < ';
			$unit = '';
		}else {
			$tag = ' <= N < ';
			$unit = 'w';
		}
		
		//额外标准单位
		if ($info['position_id'] == 52) {
			if ($info['type'] == 1) {
				$number_unit = '';
			}else {
				$number_unit = 'w';
			}
		}elseif (in_array($info['position_id'], [51,48])) {
			$number_unit = '件/月';
		}else {
			$number_unit = '';
		}
		
		if ($info['max'] == '65535') {
			$info['max'] = '*';
		}
		
		//计算标准数据格式处理
		$info['standard'] = $info['min'] . $unit . $tag . $info['max'] . $unit;
		if ($info['relation'] > 0) {
			$info['standard'] .= ' ' . $this->relation[$info['relation']];
		}
		if ($info['type'] > 0) {
			$info['standard'] .= ' ' . $this->type[$info['type']];
		}
		if ($info['compare'] > 0) {
			$info['standard'] .= ' ' . $this->compare[$info['compare']];
		}
		if ($info['number']) {
			$info['standard'] .= ' ' . $info['number'] . $number_unit;
		}
		
		$info = parent::formatInfo($info);
		
		return $info;
	}
}