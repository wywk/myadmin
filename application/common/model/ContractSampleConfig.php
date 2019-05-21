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
 * 合同示例配置模型
 *
 * @author	zhangjun
 * @date 	2017-08-16
 * @version 1.0
 */
class ContractSampleConfig extends Base {
	/**
	 * 自定义初始化
	 */
	protected function initialize(){
		parent::initialize();
	}

	/**
	 * 合同示例配置数据处理
	 * @author zhangjun
	 * @date 2017-8-17
	 *
	 * @param array $info 示例记录
	 * @return array
	 */
	public function moreInfo($info) {
		/****合同名称和版本号****/
		$info['contract_name_show'] = isset($info['contract_name']) ? model('contract_config')->contract_name[$info['contract_name']] : '';//合同名称
		$info['version_number_name'] = isset($info['version_number']) ? model('contract_template_config')->version_number[$info['version_number']] : '';//合同名称

		//有效时间
		$info['format_start_time'] = $info['start_time'] > 0 ? date('Y-m-d', $info['start_time']) : '';

		//备注
		if ($info['remark']) {
			if (strlen($info['remark']) > 10) {
				$info['list_remark'] = mb_substr($info['remark'], 0, 10 ) . '...';
			} else {
				$info['list_remark'] = $info['remark'];
			}
		}

		return $info;
	}
	
}