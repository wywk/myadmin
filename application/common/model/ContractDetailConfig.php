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
 * 合同详细配置模型
 *
 * @author	zhangjun
 * @date 	2017-08-17
 * @version 1.0
 */
class ContractDetailConfig extends Base {
	/**
	 * 自定义初始化
	 */
	protected function initialize(){
		parent::initialize();
	}

	/**
	 * 关联贷款合同配置数据缓存内处理
	 * @author	zhangjun
	 * @date 	2017-08-17
	 */
	public function moreInfo($info) {

		/****合同名称****/
		$info['contract_name_show'] = '';
		if (!empty($info['contract_name'])) {
			$info['contract_name_show'] = model('contract_config')->contract_name[$info['contract_name']];
		}

		return $info;
	}

	/**
	 * 关联贷款合同配置数据缓存外处理
	 *
	 * @param array $info
	 * @return array
	 */
	public function relationInfo($info) {
		$contract_requirements = '';
		$info['list_contract_requirements'] = '';
		if ($info['id']) {
			$where['mark'] = 1;
			$where['detail_config_id'] = $info['id'];
			$template_config_list = model('contract_template_config')->getColumn($where, 'id');
			$contract_requirements = '';
			foreach ($template_config_list as $key => $val) {
				$template_info = model('contract_template_config')->getInfo($val);//模板数据
				$temp_contract_requirement = '';

				/****展示出必选的参数值***/
				$temp_contract_requirement .= '版本号:' . $template_info['version_number_name'] . ' ';//版本
				$temp_contract_requirement .= '还款方式:' .$template_info['repay_style_name'] . ' ';//合同要求
				$temp_contract_requirement .= '开始时间:' .$template_info['format_start_time'] . ' ';//开始时间

				/****循环出所有的可选参数值****/
				if (isset(model('contract_template_config')->contract_to_param[$info['contract_name']])) {
					foreach (model('contract_template_config')->contract_to_param[$info['contract_name']] as $k => $v) {
						$value_array = model('contract_template_config')->influence_param_value[$v];
						$value = $template_info[$v];//参数值
						$temp_contract_requirement .= $value_array['name'] . ':' . $template_info["{$v}_name"];
						$contract_requirements .= $temp_contract_requirement . ' ';
					}
				}
				$info['list_contract_requirements'] .= strlen($temp_contract_requirement) > 80 ? mb_substr($temp_contract_requirement, 0, 80, 'UTF-8' ) . '...' : $temp_contract_requirement;
				$info['list_contract_requirements'] .= '<br>';
			}
		}

		//完整的合同要求
		$info['contract_requirements'] = $contract_requirements;//完整的合同要求

		return $info;
	}
	
}