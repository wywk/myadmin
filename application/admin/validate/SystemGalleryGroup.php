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

namespace app\admin\validate;

use app\common\validate\Base;

/**
 * 后台商店列表服务类
 * @author   Run
 * @date     2019-04-015
 * @version  1.0
 */
class SystemGalleryGroup extends Base {

    use \traits\controller\Jump;

    /**
     * 自定义初始化
     */
    protected function initialize(){
        parent::initialize();
        echo 123;die;
    }


    protected $regex = [
        'name_regular'      => '/^[a-zA-Z][a-zA-Z0-9_-]{4,10}$/',
    ];

    protected $rule = [
        ['name'     , 'require|unique:system_gallery_group' , '账号名称不能为空|账号名称已存在，请重新输入！'],
    ];

    protected $scene = [
        'add'       => 'name,',
        'edit'      => 'name',
    ];
}