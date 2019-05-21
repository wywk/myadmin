<?php
namespace app\admin\service;

use app\common\model\Base;

/**
 * 来源管理服务类
 * Class AuthGroup
 * @package app\admin\service
 * @author zhangkx
 * @date 2019/4/19
 */
class AdminSource extends Base {

    use \traits\controller\Jump;

    /**
     * 自定义初始化
     */
    protected function initialize()
    {
        parent::initialize();
    }

    /**
     * 数据组装校验
     * @author zhangkx
     * @date 2019/4/19
     */
    public function buildData()
    {
        $id = input('post.id/d');
        $name = input('post.name/s');
        $sort = input('post.sort/d');
        if (empty($name)) {
            return $this->error("请填写来源名称");
        }
        $result = [
            'name' => $name,
            'sort' => $sort,
        ];
        if ($id) {
            $result['id'] = $id;
            $result['upd_time'] = time();
        } else {
            $result['add_time'] = time();
        }
        return $result;
    }

    /**
     * 执行编辑
     * @author zhangkx
     * @date 2019/4/19
     * @param $arr
     */
    public function goEdit($arr)
    {
        $result     = $this->edit($arr);
        if (false !== $result) {
            return $this->success("操作成功！", url('admin/AdminSource/index'));
        } else {
            return $this->error("操作失败！");
        }
    }

    /**
     * 执行删除
     * @author zhangkx
     * @date 2019/4/19
     * @param $id
     */
    public function goDrop($id)
    {
        $result = $this->doDrop($id);
        if ($result !== false) {
            return $this->success("操作成功！");
        } else {
            return $this->error("操作失败！");
        }
    }
}