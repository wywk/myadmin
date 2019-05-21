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
 *  角色管理服务类
 * Class AuthGroup
 * @package app\admin\service
 * @author zhangkx
 * @date 2019/4/18
 */
class AdminRole extends Base {

    use \traits\controller\Jump;

    /**
     * 自定义初始化
     */
    protected function initialize(){
        parent::initialize();
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
        $sort = input('post.sort/d');
        if (empty($name)) {
            return $this->error("请填写角色名称");
        }
        $result = [
            'id' => $id,
            'name' => $name,
            'pid' => $pid,
            'sort' => $sort,
        ];
        return $result;
    }

    /**
     * 执行删除
     * @author zhangkx
     * @date 2019/4/22
     * @param $id
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function goDrop($id){
        $data = $this->where('pid','in',$id)->where('mark',1)->select();
        if ($data) {
            return $this->error("当前角色下有其他角色，不能删除");
        }
        $result = $this->doDrop($id);
        if ($result !== false) {
            return $this->success("操作成功！");
        } else {
            return $this->error("操作失败！");
        }
    }

    /**
     * 权限菜单数据组装校验
     * @author zhangkx
     * @date 2019/4/18
     */
    public function buildDataMenu(){
        $id         = input('post.id/d');
        $menu_ids   = input('post.menu_ids');
        if(empty($id)){
            return $this->error("参数错误！");
        }
        if(empty($menu_ids)){
            return $this->error("请勾选权限！");
        }
        $data = array(
            'id'=>$id,
            'rules'=>$menu_ids
        );
        return $data;
    }

    /**
     * 改多级分类为2级分类
     * @author Run
     * @date 2019/5/14
     * @param $data,$fid,$level
     */
    public function digui($data, $pid,$level = 0){
        if ($pid == 0) {
            return $pid;
        } else {
            foreach($data as $v) {
                if ($v['id'] == $pid) {
                    if ($v['pid'] == 0) {
                        return $pid;
                    } else {
                        return $this->digui($data, $v['pid'],$level+1);
                    }
                }
            }
        }
        return 0;
    }

    /**
     * Notes:根据用户id获取权限菜单
     * Author: fp
     * Date: 2019/5/10 0010
     * Time: 下午 5:15
     * @param $id int 用户userId
     */
    public function getAuthMenu($userId=0, $controller = ''){
        if(!$userId){
            return [];
        }

        $admin_user     = model('AdminUser','service');
        $admin_menu     = model('AdminMenu','service');

        //权限检查
        if ($userId != IS_ROOT) {
            //获取当前登陆用户角色对应的菜单权限
            $role_ids   = $admin_user->getRoleId($userId);
            $menu_ids   = $this->getMenuId($role_ids);
            $arr        = ['id'=> ['in',$menu_ids], 'mark' => 1];
            if ($controller) {
                $arr = array_merge($arr, ['controller'=>$controller,'action'=>['neq','index']]);
            }
        } else {
            $arr        = ['mark' => 1];
        }

        $admin_menus    = $admin_menu->getAdminMenu($arr);

        if (!$controller) {
            import('tree', EXTEND_PATH,'.lib.php');
            $treeObj = new \Tree();
            $treeObj ->init($admin_menus);
            $navs    = $treeObj->get_tree_array(0, 1);
        } else {
            $navs = $admin_menus;
        }
        return $navs;
    }

    /**
     * Notes:根据用户角色id获取权限菜单
     * Author: fp
     * Date: 2019/5/10 0010
     * Time: 下午 5:15
     * @param $role_ids string 角色id
     */
    public function getMenuId($role_ids){
        $rule  = $this->where('id','in',$role_ids)->column('rules');
        $rules = $this->remDuplicate($rule);
        return $rules;
    }

    /**
     * Notes:用户角色权限id去重
     * Author: fp
     * Date: 2019/5/10 0010
     * Time: 下午 5:15
     * @param $rules array 角色id
     */
    public function remDuplicate($rules){
        foreach ($rules as &$value) {
            $value = trim($value,',');
        }
        $rule_ids = '';
        $rule_ids = implode(',',array_filter($rules));
        $rules = array_unique(explode(',',$rule_ids));
        return $rules;
    }
}