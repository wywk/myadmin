<?php
namespace app\admin\controller;

use app\common\controller\Backend;
/**
 * 会员管理控制器
 * @author hujp
 * @date 2019-04-16
 */
class StoreUser extends Backend
{
    protected $model;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('StoreUser','service');
    }

    /**
     * 列表页
     * @author hujp
     * @date 2019-04-16
     */
    public function index()
    {
        $cond['mark'] = 1;
        $this->fuzzyCond($cond,'name');
        $this->fuzzyCond($cond,'cate_id');
        $this->timeCond($cond);
        if( $this->params['page'] && $this->params['limit']){
            $list   = $this->model->getList($cond, '*', 'status ASC,id DESC', $this->params['page'], $this->params['limit']);
            foreach($list as $key => $value){
                $list[$key]['format_add_time'] = date('Y-m-d H:i',$value['add_time']);
            }
            $data   = ['code' => 0,'count' => $this->model->getCount($cond), 'msg' => 'ok', 'data'  => $list];
            return json($data);
        }

        // 获取所有分类
        $store_name_arr = model('Store')->selectList();
        $this->assign('store_name_arr',make_option($store_name_arr, ''));
        return $this->fetch('store_user/index');
    }

    /**
     * 添加
     * @author hujp
     * @date 2019-04-17
     * @return mixed
     */
    public function add()
    {
        if (IS_POST){
            $StoreUserService = model('StoreUser','service');
            if(method_exists($StoreUserService, 'buildDataAdd')){
                $arr = $StoreUserService->buildDataAdd();
            }
            if(method_exists($StoreUserService, 'goEdit')){
                $StoreUserService->goEdit($arr);
            }
        }
        // 获取所有分类
        $store_name_arr = model('Store')->selectList();
        $this->assign('store_name_arr',make_option($store_name_arr, ''));
        return $this->fetch('store_user/add');
    }

    /**
     * 编辑
     * @author hujp
     * @date 2019-04-16
     * @return mixed|void
     */
    public function edit()
    {
        $id = input('param.id');
        if (empty($id)){
            return $this->error('参数错误！');
        }

        $data = $this->model->getInfo($id);

        if (IS_POST){
            $StoreUserService = model('StoreUser','service');
            if (method_exists($StoreUserService,'buildDataEdit')){
                $arr = $StoreUserService->buildDataEdit();
            }
            if (method_exists($StoreUserService,'goEdit')){
                $StoreUserService->goEdit($arr);
            }
        }
        // 获取所有分类
        $store_name_arr = model('Store')->selectList();
        $this->assign('store_name_arr',make_option($store_name_arr, $data['store_id']));
        $this->assign('id',$id);
        $this->assign('data',$data);
        return $this->fetch('store_user/edit');
    }

    /**
     * 删除
     * @author hujp
     * @date 2019-04-16
     */
    public function drop()
    {
        $id = input('post.id');
        if(empty($id)){
            return $this->error('参数错误！');
        }
        if (IS_POST){
            $StoreUserService = model('StoreUser','service');
            if (method_exists($StoreUserService,'goDrop')){
                $StoreUserService->goDrop($id);
            }
        }
    }

    /**
     * 设置状态
     * @author hujp
     * @date 2019-04-16
     */
    public function setStatus()
    {
        $id     = input('post.id/d');
        $status = input('post.status/d');
        if (empty($id) || empty($status)){
            return $this->error("参数错误！");
        }

        if (IS_POST){
            $StoreUserService = model('StoreUser','service');
            if (method_exists($StoreUserService,'goEdit')){
                $StoreUserService->goEdit(['id'=>$id,'status'=>$status]);
            }
        }
    }

    /**
     * 设置状态
     * @author hujp
     * @date 2019-04-16
     */
    public function accountLayer()
    {
        $cond['mark'] = 1;
        $this->fuzzyCond($cond,'name');
        if( $this->params['page'] && $this->params['limit']){
            $list   = model('storeUser')->getList($cond, '*', 'status ASC,id DESC', $this->params['page'], $this->params['limit']);
            foreach($list as $key => $value){
                $list[$key]['format_add_time'] = date('Y-m-d H:i',$value['add_time']);
            }
            $data   = ['code' => 0,'count' => $this->model->getCount($cond), 'msg' => 'ok', 'data'  => $list];
            return json($data);
        }

        return $this->fetch('store_user/accountLayer');
    }
}