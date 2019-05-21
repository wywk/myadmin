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
 * 城市模型
 * 
 * @author luffy
 * @date 2017-8-12
 * @version 1.0
 */
class City extends Base {
	
	/**
	 * 自定义初始化
	 */
	public function initialize(){
		parent::initialize();
	}
    /**
     * 省市区数据
     */
	public function getProvince($parent_id = 1,$selected =''){
	    $arr = array();
        $cond['parent_id'] = $parent_id;
        $list   = $this->getList($cond, 'id,name', $order='id asc', $page = 0, $limit = 99999);
        foreach ($list as $k =>$v){
            $arr[$v['id']] = $v['name'];
        }
        $field_option = make_option($arr, $selected,'name');
        return $field_option;
    }

}