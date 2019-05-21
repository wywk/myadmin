<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2017 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: daixiantong <dai.xiantong@njswtz.com>
// +----------------------------------------------------------------------

namespace app\common\model;

use app\common\model\GeneralPeriodRecord as Period;

/**
 * 系统管理 - 组织架构 - 门店管理 模型
 * 
 * @author daixiantong
 * @date 2017-8-7
 * @version 1.0
 */
class Store extends Base {
	
	protected $insert = [];
	protected $update = [];
	
	public $store_state = [
		1	=> '待营业',
		2	=> '试营业',
		3	=> '营业中',
		4	=> '停业整顿',
		5	=> '已关闭'
	];
	
	public $store_level = [
		1	=> 'A类',
		2	=> 'B类',
		3	=> 'C类',
		4	=> 'D类',
		5	=> 'E类'
	];
	
	public $salary_type = [
		1	=> '薪资方案一',
		2	=> '薪资方案二',
	];
	
	public $keyList;
	
	/**
	 * 自定义初始化
	 */
	public function initialize(){
		parent::initialize();
		$this->insert = ['create_user'=>session('user_auth.uid'), 'update_user'=>session('user_auth.uid')];
		$this->update = ['update_user'=>session('user_auth.uid')];
		$this->keyList = [
			'store_state' => $this->store_state,
			'store_level' => $this->store_level,
			'salary_type' => $this->salary_type,
		];
	}

    /**
     * 缓存门店数据及数据格式化
     */
    public function doUpdate($data=array(),$id= ''){
        $res = $this->doEdit($data);
        if($res){
            return json(['code' => 1, 'msg' => '添加成功', 'url' => 'add']);
        }else{
            return json(['code' => 0, 'msg' => '添加失败', 'url' => 'add']);
        }
    }

    /**
     * 门店列表
     */
    public function selectList(){
        $selectList = $this->order('id disc')->field('id,name')->column('name', 'id');
        return $selectList;
    }
	
}