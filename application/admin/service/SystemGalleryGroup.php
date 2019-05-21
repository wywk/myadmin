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

namespace app\admin\service;

use app\common\model\Base;

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
    }
/**
     * 执行编辑
     * @author   luffy
     * @date     2019-04-11
     */
    public function buildDataEdit(){
        $id       = input('post.id/d');
        $sort       = input('post.sort');
        $name       = input('post.name/s');

        $arr = [
            'id' => $id,
            'name' => $name,
            'sort' => $sort,
        ];
        if($id){
            $arr['upd_time'] = time();
            $scene = 'edit';
        }else{
            $arr['add_time'] = time();
            $arr['add_user'] = is_login();
            $scene = 'add';
        }
        //校验
        if (!validate('SystemGalleryGroup')->check($arr, [], $scene)) {
            return $this->error(validate('SystemGalleryGroup')->getError());
        }
        return $arr;
    }
/**
     * 执行编辑
     * @author   luffy
     * @date     2019-04-11
     */
    public function goEdit($arr){
        $result     = $this->edit($arr);
        if (false !== $result) {
            return $this->success("操作成功！", url('admin/test/index'));
        } else {
            return $this->error("操作失败！");
        }
    }

    /**
     * 执行删除
     * @author   luffy
     * @date     2019-04-11
     */
    public function goDrop($id){
        $result     = $this->doDrop($id);
        if (false !== $result) {
            return $this->success("操作成功！");
        } else {
            return $this->error("操作失败！");
        }
    }

}