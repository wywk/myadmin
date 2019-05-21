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

namespace app\admin\controller;

use app\common\controller\Backend;

use think\Db;
/**
 * 后台用户管理控制器
 * @author	luffy
 * @date    2019-04-01
 */
class Test extends Backend {

    protected $model;

    public function _initialize(){
        parent::_initialize();
        $this->model = model('AdminUser');
    }

    /**
     * 列表页
     * @author	luffy
     * @date    2019-04-01
     */
    public function index() {
        $cond['mark'] = 1;
        $this->fuzzyCond($cond, 'name');        //账号名称
        $this->fuzzyCond($cond, 'phone');       //手机号码
        $this->fuzzyCond($cond, 'telephone');   //座机号码
        $this->queryCond($cond, 'status');      //账号状态
        $this->timeCond($cond);                             //操作时间
        if( $this->params['page'] && $this->params['limit']){
            $list   = $this->model->getList($cond, '*', $this->params['page'], $this->params['limit'], 'status ASC,id DESC');
            $data   = ['code' => 0,'count' => $this->model->getCount($cond), 'msg' => 'ok', 'data'  => array_values($list)];
            return json($data);
        }
        return $this->fetch();
    }
    /**
     * 文件分类--添加
     * @author	Run
     * @date    2019-04-18
     */
    public function fileTypeList() {
        $fileTypeService = model('FileType');
        $cond['mark'] = 1;
        if( $this->params['page'] && $this->params['limit']){
            $list   = $fileTypeService->getList($cond, '*', 'id DESC', $this->params['page'], $this->params['limit']);
            $data   = ['code' => 0,'count' => $fileTypeService->getCount($cond), 'msg' => 'ok', 'data'  => $list];
            return json($data);
        }
    }
    /**
     * 文件分类--添加
     * @author	Run
     * @date    2019-04-18
     */
    public function fileTypeAdd() {
        if(IS_POST){
            $fileTypeService = model('FileType', 'service');
            if(method_exists($fileTypeService, 'buildDataEdit')){
                $arr = $fileTypeService->buildDataEdit();
            }
            if(method_exists($fileTypeService, 'goEdit')){
                $fileTypeService->goEdit($arr);
            }
        }

        return $this->fetch();
    }
    /**
     * 文件分类--编辑
     * @author	Run
     * @date    2019-04-18
     */
    public function fileTypeEdit() {
        $fileTypeService = model('FileType', 'service');
        $id     = input('param.id');
        $info = $fileTypeService->getInfo($id);
        if(IS_POST){
            if(method_exists($fileTypeService, 'buildDataEdit')){
                $arr = $fileTypeService->buildDataEdit();
            }
            if(method_exists($fileTypeService, 'goEdit')){
                $fileTypeService->goEdit($arr);
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
    public function fileTypeDrop() {
        $id         = input('post.id');
        if( empty($id) ){
            return $this->error("参数错误！");
        }
        if( IS_POST ){
            $adminUserService = model('FileType', 'service');
            if(method_exists($adminUserService, 'goDrop')){
                $adminUserService->goDrop($id);
            }
        }
    }
}
