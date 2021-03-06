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
 * 员工状态变化表
 * @date: 2017-8-15
 * @author: cc
 */
class UserStateChangeLog extends Base {
    
    protected function initialize(){
        parent::initialize();
        $this->is_cache = false;
    }
}