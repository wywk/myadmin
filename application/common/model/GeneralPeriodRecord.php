<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2017 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: lyl
// +----------------------------------------------------------------------

namespace app\common\model;

/**
 * 综合时间区间记录表 模型
 * 
 * @author lyl
 * @date 2017-8-9
 * @version 1.0
 */
class GeneralPeriodRecord extends Base {
	
	protected $insert = [];
	protected $update = [];
	
	const STORE_AREA = 'store-area';
	const STORE_CHARGEMEMBER = 'store-chargemember';
	const STORE_SALARYTYPE = 'store-salarytype';
	
	
	/**
	 * 自定义初始化
	 */
	public function initialize(){
		parent::initialize();
		
		$this->insert = ['create_user'=>session('user_auth.uid'), 'update_user'=>session('user_auth.uid')];
		$this->update = ['update_user'=>session('user_auth.uid')];
	}
	
	/*
	 * 获取指定业务类型的当前属性
	 */
	public static function CurrentInfo($type, $master_id, $time=null) {
		$time || $time=time();
		return db('general_period_record')
			->where(['type'=>$type,'master_id'=>$master_id,'mark'=>1])
			->where(['begin_time'=>['<',$time]]) // 开始时间已过  ~~且尚未设置结束时间~~
			->order('begin_time','desc') // 取begin_time最近的记录
			->limit(0,1);
	}
	
	/*
	 * 获取指定业务类型的代生效的新属性
	 */
	public static function FutureInfo($type, $master_id, $time=null) {
		$time || $time=time(); 
		return db('general_period_record')
			->where(['type'=>$type,'master_id'=>$master_id,'mark'=>1])
			->where(['begin_time'=>['>',$time]]);
	}
	
	/*
	 * 添加/修改未生效数据
	 */
	public function editFuture($data) {
		$type = getter($data, 'type');
		$master_id = getter($data, 'master_id');
		$begin_time = getter($data, 'begin_time');
		if ($type && $master_id && $begin_time) {
			$future_info = $this->FutureInfo($type, $master_id)->find();
			$future_id = getter($future_info, 'id');
			$data['id'] = $future_id;
			$futurn_id = $this->edit($data, false, true);
			if ($futurn_id) {
				return $futurn_id;
			} else {
				return false;
			}
		}
		return false;
	}
	
}