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

namespace app\common\controller;

use think\Controller;

/**
 * API控制器基类
 *
 * @author	matengfei
 * @date    2017-08-01
 * @version 1.0
 */
class Api extends Controller {

	public function _initialize(){
		parent::_initialize();

		header("Access-Control-Allow-Origin: *");
		header("Access-Control-Allow-Headers: Content-Type");
	}
	
	protected function message($status=200, $msg='', $data=[]) {
		
		header("Content-Type:application/json; charset=UTF-8");
		echo json_encode(['status'=>$status,'msg'=>$msg,'data'=>$data]);
		exit;
	}

}
