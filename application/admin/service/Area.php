<?php
namespace app\admin\service;

use app\common\model\Base;

class Area extends Base
{
    public function goDrop($id)
    {
        $data = $this->where('parent_id','in',$id)->select();
        if ($data) {
            return $this->error("当前区划下有其他区划，不能删除！");
        }
        $result = $this->doDrop($id);
        if ($result !== false){
            return $this->success("操作成功！");
        } else {
            return $this->error("操作失败！");
        }
    }
}