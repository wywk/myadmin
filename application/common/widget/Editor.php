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

namespace app\common\widget;

use think\Controller;

/**
 * 编辑器插件
 *
 * @author	matengfei
 * @date    2017-08-01
 * @version 1.0
 */
class Editor extends Controller {
	
	private $type;
	
	public function _initialize(){
		
		$this->type = [
			'default',
			'simple',
			'qq',
		];
	}
	
	//kindeditor编辑器
    public function kind($type='default', $name='content', $width=850, $height=500){
    	if (!in_array($type, $this->type)) {
    		$type = $this->type[0];
    	}
    	$this->assign('type', $type);
    	$this->assign('name', $name);
    	$this->assign('width', $width);
    	$this->assign('height', $height);
    	$this->view->engine->layout(false);
    	return $this->fetch('editor/kind');
    }
    
}