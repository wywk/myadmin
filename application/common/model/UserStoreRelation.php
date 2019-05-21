<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2017 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: cc <chen.chen@njswtz.com>
// +----------------------------------------------------------------------

namespace app\common\model;

/**
 * 员工门店对应关系模型
 * @date: 2017-8-16
 * @author: cc
 */
class UserStoreRelation extends Base {
    
    // 关闭自动写入时间戳
    protected $autoWriteTimestamp = false;
    
    protected function initialize(){
        parent::initialize();
    }
}