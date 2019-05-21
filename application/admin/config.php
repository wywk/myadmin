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
return [
    // +----------------------------------------------------------------------
    // | 模块设置
    // +----------------------------------------------------------------------

    //超级管理员ID
//    'user_administrator' => 1,

    //密钥
//    "authcode" => 'IgtUdEQJyVevaCxQnY',

    //'url_common_param' => true,

    'template' => [
        'tpl_cache'   => false,
        'layout_on'   => true, // 布局模板开关
        'layout_name' => 'layout', // 布局模板入口文件
        'layout_item' => '{__CONTENT__}', // 布局模板的内容替换标识
    ],

    'view_replace_str' => [
        '__JS__'     => '/static/public/js',
        '__LAYUI__'  => '/static/public/layui',
//        '__CSS__'    => '/static/admin/css',
//        '__IMG__'    => '/static/admin/images',
//        '__css__'    => '/static/css',
//        '__img__'    => '/static/images',
//        '__font__'   => '/static/font',
//        '__boot__'   => '/static/bootstrap',
//        '__js__'     => '/static/js',
    ],
//
//    'session' => [
//        'prefix'     => 'think',
//        'type'       => '',
//        'auto_start' => true,
//    ],
//
//    'SP_ADMIN_STYLE' => 'flat', //bluesky or flat
//
//    //分页配置
//    'paginate'      => [
//        'type'      => 'bootstrap',
//        'var_page'  => 'page',
//        'list_rows' => 15,
//    ],
//
//    'allow_visit' => [
//        'admin/test/*',
//        'admin/city/*',
//        'admin/upload/*'
//    ],
//
//    'deny_visit' => [
//
//    ],
//
    // +----------------------------------------------------------------------
    // | 极验验证,请到官网申请ID和KEY，http://www.geetest.com/
    // +----------------------------------------------------------------------
    'verify_type' => '1',   //验证码类型：0极验验证， 1数字验证码
    'gee_id'  => 'ca1219b1ba907a733eaadfc3f6595fad',
    'gee_key' => '9977de876b194d227b2209df142c92a0',
];