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

// 定义根域名
//define('URL_DOMAIN', '.ship2p.com/');

// 定义网站域名
//define('SITE_URL', 'http://www.zkkm.com');

// 定义手机站域名
//define('M_URL', 'http://m.ship2p.com/');

// 定义静态域名
//define('IMG_URL', 'http://img.ship2p.com/');

// 定义文章内容图片域名
//define('PIC_URL', 'http://pic.ship2p.com/');

// 定义上传域名
//define('UPLOAD_URL', SITE_URL . 'uploads/');

// 定义临时上传域名
//define('UPLOAD_TEMP_URL', SITE_URL . 'uploads/temp/');

// 定义上传目录
//define('UPLOAD_PATH', __DIR__ . '/../public/uploads/');

// 定义临时上传目录
//define('UPLOAD_TEMP_PATH', __DIR__ . '/../public/uploads/temp/');

// 定义文章内容图片目录
//define('PIC_PATH', __DIR__ . '/../public/uploads/pic/');

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
//        '__WEB_ROOT__' => ROOT_PATH,
//        '__UPLOAD__'   => UPLOAD_PATH,
        '__CSS__'    => '/static/admin/css',
        '__IMG__'    => '/static/admin/images',
        '__JS__'     => '/static/admin/js',
        '__css__'    => '/static/css',
        '__img__'    => '/static/images',
        '__js__'     => '/static/js',
        '__font__'   => '/static/font',
        '__boot__'   => '/static/bootstrap',
        '__LAYUI__'  => '/static/layui',
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
//    // +----------------------------------------------------------------------
//    // | 极验验证,请到官网申请ID和KEY，http://www.geetest.com/
//    // +----------------------------------------------------------------------
//    'verify_type' => '1',   //验证码类型：0极验验证， 1数字验证码
//    'gee_id'  => 'ca1219b1ba907a733eaadfc3f6595fad',
//    'gee_key' => '9977de876b194d227b2209df142c92a0',
];