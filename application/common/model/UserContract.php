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
 * 用户合同表模型
 * @date: 2017-8-14
 * @author: cc
 */
class UserContract extends Base{
    
    protected function initialize() {
        parent::initialize();
        $this->is_cache = false;
    }
    
    public function user() {
        return $this->belongsTo('user');
    }
}