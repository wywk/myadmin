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
use think\db;
/**
 * 后台登录服务类
 * @author	luffy
 * @date    2018-04-24
 */
class Login extends Base {

    /**
     * 自定义初始化
     */
    protected function initialize(){
        parent::initialize();
    }
    
    /**
     * 用户登录
     *
     */
    public function login($name, $password, $remember){
        //去除前后空格
        $name = trim($name);
        //匹配登录方式
        if (\think\Validate::is($name, 'email')) {
            $cond['email'] = $name; //邮箱登陆
        } elseif (preg_match("/^1\d{10}$/", $name)) {
            $cond['mobile'] = $name; //手机号登陆
        } else {
            $cond['name']   = $name; //用户名登陆
        }

        $cond['status'] = 1;
        $cond['mark']   = 1;
        $adminUserMod   = model('AdminUser');
        $userInfo       = $adminUserMod->getInfoByCond($cond); //查找用户
        if (!$userInfo) {
            return -1; //用户不存在或被禁用
        } else {
            if (!compare_password($password . $name, $userInfo['password'])) {
                return -2; //密码错误
            } else {
                /* 更新登录信息 */
                $data = [
                    'login_num'       =>  Db::raw('login_num+1'),
                    'last_login_time' => time(),
                    'last_login_ip'   => get_client_ip(1),
                ];
                $adminUserMod->doEdit($data, $userInfo['id']);  //更新用户登录信息
                auto_login($userInfo, MODULE, $remember);
                return $userInfo['id']; //登录成功，返回用户ID
            }
        }
    }
    
    /**
     * 用户注销
     *
     */
    public function logout(){
        session(null, MODULE);
    }
    
	/**
     * 添加登录日志
     * @auther luffy
     */
    public function addLoginLog($user_id = ''){
        $data['agent'] = 'pc';
        $data['user_id'] = $user_id;
        $data['current_ip'] = request()->ip();
        $data['create_time'] = time();
        $data['update_time'] = false;

        //是否变动判断
        $login_logs =  $this->login_log_model->where([
            'user_id' 	=> $user_id,
            'agent' 	=>  $data['agent'],
            'mark'		=> 1,
        ])->field('*')->order('id desc')->find();

        if( $login_logs ){
            $data['prev_ip'] = $login_logs['current_ip'];
            //是否ip变动
            if( $login_logs['current_ip']  != $data['current_ip']){
                $data['is_ip_change'] = 1;
            }
        } elseif( empty($login_logs) ) {
            $data['is_ip_change'] = 1;
        }
        $this->login_log_model->edit($data);
    }

}