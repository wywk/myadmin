<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2017 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: matengfei <matengfei2000@gmail.com>
// +----------------------------------------------------------------------
namespace app\common\validate;

use think\Validate;

/**
 * 验证基类
 *
 * @author	matengfei
 * @date    2017-08-01
 * @version 1.0
 */
class Base extends Validate {

	protected function requireIn($value, $rule, $data){
		if (is_string($rule)) {
			$rule = explode(',', $rule);
		} else {
			return true;
		}
		$field = array_shift($rule);
		$val = $this->getDataValue($data, $field);
		if (!in_array($val, $rule) && $value == '') {
			return false;
		} else {
			return true;
		}
	}
	
}
