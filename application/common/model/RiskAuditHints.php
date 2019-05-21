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
 * 风控审核提示状态表管理模型
 *
 * @author luffy
 * @date 2017-8-14
 * @version 1.0
 */
class RiskAuditHints extends Base {
	
	public $is_cache = false;

	/**
	 * 自定义初始化
	 */
	public function initialize(){
		parent::initialize();
	}
	
}