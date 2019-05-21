<?php

namespace app\admin\controller;

use app\common\controller\Backend;

/**
 * 首页管理控制器
 * @author	luffy
 * @date    2019-04-01
 */
class Index extends Backend {

    /**
     * 列表页
     * @author	luffy
     * @date    2019-04-01
     */
    public function index() {
        //获取当前登陆用户角色对应的菜单权限
        $adminRole  = model('AdminRole','service');
        $navs       = $adminRole->getAuthMenu($this->userId);
        $this->assign('navs', $navs);
        return $this->fetch();
    }

    /**
     * 添加
     * @author	luffy
     * @date    2019-04-03
     */
    public function add() {
        return $this->fetch();
    }

    /**
     * 编辑
     * @author	luffy
     * @date    2019-04-03
     */
    public function edit() {

    }

    /**
     * 删除
     * @author	luffy
     * @date    2019-04-03
     */
    public function drop() {

    }

    /**
     * 锁屏及解锁
     * @author zhangkx
     * @date 2019/4/22
     */
    public function screenLock()
    {
        if (IS_POST) {
            $id = input('post.id/d');
            $password = input('post.password/s');
            $type = input('post.type/d');
            //锁屏
            if ($type == 1) {
                cookie('lock_status',1 ,3600*24*7);
            } else {  //解锁
                if (empty($id)) {
                    return $this->error("参数错误");
                }
                if (empty($password)) {
                    return $this->error("请输入密码");
                }
                $userMod = model('adminUser', 'model');
                $userData = $userMod->getInfo($id);
                if (!compare_password($password.$userData['name'], $userData['password'])) {
                    return $this->error("密码错误");
                }
                cookie('lock_status',0 ,3600*24*7);
                return $this->success('解锁成功');
            }
        }
    }
}
