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
use think\Cookie;

/**
 * 登录管理
 * @author	luffy
 * @date    2018-04-24
 */
class Login extends Backend {

	//用户登录
	public function login($username='', $password='', $verify='', $remember=false) {
		$remember = $remember == 'on' ? true : false;
		if (is_login(MODULE)) {
			$this->redirect('admin/index/index');
		}
		if (IS_POST) {
			if (empty($username)) {
				return $this->error('账号不能为空！');
			}
			if (empty($password)) {
				return $this->error('密码不能为空！');
			}
			if (empty($verify)) {
				return $this->error('验证码不能为空！');
			}
//			$check_captcha = $this->validate(['verify' => $verify],[
//				'verify|验证码'=>'require|captcha'
//			]);
//			if ($check_captcha !== true) {
//				return $this->error($check_captcha);
//			}
            cookie('lock_status',0 ,3600*24*7);
            if($remember){//记住密码是写入cookie
                cookie('username',$username ,3600*24*7);
                cookie('password',$password ,3600*24*7);
                cookie('remember',1 ,3600*24*7);
            }else{//删除cookie
                @Cookie::delete('username');
                @Cookie::delete('password');
                @Cookie::delete('remember');
			}
            $login_obj = model('Login', 'service');
			$result = $login_obj->login($username, $password, $remember);

            if ($result > 0) {//记住密码
				//添加登陆日志
//				$user_obj->addLoginLog(session('user_auth.uid'));
				return $this->success('登录成功！', url('admin/index/index'));
			} elseif ($result == -1) {
				return $this->error('用户不存在或被禁用！');
			} elseif ($result == -2) {
				return $this->error('密码错误！');
			}
		}
        //页面加载，背景图加载
        if(@$_COOKIE['loginType'] =='loginSkin'){
            $loginBg = @$_COOKIE['loginBg'];
            $loadHtml = 'login/login_style';
        }else{
            $loadHtml='';
            $loginBg = "/static/admin/img/login/loginSkin/bg4.png";
        }
        $this->assign('username',@$_COOKIE['username']);
        $this->assign('password',@$_COOKIE['password']);
        $this->assign('remember',@$_COOKIE['remember']);
        $this->assign('loginBg',$loginBg);
        $this->assign('loginBg',$loginBg);
		$this->view->engine->layout(false);
		return $this->fetch($loadHtml);
	}

    //用户登出
    public function logout(){
        $service    = model('Login', 'service');
        $service->logout();
        $loginUrl   =  'admin/login/login';
        $this->redirect($loginUrl);
//		$this->success('退出成功！', url($loginUrl));
    }

	//登录背景图设置
	public function loginSkin($loginBg = '',$cleanCookie=0){
	    if(IS_POST){
	        if($loginBg){
                cookie('loginBg',$loginBg ,3600*24*365);
                cookie('loginType','loginSkin' ,3600*24*365);
            }
            if($cleanCookie){
	            Cookie::delete('loginBg');
	            Cookie::delete('loginType');
            }
        }
        $pathBg = @$_COOKIE['loginBg'];
        $bg_path = substr($pathBg,strripos($pathBg,"/")+1);//字符串截取
        $bg_path = substr($bg_path,0,strripos($bg_path,"."));//字符串截取
        $this->assign('bg_path',$bg_path);
        $this->assign('loginType',@$_COOKIE['loginType']);
	        $this->assign('loginBg',@$_COOKIE['loginBg']);
        return $this->fetch();
    }

	
}