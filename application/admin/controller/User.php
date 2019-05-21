<?php
namespace app\admin\controller;

use app\common\controller\Backend;

class User extends Backend
{
    protected $model, $service;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('User');
        $this->service = model('User', 'service');
    }

    /**
     * 用户列表
     * @author zhangkx
     * @date 2019/4/25
     */
    public function index()
    {
        $cond['mark'] = 1;
        $this->fuzzyCond($cond,'user_name');
        $this->fuzzyCond($cond,'phone');
        $this->queryCond($cond,'status');

        $this->timeCond($cond);
        if( $this->params['page'] && $this->params['limit']){
            $list = $this->model->getList($cond, '*', 'status ASC,id DESC', $this->params['page'], $this->params['limit']);
            foreach ($list as $k => &$v) {
                $v['format_source_id'] = $this->model->source[$v['source_id']];
                $v['format_sex'] = $this->model->sex[$v['sex']];
            }
            $data = ['code' => 0,'count' => $this->model->getCount($cond), 'msg' => 'ok', 'data'  => array_values($list)];
            return json($data);
        }
        return $this->fetch();
    }

    /**
     * 添加
     * @author zhangkx
     * @date 2019/4/25
     * @return mixed
     */
    public function add()
    {
        if (IS_POST) {
            if(method_exists($this->service, 'buildData')){
                $arr = $this->service->buildData();
                $result = $this->model->editData('User', $arr, $this->userId);
                if (!$result) {
                    return $this->error("操作失败！");
                }
                return $this->success("操作成功！", url('admin/User/index'));
            }
        }
        //获取门店下拉框
        $storeMod = model('Store', 'service');
        $storeOption = $storeMod->getStoreOption();
        $this->assign('storeOption',$storeOption);
        return $this->fetch();
    }

    /**
     * 编辑
     * @author zhangkx
     * @date 2019/4/26
     * @return mixed|void
     */
    public function edit()
    {
        $id = input('param.id');
        if (empty($id)){
            return $this->error('参数错误！');
        }
        if (IS_POST) {
            if(method_exists($this->service, 'buildData')){
                $arr = $this->service->buildData();
                $result = $this->model->editData('User', $arr, $this->userId);
                if (!$result) {
                    return $this->error("操作失败！");
                }
                return $this->success("操作成功！", url('admin/User/index'));
            }
        }
        $data = $this->model->getInfo($id);
        //获取门店下拉框
        $storeMod = model('Store', 'service');
        $storeOption = $storeMod->getStoreOption($data['store_id']);
        $this->assign('storeOption',$storeOption);
        $this->assign('id',$id);
        $this->assign('data',$data);
        return $this->fetch();
    }

    /**
     * 设置状态
     * @author zhangkx
     * @date 2019/4/26
     */
    public function setStatus()
    {
        if (IS_POST){
            $id     = input('post.id/d');
            $status = input('post.status/d');
            if (empty($id) || empty($status)) {
                return $this->error("参数错误！");
            }
            $data['id'] = $id;
            $data['status'] = $status;
            $result = $this->model->editData('User', $data);
            if (!$result) {
                return $this->error("操作失败！");
            }
            return $this->success("操作成功！");
        }
    }

    /**
     * 修改密码
     * @author zhangkx
     * @date 2019/4/26
     */
    public function editPassWord()
    {
        $id = input('param.id');
        if (empty($id)){
            return $this->error('参数错误！');
        }
        if (IS_POST){
            if(method_exists($this->service, 'buildDataPassword')){
                $arr = $this->service->buildDataPassword();
                $result = $this->model->editData('User', $arr, $this->userId);
                if (!$result) {
                    return $this->error("操作失败！");
                }
                return $this->success("操作成功！", url('admin/User/index'));
            }
        }
        $this->assign('id',$id);
        return $this->fetch('user/editPassword');
    }

    /**
     * 删除
     * @author zhangkx
     * @date 2019/4/26
     */
    public function drop()
    {
        if (IS_POST){
            $id = input('post.id/s');
            if(empty($id)){
                return $this->error('参数错误！');
            }
            $result = $this->model->doDrop($id);
            if (!$result) {
                return $this->error("操作失败！");
            }
            return $this->success("操作成功！");
        }
    }


}
