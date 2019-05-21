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
 * 合同模板配置模型
 *
 * @author	zhangjun
 * @date 	2017-08-17
 * @version 1.0
 */
class ContractTemplateConfig extends Base {
	//影响参数名
	public $influence_param_key = [
		'is_sign' => '共签时使用',
		'repay_period' => '还款期数',
		'is_vehicles_loan' => '多车共贷时使用',
		'is_relation_vehicle_number' => '是否关联车辆数',
		'is_transfer_use' => '过户时使用',
		'is_transfer_company_use' => '过户到公司时使用',
		'is_pledge_not_use' => '质押贷时不使用',
		'can_use_stores' => '哪些门店可用',
		'not_can_use_stores' => '哪些门店不可用',
		'is_previous_loan_specific' => '前笔贷款特定时使用',
		'is_transfer_loan_month' => '转业务贷款为等额本息时使用',
	];

	//版本数字
	public $version_number = [
		'1' => 'v1.0.0',
		'2' => 'v1.0.1',
		'3' => 'v1.0.2',
		'4' => 'v1.1.0',
		'5' => 'v1.1.1',
		'6' => 'v1.1.2',
		'7' => 'v1.2.0',
		'8' => 'v1.2.1',
		'9' => 'v1.2.2',
		'10' => 'v1.3.0',
		'11' => 'v1.3.1',
		'12' => 'v1.3.2',
		'13' => 'v1.4.0',
		'14' => 'v1.4.1',
		'15' => 'v1.4.2',
		'16' => 'v1.5.0',
		'17' => 'v1.5.1',
		'18' => 'v1.5.2',
		'19' => 'v1.3.3',
		'20' => 'v1.4.3',
		'21' => 'v1.5.3',
		'22' => 'v1.0.3',
		'23' => 'v1.1.3',
		'24' => 'v1.2.3',
		'25' => 'v1.6.3',
		'26' => 'v1.7.3',
		'27' => 'v1.0.4',
		'28' => 'v1.1.4',
		'29' => 'v1.2.4',
		'30' => 'v1.3.4',
		'31' => 'v1.4.4',
		'32' => 'v1.5.4',
		'33' => 'v1.6.4',
		'34' => 'v1.7.4',
	];

	//合同对应的参数
	public $contract_to_param = [
		//借款合同
		'loan' => [
			'1' => 'is_sign',
		],

		//借款居间合同
		'loan_intermediary' => [

		],

		//借条
		'IOU' => [

		],

		//债权转让确认书
		'confirm' => [
		],

		//还款管理说明书
		'repay' => [
			'1' => 'repay_period',
		],

		//个人账户开通协议合同
		'account_opened' => [

		],

		//委托扣款授权书
		'delegated' => [

		],

		//车辆买卖合同(新车主签)
		'vehicle_sale' => '',

		//车辆租赁合同
		'vehicle_rental_license' => [
			'1' => 'is_pledge_not_use',
		],

		//公司授权委托书
		'power_of_attorney' => [
			'1' => 'is_transfer_company_use',
		],

		//抵押合同
		'mortgage' => [
			'1' => 'is_vehicles_loan',
		],

		//质押合同
		'pledge' => [
			'1' => 'is_vehicles_loan',
		],

		//车辆买卖合同
		'vehicle_trading' => [
			'1' => 'is_relation_vehicle_number',
		],

		//借款咨询及管理服务
		'financing' => '',

		//告知函
		//'letter' => '',

		//委托书
		//'entrust' => '',

		//委托代理书
		//'agency' => '',

		//借款合同补充协议
		'loan_supplement' => '',

		//借条延期申请
		'IOU_extension_application' => '',

		//担保协议
		//'security_agreement' => '',

		//租车协议
		//'rental_agreement' => '',

		//购车款收据
		//'vehicle_payment' => '',

		//文书送达地址确认协议
		//'validation_protocol_confirm' => '',

		//授权过户委托书
		'authorized_transfer' => [
			'1' => 'can_use_stores',
			'2' => 'is_previous_loan_specific',
		],

		//债权债务确认书
		'debt_confirm' => '',

	];

	//还款方式
	public $repay_style = [
		'0' => '全部',
		'1' => '等额本息',
		'2' => '先息后本',
	];

