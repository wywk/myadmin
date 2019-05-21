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
use think\db;

/**
 * 后台管理员服务类
 * @author   luffy
 * @date     2019-04-03
 * @version  1.0
 */
class AdminUser extends Base {

    use \traits\controller\Jump;
    protected $password;

    /**
     * 自定义初始化
     */
    protected function initialize(){
        parent::initialize();
        $this->password = '123456';
    }

    /**
     * 数据组装校验--添加编辑
     * @author   luffy
     * @date     2019-04-11
     */
    public function buildData($userId){
        $id         = input('post.id/d');
        $name       = input('post.name/s');
        $phone      = input('post.phone/s');
        $telephone  = input('post.telephone/s');
        $open       = input('post.open');
        $roleIds  = input('post.role_ids/s');
        $arr            =   [
            'name'      =>  $name,
            'phone'     =>  $phone,
            'telephone' =>  $telephone,
            'status'    =>  $open == 'on' ? 1 : 2,
            'role_ids' =>  $roleIds,
        ];
        if ($id) {
            $arr['id']          = $id;
            $scene              = 'edit';
        } else {
            $arr['password']    = password($this->password.$name);
            $arr['add_user']    = $userId;
            $arr['add_time']    = time();
            $scene              = 'add';
        }
        //校验
        if (!validate('AdminUser')->check($arr, [], $scene)) {
            return $this->error(validate('AdminUser')->getError());
        }
        return  $arr;
    }

    /**
     * 数据组装校验--修改密码
     * @author   luffy
     * @date     2019-04-11
     */
    public function buildDataPassword(){
        $id             = input('post.id/d');
        $password       = input('post.password/s');
        $enter_password = input('post.enter_password/s');
        if($password    != $enter_password){
            return $this->error("您两次输入的密码不一致，请重新输入！");
        }
        $info           = $this->getInfo($id);
        $arr            =   [
            'id'            =>  $id,
            'password'      =>  password($password.$info['name'])
        ];
        return  $arr;
    }

    /**
     * 执行编辑
     * @author   luffy
     * @date     2019-04-11
     */
    public function goEdit($arr){
        $result     = $this->edit($arr);
        if (false !== $result) {
            return $this->success("操作成功！", url('admin/AdminUser/index'));
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


    /**
     * 获取用户角色id
     *@param $id int 用户id
     *renturn $role_ids array
     */
    public function getRoleId($id){
        $role_ids = $this->where(['id'=>$id,'mark'=>1,'status'=>1])->value('role_ids');
        return $role_ids;
    }
}