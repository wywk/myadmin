<?php
namespace app\admin\controller;

use app\common\controller\Backend;

/**
 * 来源管理控制器
 * @author zhangkx
 * @date 2019/4/18
 */
class AdminSource extends Backend
{

    protected $model, $service;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('AdminSource');
        $this->service = model('AdminSource', 'service');
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
            $list = $this->model->getList($cond, '*', 'sort ASC, id DESC', $this->params['page'], $this->params['limit']);
            $data = ['code' => 0,'count' => $this->model->getCount($cond), 'msg' => 'ok', 'data'  => $list];
            return json($data);
        }
        return $this->fetch();
    }

    /**
     * 添加来源
     * @author zhangkx
     * @date 2019/4/19
     */
    public function add() {
        if (IS_POST) {
            if (method_exists($this->service, 'buildData')) {
                $arr = $this->service->buildData();
                if (method_exists($this->service, 'goEdit')) {
                    $this->service->goEdit($arr);
                }
            }
        }
        return $this->fetch();
    }

    /**
     * 编辑来源
     * @author zhangkx
     * @date 2019/4/19
     * @return mixed|void
     */
    public function edit() {
        $id = input('param.id/d');
        if (empty($id)) {
            return $this->error("参数错误！");
        }
        if (IS_POST) {
            if (method_exists($this->service, 'buildData')) {
                $arr = $this->service->buildData();
                if (method_exists($this->service, 'goEdit')) {
                    $this->service->goEdit($arr);
                }
            }
        }
        $data = $this->model->getInfo($id);

        $this->assign('id', $id);
        $this->assign('data', $data);
        return $this->fetch();
    }

    /**
     * 删除来源
     * @author zhangkx
     * @date 2019/4/19
     */
    public function drop()
    {
        $id = input('post.id');
        if (empty($id)) {
            return $this->error("参数错误！");
        }
        if (IS_POST) {
            if (method_exists($this->service, 'goDrop')) {
                $this->service->goDrop($id);
            }
        }
    }

}
