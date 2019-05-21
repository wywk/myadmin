<?php

namespace app\admin\controller;

use app\common\controller\Backend;
use think\Request;
use think\Model;
use think\Db;
/**
 * 首页管理控制器
 * @author	Run
 * @date    2019-04-11
 */
class Store extends Backend {

    protected $model;
    public function _initialize(){
        parent::_initialize();
        $this->model = model('Store');
    }
    /**
     * 列表页
     * @author	Run
     * @date    2019-04-11
     */
    public function index() {
        $cond['mark'] = 1;
        $this->fuzzyCond($cond, 'name');        //账号名称
        $this->fuzzyCond($cond, 'link_man');       //手机号码
        $this->fuzzyCond($cond, 'link_tel');   //座机号码
        $this->timeCond($cond);                             //操作时间
        if( $this->params['page'] && $this->params['limit']){
            $list   = $this->model->getList($cond, '*','status ASC,id DESC', $this->params['page'], $this->params['limit']);
            $data   = ['code' => 0,'count' => $this->model->getCount($cond), 'msg' => 'ok', 'data'  => $list];
            return json($data);
        }
        return $this->fetch();
    }

    /**
     * 添加
     * @author	Run
     * @date    2019-04-11
     */
    public function add() {
        if( IS_POST ){
            $storeService = model('Store', 'service');
            if(method_exists($storeService, 'buildDataEdit')){
                $arr = $storeService->buildDataEdit();
            }
            if(method_exists($storeService, 'goEdit')){
                $storeService->goEdit($arr);
            }
        }
        $CityMod = model('City');
        $city_option = $CityMod->getProvince(1);
        $this->assign('city_option',$city_option);

        $this->tencentMapCoordinatePickup();

        return $this->fetch();
    }

    /**
     * 添加
     * @author	Run
     * @date    2019-04-11
     */
    public function getCity($parent_id = 1) {
        $CityMod = model('City');
        if(IS_POST){
            $city_option = $CityMod->getProvince($parent_id);
            return json(['code' => 1, 'msg' => '!', 'option' => $city_option]);
        }

        return json(['code' => 0, 'msg' => '!', 'option' => '']);
    }

    /**
     * 编辑
     * @author	Run
     * @date    2019-04-11
     */
    public function edit() {
        $id     = input('param.id');

        if( IS_POST ){
            $storeService = model('Store', 'service');
            if(method_exists($storeService, 'buildDataEdit')){
                $arr = $storeService->buildDataEdit();
            }
            if(method_exists($storeService, 'goEdit')){
                $storeService->goEdit($arr);
            }
        }
        $info = $this->model->getInfo($id);;
        $CityMod                = model('City');
//        echo "<pre>";print_r($city_arr);die;
        $province_option    = $CityMod->getProvince(1,$info['province_id']);
        $city_option        = $CityMod->getProvince($info['province_id'],$info['city_id']);
        $area_option        = $CityMod->getProvince($info['city_id'],$info['area_id']);
        $this->tencentMapCoordinatePickup();//腾讯地图
        $this->assign('id',$id);
        $this->assign('info',$info);
        $this->assign('province_option',$province_option);
        $this->assign('city_option',$city_option);
        $this->assign('area_option',$area_option);
        return $this->fetch();
    }

    /**
     * 详情
     * @author	Run
     * @date    2019-04-11
     */
    public function info() {

        $id     = input('param.id');
        $data = $this->model->getInfo($id);
        return $this->fetch();
    }
    /**
     * 编辑
     * @author	Run
     * @date    2019-04-11
     */
    public function paySet() {
        $id     = input('param.id');
        $info   = Db::table('bs_pay_set')->where('store_id',$id)->find();
        $this->assign('info',$info);
        $sms_info   = Db::table('bs_sms_config')->where('store_id',$id)->find();
        $this->assign('sms_info',$sms_info);
        //查询配置记录
        if( IS_POST ){
            $type  = input('param.type');
            $paySetService = model('PaySet', 'service');
            switch ($type){
                case 'ali':
                    if(method_exists($paySetService, 'buildDataAliEdit')){
                        $arr = $paySetService->buildDataAliEdit();
                    }
                    if(method_exists($paySetService, 'goEdit')){
                        $paySetService->goEdit($arr);
                    }

                    break;
                case 'wx';
                    if(method_exists($paySetService, 'buildDataWxEdit')){
                        $arr = $paySetService->buildDataWxEdit();
                    }
                    if(method_exists($paySetService, 'goEdit')){
                        $paySetService->goEdit($arr);
                    }
                    break;
                case 'sms';
                    if(method_exists($paySetService, 'buildDataSmsEdit')){
                        $arr = $paySetService->buildDataSmsEdit();
                    }
                    if(method_exists($paySetService, 'goEdit')){
                        $paySetService->goEdit($arr);
                    }
                    break;
            }
            //echo "<pre>";print_r($arr);die;
        }
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
            $adminUserService = model('Store', 'service');
            if(method_exists($adminUserService, 'goDrop')){
                $adminUserService->goDrop($id);
            }
        }
    }
}