	//影响参数值
	public $influence_param_value = [
		//共签值
		'is_sign' => [
			'name' => '是否共签',
			'is_multiselect' => 2,
			'label_name' => 'is_sign',
			'value' => [
				'0' => '全部',
				'1' => '共签',
				'2' => '非共签',
			]
		],

		//还款期数
		'repay_period' => [
			'name' => '还款期数',
			'label_name' => 'repay_period',
			'is_multiselect' => 2,
			'value' => [
				'1' => '1期',
				'3' => '3期',
				'6' => '6期',
				'12' => '12期',
				'24' => '24期',
				'36' => '36期',
			]
		],

		//多车共贷时使用
		'is_vehicles_loan' => [
			'name' => '是否多车过户',
			'label_name' => 'is_vehicles_loan',
			'is_multiselect' => 2,
			'value' => [
				'1' => '是',
				'2' => '否',
			]
		],

		//是否关联车辆数
		'is_relation_vehicle_number' => [
			'name' => '是否关联车辆数',
			'label_name' => 'is_relation_vehicle_number',
			'is_multiselect' => 2,
			'value' => [
				'1' => '是',
				'2' => '否',
			],
		],

		//过户时使用
		'is_transfer_use' => [
			'name' => '是否是过户时使用',
			'label_name' => 'is_transfer_use',
			'is_multiselect' => 2,
			'value' => [
				'1' => '是',
				'2' => '否',
			]
		],

		//过户到公司时使用
		'is_transfer_company_use' => [
			'name' => '是否过户公司时使用',
			'label_name' => 'is_transfer_company_use',
			'is_multiselect' => 2,
			'value' => [
				'1' => '是',
				'2' => '否',
			],
		],

		//质押贷时不使用
		'is_pledge_not_use' => [
			'name' => '是否是质押贷时不使用',
			'label_name' => 'is_pledge_not_use',
			'is_multiselect' => 2,
			'value' => [
				'1' => '是',
				'2' => '否',
			],
		],

		//哪些门店可用
		'can_use_stores' => [
			'name' => '哪些门店可用',
			'label_name' => 'can_use_stores',
			'is_multiselect' => 1,
			'value' => [
				'1' => '江宁',
				'2' => '新街口',
			],
		],

		//哪些门店不可用
		'not_can_use_stores' => [
			'name' => '哪些门店不可用',
			'label_name' => 'not_can_use_stores',
			'is_multiselect' => 1,
			'value' => [
				'3' => '马鞍山'
			],
		],

		//前笔贷款特定时使用
		'is_previous_loan_specific' => [
			'name' => '是否是前笔贷款特定时使用',
			'label_name' => 'is_previous_loan_specific',
			'is_multiselect' => 2,
			'value' => [
				'1' => '是',
				'2' => '否',
			],
		],

		//转业务贷款为等额本息时使用
		'is_transfer_loan_month' => [
			'name' => '是否是转业务贷款为等额本息时使用',
			'label_name' => 'is_transfer_loan_month',
			'is_multiselect' => 2,
			'value' => [
				'1' => '是',
				'2' => '否',
			],
		],
	];

	/**
	 * 自定义初始化
	 */
	protected function initialize(){
		parent::initialize();
	}

	/**
	 * 缓存内数据处理
	 *
	 * @param array $info
	 */
	public function moreInfo($info) {
		$detail_config_info = model('contract_detail_config')->getInfo($info['detail_config_id']);

		/****展示出必选的参数值***/
		$info['version_number_name'] = model('contract_template_config')->version_number[$info['version_number']] . ' ';//版本
		$info['repay_style_name'] = model('contract_template_config')->repay_style[$info['repay_style']] . ' ';//还款凡是
		$info['format_start_time'] = date('Y-m-d', $info['start_time']) . ' ';//开始时间

		/****循环出所有的可选参数值****/
		if (isset($this->contract_to_param[$detail_config_info['contract_name']])) {
			foreach (model('contract_template_config')->contract_to_param[$detail_config_info['contract_name']] as $key => $val) {
				$value_array = $this->influence_param_value[$val];//参数数组数组
				$value = $info[$val];//参数值
				if ($value_array['is_multiselect'] == 1) {
					$info[$val.'_name'] = '';
					$multiselect_value_array = explode(',', $info[$val]);
					$multiselect_value_name = '';
					foreach ($multiselect_value_array as $k => $v) {
						if (isset($value_array['value'][$v])) {
							$multiselect_value_name .= $value_array['value'][$v] . ',';
						}
					}
					$multiselect_value_name = rtrim($multiselect_value_name, ',');
					$info[$val.'_name'] = $multiselect_value_name;
				} else {
					$info[$val.'_name'] = isset($value_array['value'][$value]) ? $value_array['value'][$value] : '';
				}
			}
		}

		return $info;
	}
}