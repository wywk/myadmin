<?php
namespace app\admin\controller;

use app\common\controller\Backend;

/**
 * 角色管理控制器
 * @author zhangkx
 * @date 2019/4/18
 */
class AdminRole extends Backend
{

    protected $model, $service,$adminMenu;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('AdminRole');
        $this->adminMenu = model('AdminMenu');
        $this->service = model('AdminRole', 'service');
    }

    /**
     * 角色列表
     * @author zhangkx
     * @date 2019/4/18
     */
    public function index()
    {
        $cond['mark'] = 1;
        import('tree', EXTEND_PATH,'.lib.php');
        if ($this->params['page'] && $this->params['limit']) {
            $list = $this->model->getList($cond, '*', 'sort ASC,id DESC', 0, 999);
            //得到树形列表
            $treeObj = new \Tree();
            $list = $treeObj->toFormatTree($list, 'name');
            $data = ['code' => 0,'count' => $this->model->getCount($cond), 'msg' => 'ok', 'data'  => $list];
            return json($data);
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
                $result = $this->model->editData('adminRole', $arr, $this->userId);
                if (!$result) {
                    return $this->error("操作失败！");
                }
                return $this->success("操作成功！", url('admin/AdminRole/index'));
            }
        }
        //上级角色
        $list = $this->model->getList(['mark'=>1,'pid'=>0], 'id,pid,name', 'sort ASC,id DESC');
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
                $result = $this->model->editData('adminRole', $arr, $this->userId);
                if (!$result) {
                    return $this->error("操作失败！");
                }
                return $this->success("操作成功！", url('admin/AdminRole/index'));
            }
        }
        $data = $this->model->getInfo($id);
        $parentTree = '';
        if ($data['pid']) {
            //上级角色
            $list = $this->model->getList(['mark'=>1,'pid'=>0], 'id,pid,name', 'sort ASC,id DESC');
            $parentTree = get_tree($list, $data['pid']);
        }
        $this->assign('id', $id);
        $this->assign('data', $data);
        $this->assign('parentTree', $parentTree);
        return $this->fetch();
    }

    /**
     * 设置权限
     * @author run
     * @date   2019/4/18
     */
    public function setMenu()
    {
        $id = input('param.id/d');
        if (IS_POST) {
            if (method_exists($this->service, 'buildDataMenu')) {
                $arr = $this->service->buildDataMenu();

                $result = $this->model->editData('adminRole', $arr, $this->userId);
                if (!$result) {
                    return $this->error("操作失败！");
                }
                return $this->success("操作成功！", 'admin/AdminRole/index');
            }
        }
        $cond['mark'] = 1;
        $list = $this->adminMenu->getList($cond, '*','sort ASC, id DESC');
        foreach ($list as $k => &$v){
           $v['pid'] =  $this->service->digui($list,$v['pid']);
        }
        $data = $this->service->tree($list);
        $arr = $this->model->getInfo($id,'id,rules');
        $meun_ids =  explode(',',$arr['rules']);
        $this->assign('meun_ids', array_filter($meun_ids));
        $this->assign('id', $id);
        $this->assign('data', $data);
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


}
