<?php
namespace app\admin\validate;

use app\common\validate\Base;

/**
 * 后台管理员验证类
 * @author zhangkx
 * @date 2019/4/26
 */
class User extends Base
{
    protected $regex = [
        'user_name_regular'      => '/^[a-zA-Z][a-zA-Z0-9_-]{4,10}$/',
        'phone_regular'     => '/^1\d{10}$/',
//        'password_regular'  => '/(?=.*[0-9])(?=.*[a-z])(?=.*[A-Z]).{8,16}/' //正则表达式6-16位字符（英文/数字/符号）三种组合
    ];

    protected $rule = [
        ['user_name' , 'require|unique:User|regex:user_name_regular' , '用户名称不能为空|用户名称已存在，请重新输入！|请输入正确的用户名称'],
        ['phone'     , 'require|unique:User|regex:phone_regular', '手机号码不能为空|手机号码已存在，请重新输入！|请输入正确的手机号码'],
        ['password'  , 'require', '密码不能为空']
    ];

    protected $scene = [
        'add'      => 'user_name,phone,password',
        'edit'     => 'user_name,phone'
    ];
}