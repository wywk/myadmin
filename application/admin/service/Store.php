<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2017 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: luffy <1549626108@qq.com>
// +----------------------------------------------------------------------

namespace app\admin\service;

use app\common\model\Base;

/**
 * 后台商店列表服务类
 * @author   Run
 * @date     2019-04-015
 * @version  1.0
 */
class Store extends Base {

    use \traits\controller\Jump;

    /**
     * 自定义初始化
     */
    protected function initialize(){
        parent::initialize();
    }

    /**
     * 数据组装校验--编辑
     * @author   Run
     * @date     2019-04-015
     * @version  1.0
     */
    public function buildDataEdit(){
        $id         = input('post.id');
        $name       = input('post.name');
        $logo_path  = input('post.logo_path');
        $link_man   = input('post.link_man');
        $link_tel   = input('post.link_tel');
        $open_time  = input('post.open_time');//营业时间
        $province   = input('post.province');
        $city       = input('post.city');
        $area       = input('post.area');
        $addr       = input('post.addr');
        $tude       = input('post.tude');//经纬度
        $brief      = input('post.brief');
        $status     = input('post.status');
        $sort       = input('post.sort');
        $tude_arr   = explode(',',$tude);
        $arr            =   [
            'id'            =>  $id,
            'name'          =>  $name,
            'status'        =>  $status,
            'logo_path'     =>  $logo_path,
            'link_man'      =>  $link_man,
            'link_tel'      =>  $link_tel,
            'open_time'     =>  $open_time,
            'province_id'   =>  $province,
            'city_id'       =>  $city,
            'area_id'       =>  $area,
            'address'       =>  $addr,
            'longitude'     =>  $tude_arr[0],
            'latitude'      =>  $tude_arr[1],
            'desc'          =>  $brief,
            'sort'          =>  $sort,
        ];
        if($id){
            $scene              = 'edit';
        }else{
            $arr['add_time'] = time();
            $arr['add_user'] = is_login();
            $scene              = 'add';
        }

        //校验
        if (!validate('Store')->check($arr, [], $scene)) {
            return $this->error(validate('Store')->getError());
        }
//        echo "<pre>";print_r($arr);die;
        return  $arr;
    }

    /**
     * 执行编辑
     * @author   luffy
     * @date     2019-04-11
     */
    public function goEdit($arr){
        $result     = $this->edit($arr);
        if (false !== $result) {
            return $this->success("操作成功！", url('admin/AdminUser/index'));
        } else {
            return $this->error("操作失败！");
        }
    }

    /**
     * 执行删除
     * @author   luffy
     * @date     2019-04-11
     */
    public function goDrop($id){
        $result     = $this->doDrop($id);
        if (false !== $result) {
            return $this->success("操作成功！");
        } else {
            return $this->error("操作失败！");
        }
    }

    /**
     * 获取店铺下拉框
     * @author zhangkx
     * @date 2019/4/26
     * @param string $selected
     * @return string
     */
    public function getStoreOption($selected = '')
    {
        $arr = array();
        $cond['mark'] = 1;
        $cond['status'] = 1;
        $list = $this->getList($cond, 'id,name', $order = 'id asc', $page = 0, $limit = 99999);
        foreach ($list as $k => $v) {
            $arr[$v['id']] = $v['name'];
        }
        $field_option = make_option($arr, $selected, 'name');
        return $field_option;
    }
}