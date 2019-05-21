<?php
namespace app\common\model;

/**
 * 角色模型
 * @author zhangkx
 * @date 2019/4/18
 */
class AdminRole extends Base {

    public $status = [
        1 => '启用',
        2 => '停用'
    ];

    /**
     * 自定义初始化
     */
    protected function initialize(){
        parent::initialize();
    }
}