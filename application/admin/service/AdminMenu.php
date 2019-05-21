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
 * 菜单管理服务类
 * Class AuthGroup
 * @package app\admin\service
 * @author zhangkx
 * @date 2019/4/18
 */
class AdminMenu extends Base {

    use \traits\controller\Jump;

    /**
     * 自定义初始化
     */
    protected function initialize(){
        parent::initialize();
    }

    /**
     * 加载权限按钮
     * @author luffy
     * @date 2019-5-15
     * @param $arr
     */
    public function getAuthMenu(){
        $this->service = model('AdminRole', 'service');
        $getAuthMenu = $this->service->getAuthMenu(is_login(MODULE), 'admin_menu');
        if($getAuthMenu){
            $authMenu = [];
            //获取当前用户当前模块下应有的菜单权限
            foreach($getAuthMenu as $key => $value){
                if($value['action'] == 'add'){
                    $authMenu['addButton']  = '<button class="layui-btn layui-btn-sm j-add" data-href="/admin/admin_menu/add">添加</button>';
                }
                if($value['action'] == 'edit'){
                    $authMenu['editButton'] = '<a class="layui-btn layui-btn-xs j-edit" data-href="/admin/admin_menu/edit" lay-event="edit">编辑</a>';
                }
                if($value['action'] == 'drop'){
                    $authMenu['dropButton'] = '<a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="drop" >删除</a>';
                }
            }
        }
        return $authMenu;
    }

    /**
     * 数据组装校验
     * @author zhangkx
     * @date 2019/4/18
     */
    public function buildData(){
        $id = input('post.id/d');
        $name = input('post.name/s');
        $pid = input('post.pid/d');
        $controller = input('post.controller/s');
        $action = input('post.action/s');
        $param = input('post.param/s');
        $sort = input('post.sort/d');
        $remark = input('post.remark/s');
        if (empty($name)) {
            return $this->error("请填写角色名称");
        }
        $result = [
            'id' => $id,
            'name' => $name,
            'pid' => $pid,
            'controller' => $controller,
            'action' => $action,
            'param' => $param,
            'sort' => $sort,
            'remark' => $remark,
        ];
        return $result;
    }

    /**
     * 执行编辑
     * @author zhangkx
     * @date 2019/4/18
     * @param $arr
     */
    public function goEdit($arr){
        $result     = $this->edit($arr);
        if (false !== $result) {
            return $this->success("操作成功！", url('admin/AdminMenu/index'));
        } else {
            return $this->error("操作失败！");
        }
    }

    /**
     * 执行删除
     * @author zhangkx
     * @date 2019/4/19
     * @param $id
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function goDrop($id){
        $data = $this->where('pid','in',$id)->where('mark',1)->select();
        if ($data) {
            return $this->error("当前菜单下有其他菜单，不能删除");
        }
        $result = $this->doDrop($id);
        if ($result !== false) {
            return $this->success("操作成功！");
        } else {
            return $this->error("操作失败！");
        }
    }

    /**
    * 获取菜单权限
    * @author	fup
    * @date    2019-05-13
    *@param $ids array 菜单id 
    */
    public function getAdminMenu($arr = []){
        $result = $this->getList($arr, 'id,pid,name,controller,action,param,css,icon,sort', 'sort ASC');
        return $result;
    }
}