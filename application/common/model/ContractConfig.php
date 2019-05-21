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
 * 关联贷款合同配置模型
 * @author	zhangjun
 * @date 	2017-08-17
 * @version 1.0
 */
class ContractConfig extends Base {
	//贷款；类型名称
	public $loan_type_name = [
		'1'	=> '抵押类',
		'2'	=> '二押类',
		'3'	=> '质押类',
	];

	//合同名称
	public $contract_name = [
		'loan' => '借款合同',
		'loan_intermediary' => '借款居间合同',
		'IOU' => '借条',
		'confirm' => '债权转让确认书',
		'repay' => '还款管理说明书',
		'account_opened' => '个人账户开通协议合同',
		'delegated' => '委托扣款授权书',
		'vehicle_sale' => '车辆买卖合同(新车主签)',
		'vehicle_rental_license' => '车辆租赁合同',
		'power_of_attorney' => '公司授权委托书',
		'mortgage' => '抵押合同',
		'pledge' => '质押合同',
		'vehicle_trading' => '车辆买卖合同',
		'financing' => '借款咨询及管理服务',
		//'letter' => '告知函',
		//'entrust' => '委托书',
		//'agency' => '委托代理书',
		'loan_supplement' => '借款合同补充协议',
		'IOU_extension_application' => '借条延期申请',
		//'security_agreement' => '担保协议',
		//'rental_agreement' => '租车协议',
		//'vehicle_payment' => '购车款收据',
		//'validation_protocol_confirm' => '文书送达地址确认协议',
		'authorized_transfer' => '授权过户委托书',
		'debt_confirm' => '债权债务确认书',
	];

	/**
	 * 自定义初始化
	 */
	protected function initialize(){
		parent::initialize();
	}

	/**
	 * 关联贷款合同配置数据处理
	 * @author	zhangjun
	 * @date 	2017-08-17
	 */
	public function moreInfo($info) {
		/****贷款类型名称****/
		$info['loan_types_name'] = '';
		if (!empty($info['loan_types'])) {
			$loan_type_array = explode(',', $info['loan_types']);
			foreach ($loan_type_array as $v) {
				$info['loan_types_name'] .= $this->loan_type_name[$v] . '-' . model('loan')->loan_type[$v] . '、';
			}
			$info['loan_types_name'] = rtrim($info['loan_types_name'], '、');
		}

		/****业务类型名称****/
		$info['business_types_name'] = '';
		if (!empty($info['business_types'])) {
			$business_type_array = explode(',', $info['business_types']);
			foreach ($business_type_array as $v) {
				$info['business_types_name'] .= model('loan')->business_type[$v] . '、';
			}
			$info['business_types_name'] = rtrim($info['business_types_name'], '、');
		}

		/****关联合同名称****/
		$info['contract_names_show'] = '';
		$info['list_contract_names_show'] = '';
		if (!empty($info['contract_names'])) {
			$contract_name_array = explode(',', $info['contract_names']);
			foreach ($contract_name_array as $v) {
				if (isset($this->contract_name[$v])) {
					$info['contract_names_show'] .= $this->contract_name[$v] . '、';
				}
			}
			$info['contract_names_show'] = rtrim($info['contract_names_show'], '、');

			//合同名称长度大于10则隐藏
			if ($info['contract_names_show']) {
				if (strlen($info['contract_names_show']) > 10) {
					$info['list_contract_names_show'] = mb_substr($info['contract_names_show'], 0, 10, 'utf-8') . '...';
				} else {
					$info['list_contract_names_show'] = $info['contract_names_show'];
				}
			}
		}

		return $info;
	}
	
}