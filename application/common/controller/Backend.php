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

namespace app\common\controller;

use think\Controller;
use app\common\lib\Hash;
use app\common\lib\Assign;

class Backend extends Controller {

    public      $assign;
    //定义试图名称
    public      $fetch = null;
    public      $userId;
    public      $userName;
    public      $lockStatus;
    protected   $rule;
    protected   $request;
    public      $helper = [
        'ViewFileSet'
    ];

    public function _initialize() {

        if (!empty($this->helper) && method_exists($this,'baseClass1')) {      //基类处理_1
            $this->baseClass1();
        }

        if (method_exists($this,'baseClass2')) {            //基类处理_2
            $this->baseClass2();
        }

        if (method_exists($this,'baseClass3')) {            //基类处理_3
            $this->baseClass3();
        }

    }

    /**
     * 加载前端文件
     * @author	luffy
     * @date    2019-04-07
     */
    final protected function loadViewConfig(){
        //加载单独前端配置
        $this->callback->appAloneFunblock($this->params['controller'] . '/' . $this->params['action']);
        $staticPath = ROOT_PATH.'public/static/';
        //css
        $viewCss     = $this->params['module'].'/css/' . $this->params['controller'] . '/' . $this->params['action'] . '.css';
//        echo $viewCss;
        if (file_exists($staticPath . $viewCss)){
            array_push($this->assign->css, $viewCss);
        }
        $this->assign('view_css', $this->assign->css);
        //js
        $viewJs      = $this->params['module'].'/js/' . $this->params['controller'] . '/' . $this->params['action'] . '.js';
        if (file_exists($staticPath . $viewJs)){
            array_push($this->assign->js, $viewJs);
        }
        $this->assign('view_js',  $this->assign->js);
    }

    /**
     * 基类处理_1
     * @author	luffy
     * @date    2019-04-07
     */
    final protected function baseClass1(){
        defined('MODULE')   or define('MODULE' , $this->request->module());
        defined('CONTROLLER')   or define('CONTROLLER'  , strtolower($this->request->controller()));
        defined('ACTION')   or define('ACTION' , strtolower($this->request->action()));

        //当前访问参数
        $this->params = [
            'module'    => MODULE,
            'controller'=> CONTROLLER,
            'action'    => ACTION
        ];
        $this->rule     = strtolower(MODULE . '/' . CONTROLLER . '/' . ACTION);
        defined('IS_POST')  or define('IS_POST' , $this->request->isPost());
        defined('IS_GET')   or define('IS_GET'  , $this->request->isGet());
        defined('IS_AJAX')  or define('IS_AJAX' , $this->request->isAjax());

        //当前登陆用户ID
        $user_id = is_login(MODULE);

        //是否是超级管理员
        define('IS_ROOT', is_administrator(MODULE));
        if (!$user_id && !in_array($this->rule, ['admin/login/login','admin/login/loginstyle', 'admin/login/logout'])) {
            $loginUrl =  'admin/login/login';
            if (IS_AJAX) {
                $this->error("您还没有登录！", url($loginUrl));
            } else {
                $this->redirect($loginUrl);
                exit();
            }
        } elseif( $user_id && !in_array($this->rule, ['admin/login/login','admin/login/loginstyle', 'admin/login/logout'])){
            //加载助手类
            if (!empty($this->helper)) {
                $this->helper = Hash::normalize($this->helper);
                foreach ($this->helper as $h => $h_option) {
                    eval('$this->$h = new  app\common\lib\\' . $h . ';');
                    if (!empty($h_option)) {
                        foreach ($h_option as $pro_name => $pro) {
                            $this->$h->$pro_name = $pro;
                        }
                    }
                }
            }

            $this->assign = new Assign;

            //加载自定义文件
            $this->callback = new \app\common\lib\ViewFileConfig($this);
            if ($this->callback->appBeforeAction) {
                call_user_func(array($this->callback, $this->callback->appBeforeAction));
            }

            //加载当前控制器方法所对应的前端文件
            $this->loadViewConfig();

            //渲染对象
            parent::assign('ViewFileSet', $this->ViewFileSet);
        }
    }

    /**
     * 基类处理_2
     * @author	luffy
     * @date    2019-04-07
     */
    final protected function baseClass2(){
        $page                   = input('get.page/d');
        $this->params['page']   = $page ? $page : 0;
        $limit                  = input('get.limit/d');
        $this->params['limit']  = $limit ? $limit : 0;
        $user = session('user_auth','', MODULE);
        $this->userId   = $user['uid'] ? $user['uid'] : 0;
        $this->userName = $user['name'] ? : '';
        $this->assign('userId', $this->userId);
        $this->assign('userName', $this->userName);
        $lockStatus = cookie('lock_status');
        $this->lockStatus = $lockStatus ? : 0;
        $this->assign('lockStatus', $this->lockStatus);
    }

    /**
     * 基类处理_3
     * @author	luffy
     * @date    2019-05-07
     */
    final protected function baseClass3(){

    }

