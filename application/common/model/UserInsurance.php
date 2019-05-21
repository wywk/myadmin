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

class UserInsurance extends Base {
    
    protected function initialize() {
        parent::initialize();
    }
    
    public function user() {
        return $this->belongsTo('user');
    }
}