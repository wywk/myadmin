<?php
namespace app\admin\service;

use app\common\model\Base;

/**
 * 后台管理员服务类
 * @author	luffy
 * @date    2018-04-24
 */
class User extends Base {

    use \traits\controller\Jump;
    
    /**
     * 自定义初始化
     */
    protected function initialize(){
        parent::initialize();

    }

    /**
     * 数据校验组装
     * @author zhangkx
     * @date 2019/4/26
     * @return array
     */
    public function buildData()
    {
        $id        = input('post.id/d');
        $user_name = input('post.user_name/s');
        $phone     = input('post.phone/s');
        $password  = input('post.password/s');
        $status    = input('post.status/d');
        $sex       = input('post.sex/d');
        $store_id  = input('post.store_id');
        $logo_path = input('post.logo_path/s');
        $arr            =  [
            'id'        => $id,
            'user_name' => $user_name,
            'phone'     => $phone,
            'sex'       => $sex,
            'source_id' => 2,
            'store_id'  => $store_id,
            'img_url'   => $logo_path,
            'status'    => $status,
        ];
        if ($id) {
            $scene = 'edit';
        } else {
            $scene = 'add';
            $arr['password'] = password($password,$user_name);
        }
        $validate = validate('User');
        if (!$validate->check($arr, [], $scene)) {
            return $this->error($validate->getError());
        }
        if (!$id) {
            $arr['password'] = password($password,$user_name);
        }
        return $arr;
    }

    /**
     * 数据组装校验--修改密码
     * @author zhangkx
     * @date 2019/4/26
     * @return array|void
     */
    public function buildDataPassword()
    {
        $id             = input('post.id/d');
        $password       = input('post.password/s');
        $enter_password = input('post.enter_password/s');
        if (empty($password)) {
            return $this->error('请输入新的密码！');
        }
        if (empty($enter_password)) {
            return $this->error('请输入确认的密码！');
        }
        if ($password != $enter_password) {
            return $this->error('您两次输入的密码不一致,请重新输入！');
        }
        $info = $this->getInfo($id);
        $arr  = [
            'id'       => $id,
            'password' => password($password,$info['user_name'])
        ];
        return $arr;
    }

}