    /**
     * 批量排序
     * @author	luffy
     * @date    2019-04-07
     */
    protected function batchSort(){
        $ids = input('post.ids') ? input('post.ids/s') : '';
        if (!$ids) {
            return $this->error("参数错误！");
        }
        $id_arr = explode(',', $ids);
        $form_data = input('post.form_data/s');
        parse_str($form_data, $arr);
        $sort = [];
        foreach ($id_arr as $key => $val) {
            $sort[$val] = $arr['sort'][$val];
        }
        $count = 0;
        foreach ($sort as $id => $val) {
            $data = [];
            $data['sort'] = $val;
            $this->model->update_user = $this->userId;
            $result = $this->model->doEdit($data, $id);
            if ($result === false) {
                return $this->error("排序失败！");
            }
            $count++;
        }
        return $this->success("成功排序{$count}条记录！");
    }

    /**
     * 关键词查询
     * @author	luffy
     * @date    2019-04-07
     */
    protected function keywordsCond(&$cond=[], $fields='name', $keywords_name='keywords'){
        $keywords = input("?get.{$keywords_name}") ? input("get.{$keywords_name}/s", '', 'trim') : '';
        if ($keywords) {
            if (is_numeric($keywords)) {
                $cond['id'] = $keywords;
            } else {
                if (strpos($fields, ',') !== false) {
                    $fields = explode(',', $fields);
                    $tmp = [];
                    foreach ($fields as $field) {
                        $tmp[] = "{$field} LIKE '%{$keywords}%'";
                    }
                    $exp = implode(' OR ', $tmp);
                    $cond[] =  ['exp', $exp];
                } else {
                    $cond[$fields] = ['LIKE', "%{$keywords}%"];
                }
            }
        }
        $this->assign($keywords_name, $keywords);
    }

    /**
     * 模糊查询
     * @author	luffy
     * @date    2019-04-07
     */
    protected function fuzzyCond(&$cond=[], $field_name='name'){
        $show_field = '';
        if (strpos($field_name, '.') !== false) {           //连表查询条件
            $field_name_arr = explode('.', $field_name);
            $show_field     = $field_name_arr[1];
        } else {
            $show_field     = $field_name;
        }
        $field = input("?get.{$show_field}") ? input("get.{$show_field}/s", '', 'trim') : '';
        if ($field) {
            $cond[$field_name]  = ['LIKE', "%{$field}%"];
        }
        $this->assign($show_field, $field);
    }

    /**
     * 精准查询
     * @author	luffy
     * @date    2019-04-07
     */
    protected function queryCond(&$cond=[], $field_name='status', $type='select', $field_arr=[]){
        $show_field = '';
        if (strpos($field_name, '.') !== false) {//连表查询条件
            $field_name_arr = explode('.', $field_name);
            $show_field = $field_name_arr[1];
        } else {
            $show_field = $field_name;
        }
        $field = input("?get.{$show_field}") ? trim(input("get.{$show_field}")) : '';
        if ($field) {
            $cond[$field_name] = $field;
            $this->assign("{$show_field}", $field);
        }
        if ($type == 'select') {
            if (empty($field_arr) && isset($this->model->$show_field)) {
                $field_arr = $this->model->$show_field;
            }
            $field_option = make_option($field_arr, $field);
            $this->assign("{$show_field}", $field_arr);
            $this->assign("{$show_field}_option", $field_option);
        }
    }

    /**
     * 时间查询
     * @author	luffy
     * @date    2019-04-07
     */
    protected function timeCond(&$cond=[], $field_name='add_time', $timeStart='start_time', $timeEnd='end_time', $if_show_form = true){
        $from_time  = input("?get.{$timeStart}") ? input("get.{$timeStart}/s") : '';
        $to_time    = input("?get.{$timeEnd}") ? input("get.{$timeEnd}/s") : '';
        $f_time     = strtotime($from_time);
        $t_time     = strtotime($to_time) + (3600*24 - 1);
        if ($from_time && !$to_time) {
            $cond[$field_name]  = ['EGT',$f_time];
            $this->assign($timeStart, $from_time);
        }
        if (!$from_time && $to_time) {
            $cond[$field_name]  = ['ELT',$t_time];
            $this->assign($timeEnd, $to_time);
        }
        if ($from_time && $to_time) {
            $cond[$field_name]  = ['BETWEEN',[$f_time,$t_time]];
            $this->assign($timeStart, $from_time);
            $this->assign($timeEnd, $to_time);
        }
        if ($if_show_form) {
            $view       = new \think\View();
            $view->engine->layout(false);
            $clendar    = $view->fetch('public/calendar',[$timeStart=>$from_time, $timeEnd=>$to_time]);
            $this->assign('clendar', $clendar);
        }
    }

    /**
     * 随时运动地图坐标选择器
     * @author	luffy
     * @date    2019-04-23
     */
    protected function tencentMapCoordinatePickup(){
        $view       = new \think\View();
        $view->engine->layout(false);
        $tencentMapCoordinatePickup = $view->fetch('public/tencentMapCoordinatePickup');
        $this->assign('tencentMapCoordinatePickup', $tencentMapCoordinatePickup);
    }
    /**
     * 编辑日志
     * @author	Run
     * @date    2019-04-26
     */
    public function getEditLog($array=[],$table=''){
        $editLog            = model('SystemEditLog');
        //当前访问参数
        $params = [
            'table_name'    => substr($table,2),
            'controller'    => CONTROLLER,
            'action'        => ACTION,
            'data'          => serialize($array),
            'upd_user'      => is_login(MODULE),
            'upd_time'      => time(),
        ];
        $editLog->edit($params);
    }

}