<?php
namespace app\admin\service;

use app\common\model\Base;

/**
 * 门店角色管理服务类
 * Class AuthGroup
 * @package app\admin\service
 * @author zhangkx
 * @date 2019/4/26
 */
class StoreRole extends Base {

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
     * @date 2019/4/26
     * @return array|void
     */
    public function buildData()
    {
        $id = input('post.id/d');
        $name = input('post.name/s');
        $storeId = input('post.store_id/d');
        $pid = input('post.pid/d');
        $sort = input('post.sort/d');
        $result = [
            'name' => $name,
            'pid' => $pid,
            'store_id' => $storeId,
            'sort' => $sort,
        ];
        if ($id) {
            $scene = 'edit';
            $result['id'] = $id;
        } else {
            $scene = 'add';
            $result['id'] = 0;
        }
        $validate = validate('StoreRole');
        //校验
        if (!$validate->check($result, [], $scene)) {
            return $this->error($validate->getError());
        }
        return $result;
    }



    /**
     * 执行删除
     * @author zhangkx
     * @date 2019/4/26
     * @param $id
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function goDrop($id)
    {
        $data = $this->where('pid','in',$id)->where('mark',1)->select();
        if ($data) {
            return $this->error("当前角色下有其他角色，不能删除");
        }
        $result = $this->doDrop($id);
        if ($result !== false) {
            return $this->success("操作成功！");
        } else {
            return $this->error("操作失败！");
        }
    }


    /**
     * 权限菜单数据组装校验
     * @author zhangkx
     * @date 2019/4/18
     */
    public function buildDataMenu(){
        $id         = input('post.id/d');
        $menu_ids   = input('post.menu_ids');
        if(empty($id)){
            return $this->error("参数错误！");
        }
        $data = array(
            'id'=>$id,
            'rules'=>$menu_ids
        );
        return $data;
    }

    /**
     * 无限极分类
     * @author zhangkx
     * @date 2019/4/26
     * @param $data
     * @param int $pid
     * @return array
     */
    public function tree($data, $pid = 0)
    {
        $treeNodes = array();
        foreach($data as $k => $v){
            if($v['pid'] == $pid){
                $v['child'] = $this->tree($data, $v['id']);
                $treeNodes[] = $v;
            }
        }
        return $treeNodes;
    }
}