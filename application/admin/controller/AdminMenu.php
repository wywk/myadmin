<?php
namespace app\admin\controller;

use app\common\controller\Backend;

/**
 * 菜单管理控制器
 * @author zhangkx
 * @date 2019/4/19
 */
class AdminMenu extends Backend
{

    protected $model, $service;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('AdminMenu');
        $this->service = model('AdminMenu', 'service');
    }

    /**
     * 角色列表
     * @author zhangkx
     * @date 2019/4/18
     */
    public function index(){
        $cond['mark'] = 1;
        import('tree', EXTEND_PATH,'.lib.php');
        if ($this->params['page'] && $this->params['limit']) {
            $list = $this->model->getList($cond, '*', 'sort ASC, id DESC', $this->params['page'], 999);
            //得到树形列表
            $treeObj = new \Tree();
            $list = $treeObj->toFormatTree($list, 'name');
            $data = ['code' => 0,'count' => $this->model->getCount($cond), 'msg' => 'ok', 'data'  => $list];
            return json($data);
        }
        //加载权限按钮
        if (method_exists($this->service, 'getAuthMenu')) {
            $this->assign('authMenu',$this->service->getAuthMenu());
        }
        return $this->fetch();
    }

    /**
     * 添加角色
     * @author zhangkx
     * @date 2019/4/18
     */
    public function add() {
        if (IS_POST) {
            if (method_exists($this->service, 'buildData')) {
                $arr = $this->service->buildData();
                $result = $this->model->editData('adminMenu', $arr, $this->userId);
                if (!$result) {
                    return $this->error("操作失败！");
                }
                return $this->success("操作成功！", url('admin/AdminMenu/index'));
            }
        }
        //上级菜单
        $list = $this->model->getList(['mark'=>1], 'id,pid,name', 'sort ASC,id DESC');
        $parentTree = get_tree($list,0);
        $this->assign('parentTree', $parentTree);
        return $this->fetch();
    }

    /**
     * 编辑角色
     * @author zhangkx
     * @date 2019/4/18
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
                $result = $this->model->editData('adminMenu', $arr, $this->userId);
                if (!$result) {
                    return $this->error("操作失败！");
                }
                return $this->success("操作成功！", url('admin/AdminMenu/index'));
            }
        }
        $data = $this->model->getInfo($id);
        //上级菜单
        $list = $this->model->getList(['mark'=>1], 'id,pid,name', 'sort ASC,id DESC');
        $parentTree = get_tree($list, $data['pid']);
        $this->assign('id', $id);
        $this->assign('data', $data);
        $this->assign('parentTree', $parentTree);
        return $this->fetch();
    }

    /**
     * 删除角色
     * @author zhangkx
     * @date 2019/4/18
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

    /**
     * 更改状态
     * @author zhangkx
     * @date 2019/4/18
     */
    public function setStatus()
    {
        $id = input('post.id/d');
        $status = input('post.status/d');
        if (empty($id) || empty($status)) {
            return $this->error("参数错误！");
        }
        if (IS_POST) {
            if (method_exists($this->service, 'goEdit')) {
                $this->service->goEdit(['id'=>$id, 'status'=>$status]);
            }
        }
    }


}
