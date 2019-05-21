<?php
namespace app\common\model;

/**
 * 门店角色模型
 * @author zhangkx
 * @date 2019/4/26
 */
class StoreRole extends Base {

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