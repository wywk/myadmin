<?php
namespace app\admin\controller;

use app\common\controller\Backend;

class Area extends Backend
{
    public function _initialize()
    {
        parent::_initialize(); // TODO: Change the autogenerated stub
        $this->model = model('City');
    }

    public function index()
    {
        // import('tree', EXTEND_PATH, '.lib.php');
        $cond['parent_id'] = 1;
        if ($this->params['page'] && $this->params['limit']) {
            $this->model->getList($cond, '*', 'id DESC', $this->params['page'],$this->params['limit']);
            $data = ['code' => 0, 'count' => $this->model->getCount($cond), 'msg' => 'ok', 'data' => $list];

            return json($data);
        }
        return $this->fetch();

    }

    public function add()
    {

    }

    public function edit()
    {

    }
    
    public function drop()
    {

    }
}