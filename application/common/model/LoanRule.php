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
 * 市场产品规则模型
 * 
 * @author daixiantong
 * @date 2017-8-15
 * @version 1.0
 */
class LoanRule extends Base {
	
	public $keyList;
	
	public $loan_type;		//贷款类型
	public $business_type;	//业务类型
	public $repay_style;	//还款方式
	public $period_unit;	//还款期限单位
	public $only_once;		//是否仅享受一次
	public $gps_rule;		//GPS费用规则
	public $over_type;		//结清性质
	public $status;			//状态
	public $relation;		//批复成数对应关系
	
	/**
	 * 自定义初始化
	 */
	public function initialize(){
		parent::initialize();
		
		//贷款类型
		$this->loan_type = [
				1	=> '车辆抵押',
				2	=> '二押',
				3	=> '质押'
		];
		
		//业务类型
		$this->business_type = [
				1	=> '新增',
				2	=> '追加',
				3	=> '展期',
				4	=> '续贷',
				5	=> '先转等',
				6	=> '二押转GPS',
				7	=> '质押转GPS',
				8	=> '二押转质押'
		];
		
		//还款方式
		$this->repay_style = [
				1	=> '等额本息',
				2	=> '先息后本'
		];
		
		//还款期限单位
		$this->period_unit = [
				1	=> '月',
				2	=> '天'
		];
		
		//是否仅享受一次
		$this->only_once = [
				1	=> '是',
				2	=> '否'
		];
		
		//GPS费用规则
		$this->gps_rule = [
				1	=> '按全额收取',
				2	=> '按流量收取'
		];
		
		//结清性质
		$this->over_type = [
				1	=> '正常结清',
				2	=> '提前结清'
		];
		
		//状态
		$this->status = [
				1	=> '开启',
				2	=> '关闭'
		];
		
		//批复成数对应关系
		$this->relation = [
				1	=> '<',
				2	=> '<=',
				3	=> '='
		];
		
		//搜索条件
		$this->keyList = [
				'loan_type'		=> $this->loan_type,
				'business_type'	=> $this->business_type,
				'repay_style'	=> $this->repay_style
		];
		
	}
	
	/**
	 * 处理数据展示格式
	 * 
	 * @see \app\common\model\Base::formatInfo()
	 * @return array
	 */
	public function formatInfo($info){
		$info = parent::formatInfo($info);
		
		//抵押类型
		if ($info['loan_type']) {
			$loan_type_arr = explode(',', $info['loan_type']);
			$loan_type = [];
			foreach ($loan_type_arr as $l){
				$loan_type[] = $this->loan_type[$l];
			}
			$info['loan_type_name'] = implode(',', $loan_type);
		}
		
		//业务类型
		if ($info['business_type']) {
			$info['business_type_name'] = $this->business_type[$info['business_type']];
		}
		
		//还款类型
		if ($info['repay_style']) {
			$info['repay_style_name'] = $this->repay_style[$info['repay_style']];
		}
		
		//还款期限单位
		if ($info['period_unit']) {
			$info['period_unit_name'] = $this->period_unit[$info['period_unit']];
		}
		
		//是否仅享受一次
		if ($info['only_once']) {
			$info['only_once_name'] = $this->only_once[$info['only_once']];
		}
		
		//有效门店
		if ($info['store_ids']) {
			$storeModel = model('store');
			$store_arr = explode(',', $info['store_ids']);
			$store_name = [];
			foreach ($store_arr as $s){
				$storeInfo = $storeModel->where(['mark'=>['eq', 1], 'id' => ['eq', $s]])->field('name')->find();
				$store_name[] = $storeInfo['name'];
			}
			$info['store_names'] = implode('，', $store_name);
		}
		
		//之前贷款规则
		if ($info['before_rule_ids']) {
			$rulesArr = explode(',', $info['before_rule_ids']);
			$rule_name = [];
			foreach ($rulesArr as $r){
				$ruleInfo = $this->where(['mark'=>['eq', 1], 'id' => ['eq', $r]])->field('name')->find();
				$rule_name[] = $ruleInfo['name'];
			}
			$info['before_rule_names'] = implode('，', $rule_name);
		}
		
		//有效开始时间
		$info['format_start_time'] = $info['start_time'] > 0 ? date('Y/m/d', $info['start_time']) : '';
		
		//有效截止时间
		$info['format_end_time'] = $info['end_time'] > 0 ? date('Y/m/d', $info['end_time']) : '';
		
		return $info;
	}
}