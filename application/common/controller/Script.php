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

/**
 * 脚本控制器基类
 *
 * @author	matengfei
 * @date    2017-08-01
 * @version 1.0
 */
class Script extends Base {
	
	protected $totalNum, $countIndex, $nowPercent, $lastPercent;
	
	/**
	 * 初始化方法
	 *
	 */
	public function _initialize(){
		parent::_initialize();
	}
	
	/**
	 * 启动进度统计
	 *
	 * @param 	int $totalNum
	 * @return	void
	 */
	protected function startCount($totalNum){
		$this->totalNum = $totalNum;
		$this->countIndex = 0;
		$this->lastPercent= 0;
	}
	
	/**
	 * 输出当前进度
	 *
	 * @param 	string $desc
	 * @return	void
	 */
	protected function putCount($desc='Current percent is : '){
		$this->countIndex++;
		if (OS != 'linux') {
			$desc = iconv('UTF-8', 'gbk', $desc);
		}
		$this->nowPercent = (int)($this->countIndex*100/$this->totalNum);
		if ($this->nowPercent > $this->lastPercent) {
			echo "{$desc}{$this->nowPercent}%\n";
		}
		$this->lastPercent = $this->nowPercent;
	}
	
}