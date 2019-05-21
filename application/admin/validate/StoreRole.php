<?php
namespace app\admin\validate;

use app\common\validate\Base;

/**
 * 门店角色验证类
 * Class StoreRole
 * @package app\admin\validate
 * @author zhangkx
 * @date 2019/4/26
 */
class StoreRole extends Base {

    protected $regex = [
        'name_regular'      => '/^[a-zA-Z][a-zA-Z0-9_-]{4,10}$/',
        'phone_regular'     => '/^1\d{10}$/',
        'telephone_regular' => '/0\d{2,3}-\d{7,8}/'
    ];

    protected $rule = [
        ['name'     , 'require|unique:StoreRole' , '角色名称不能为空|角色名称已存在，请重新输入'],
        ['pid  '    , 'require'                                     ,'请选择门店'],
    ];

    protected $scene = [
        'add'       => 'name',
        'edit'      => 'name',
    ];

}