<?php
namespace app\admin\controller;

use app\common\controller\Backend;

/**
 * 来源管理控制器
 * @author zhangkx
 * @date 2019/4/18
 */
class SystemGalleryGroup extends Backend
{

    protected $model, $service;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('SystemGalleryGroup');
        $this->service = model('SystemGalleryGroup', 'service');
    }

    /**
     * 来源列表
     * @author zhangkx
     * @date 2019/4/19
     */
    public function index()
    {
        $cond['mark'] = 1;
        if ($this->params['page'] && $this->params['limit']) {
            $list = $this->model->getList($cond, '*', 'sort asc ,id DESC', $this->params['page'], $this->params['limit']);
            $data = ['code' => 0,'count' => $this->model->getCount($cond), 'msg' => 'ok', 'data'  => $list];
            return json($data);
        }
        return $this->fetch();
    }
    /**
     * 文件分类--添加
     * @author	Run
     * @date    2019-04-18
     */
    public function add() {
        if(IS_POST){
            if(method_exists($this->service, 'buildDataEdit')){
                $arr = $this->service->buildDataEdit();
            }
            if(method_exists($this->service, 'goEdit')){
                $this->service->goEdit($arr);
            }
        }
        return $this->fetch();
    }
    /**
     * 文件分类--编辑
     * @author	Run
     * @date    2019-04-18
     */
    public function edit() {
        $id     = input('param.id');
        $info = $this->model->getInfo($id);
        if(IS_POST){
            if(method_exists($this->service, 'buildDataEdit')){
                $arr = $this->service->buildDataEdit();
            }
            if(method_exists($this->service, 'goEdit')){
                $this->service->goEdit($arr);
            }
        }
        $this->assign('info',$info);
        $this->assign('id',$id);
        return $this->fetch();
    }

    /**
     * 删除
     * @author	Run
     * @date    2019-04-11
     */
    public function drop() {
        $id         = input('post.id');
        if( empty($id) ){
            return $this->error("参数错误！");
        }
        if( IS_POST ){
            if(method_exists($this->service, 'goDrop')){
                $this->service->goDrop($id);
            }
        }
    }

}
