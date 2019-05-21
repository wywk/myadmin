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

namespace app\admin\controller;

use app\common\controller\Backend;

/**
 * 后台用户管理控制器
 * @author	luffy
 * @date    2019-04-01
 */
class AdminUser extends Backend {

    protected $model;

    public function _initialize(){
        parent::_initialize();
        $this->model = model('AdminUser');
    }

    /**
     * 列表页
     * @author	luffy
     * @date    2019-04-01
     */
    public function index() {
        $cond['mark'] = 1;
        $this->fuzzyCond($cond, 'name');        //账号名称
        $this->fuzzyCond($cond, 'phone');       //手机号码
        $this->fuzzyCond($cond, 'telephone');   //座机号码
        $this->queryCond($cond, 'status');      //账号状态
        $this->timeCond($cond);                             //操作时间
        if( $this->params['page'] && $this->params['limit']){
            $list   = $this->model->getList($cond, '*', 'status ASC,id DESC', $this->params['page'], $this->params['limit']);
            $data   = ['code' => 0,'count' => $this->model->getCount($cond), 'msg' => 'ok', 'data'  => $list];
            return json($data);
        }
        return $this->fetch();
    }

    /**
     * 添加
     * @author	luffy
     * @date    2019-04-01
     */
    public function add() {
        if( IS_POST ){
            $adminUserService = model('AdminUser', 'service');
            if(method_exists($adminUserService, 'buildData')){
                $arr = $adminUserService->buildData($this->userId);
            }
            if(method_exists($adminUserService, 'goEdit')){
                $adminUserService->goEdit($arr);
            }
        }
        //获取角色信息
        $roleModel = model('AdminRole');
        $roleData = $roleModel->getList(['mark'=>1], '*', 'sort ASC,id DESC');
        $this->assign('roleData', $roleData);
        return $this->fetch();
    }

    /**
     * 编辑
     * @author	luffy
     * @date    2019-04-03
     */
    public function edit() {
        $id = input('param.id');
        if( empty($id) ){
            return $this->error("参数错误！");
        }
        if( IS_POST ){
            $adminUserService = model('AdminUser', 'service');
            if(method_exists($adminUserService, 'buildData')){
                $arr = $adminUserService->buildData($this->userId);
            }
            if(method_exists($adminUserService, 'goEdit')){
                $adminUserService->goEdit($arr);
            }
        }
        $data = $this->model->getInfo($id);
        $roleIds = explode(',', $data['role_ids']);
        //获取角色信息
        $roleModel = model('AdminRole');
        $roleData = $roleModel->getList(['mark'=>1], '*', 'sort ASC,id DESC');
        $this->assign('roleData', $roleData);
        $this->assign('id', $id);
        $this->assign('data', $data);
        $this->assign('roleIds', $roleIds);
        return $this->fetch();
    }

    /**
     * 修改密码
     * @author	luffy
     * @date    2019-04-09
     */
    public function editPassWord() {
        $id         = input('param.id');
        if( empty($id) ){
            return $this->error("参数错误！");
        }
        if( IS_POST ){
            $adminUserService = model('AdminUser', 'service');
            if(method_exists($adminUserService, 'buildDataPassword')){
                $arr = $adminUserService->buildDataPassword();
            }
            if(method_exists($adminUserService, 'goEdit')){
                $adminUserService->goEdit($arr);
            }
        }
        $this->assign('id'  , $id);
        return $this->fetch();
    }

    /**
     * 设置状态
     * @author	luffy
     * @date    2019-04-03
     */
    public function setStatus() {
        $id         = input('post.id/d');
        $status     = input('post.status/d');
        if( empty($id) || empty($status) ){
            return $this->error("参数错误！");
        }
        if( IS_POST ){
            $adminUserService = model('AdminUser', 'service');
            if(method_exists($adminUserService, 'goEdit')){
                $adminUserService->goEdit(['id'=>$id, 'status'=>$status]);
            }
        }
    }

    /**
     * 删除
     * @author	luffy
     * @date    2019-04-03
     */
    public function drop() {
        $id         = input('post.id');
        if( empty($id) ){
            return $this->error("参数错误！");
        }
        if( IS_POST ){
            $adminUserService = model('AdminUser', 'service');
            if(method_exists($adminUserService, 'goDrop')){
                $adminUserService->goDrop($id);
            }
        }
    }
}
