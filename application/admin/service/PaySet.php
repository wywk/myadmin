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
class PaySet extends Base {

    use \traits\controller\Jump;

    /**
     * 自定义初始化
     */
    protected function initialize(){
        parent::initialize();
    }

    /**
     * 支付宝支付数据组装校验
     * @author   Run
     * @date     2019-04-015
     * @version  1.0
     */
    public function buildDataAliEdit(){
        $id             = input('post.id/d');
        $store_id       = input('post.store_id/d');
        $ali_status     = input('post.ali_status');
        $ali_appid      = input('post.ali_appid');
        $ali_pay_title  = input('post.ali_pay_title');
        $ali_public_key = input('post.ali_public_key');
        $ali_private_key = input('post.ali_private_key');
        if(empty($store_id)){
            return $this->error("系统错误！");
        }
        if(empty($ali_pay_title)){
            return $this->error("请填写支付宝付款标题！");
        }
        if(empty($ali_appid)){
            return $this->error("请填写支付宝APPID！");
        }
        if(empty($ali_public_key)){
            return $this->error("请填写支付宝公钥！");
        }
        if(empty($ali_private_key)){
            return $this->error("请填写支付宝私钥！");
        }
        if($ali_status == 'on'){
            $ali_status = 1;
        }else{
            $ali_status = 2;
        }
        $arr            =   [
            'id'            =>  $id,
            'store_id'            =>  $store_id,
            'ali_status'          =>  $ali_status,
            'ali_appid'        =>  $ali_appid,
            'ali_pay_title'     =>  $ali_pay_title,
            'ali_public_key'      =>  $ali_public_key,
            'ali_private_key'      =>  $ali_private_key,
        ];
        if($id){
            $arr['upd_time'] = time();
        }else{
            $arr['add_time'] = time();
        }
        return  $arr;
    }
    /**
     * 微信支付数据组装校验
     * @author   Run
     * @date     2019-04-015
     * @version  1.0
     */
    public function buildDataWxEdit(){
        $id             = input('post.id/d');
        $store_id       = input('post.store_id/d');
        $wx_status      = input('post.wx_status');
        $wx_appid       = input('post.wx_appid');
        $wx_mchid       = input('post.wx_mchid');
        $wx_key         = input('post.wx_key');
        $wx_appsecret   = input('post.wx_appsecret');
        $wx_cert_path   = input('post.wx_cert_path');
        $wx_key_path    = input('post.wx_key_path');
        if(empty($store_id)){
            return $this->error("系统错误！");
        }
        if(empty($wx_appid)){
            return $this->error("请填写微信APPID！");
        }
        if(empty($wx_mchid)){
            return $this->error("请填写微信商户号！");
        }
        if(empty($wx_key)){
            return $this->error("请填写商户支付密钥！");
        }
        if(empty($wx_appsecret)){
            return $this->error("请填写公众帐号secert！");
        }
        if(empty($wx_cert_path)){
            return $this->error("请上传微信支付证书路径！");
        }
        if(empty($wx_key_path)){
            return $this->error("请上传微信安全证书路径！");
        }
        if($wx_status == 'on'){
            $wx_status = 1;
        }else{
            $wx_status = 2;
        }
        $arr            =   [
            'id'            =>  $id,
            'store_id'      =>  $store_id,
            'wx_status'     =>  $wx_status,
            'wx_appid'      =>  $wx_appid,
            'wx_mchid'      =>  $wx_mchid,
            'wx_key'        =>  $wx_key,
            'wx_appsecret'  =>  $wx_appsecret,
            'wx_cert_path'  =>  $wx_cert_path,
            'wx_key_path'   =>  $wx_key_path,
        ];
        if($id){
            $arr['upd_time'] = time();
        }else{
            $arr['add_time'] = time();
        }
        return  $arr;
    }
    /**
     * 微信支付数据组装校验
     * @author   Run
     * @date     2019-04-015
     * @version  1.0
     */
    public function buildDataSmsEdit(){
        $id                 = input('post.id/d');
        $store_id           = input('post.store_id/d');
        $accessKeyId        = input('post.accessKeyId');
        $accessKeySceret    = input('post.accessKeySceret');
        $signName           = input('post.signName');
        $code_one           = input('post.code_one');
        $code_two           = input('post.code_two');
        if(empty($store_id)){
            return $this->error("系统错误！");
        }
        if(empty($accessKeyId)){
            return $this->error("请填写短信accessKeyId！");
        }
        if(empty($accessKeySceret)){
            return $this->error("请填写短信accessKeySceret！");
        }
        if(empty($signName)){
            return $this->error("请填写短信签名！");
        }
        if(empty($code_one)){
            return $this->error("请填写短信验证码模板！");
        }
        if(empty($code_two)){
            return $this->error("请填写短信初始密码模板！");
        }
        $arr            =   [
            'id'                =>  $id,
            'store_id'          =>  $store_id,
            'accessKeyId'       =>  $accessKeyId,
            'accessKeySceret'   =>  $accessKeySceret,
            'signName'          =>  $signName,
            'code_one'          =>  $code_one,
            'code_two'          =>  $code_two,
        ];
        if($id){
            $arr['upd_time'] = time();
        }else{
            $arr['add_time'] = time();
        }
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
        $result     = $this->drop($id);
        if (false !== $result) {
            return $this->success("操作成功！");
        } else {
            return $this->error("操作失败！");
        }
    }

    /**
     * 用户登录
     * @author   luffy
     * @date     2019-04-03
     */
    public function login($username, $password, $remember, $cond=[]){
        //去除前后空格
        $username = trim($username);

        //匹配登录方式
        //if (preg_match("/^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/", $username)) {
        if (\think\Validate::is($username, 'email')) {
            $cond['email'] = $username; //邮箱登陆
        } elseif (preg_match("/^1\d{10}$/", $username)) {
            $cond['phone']  = $username; //手机号登陆
        } else {
            $cond['name']   = $username; //用户名登陆
        }

        $cond['status']     = 1;
        $cond['mark']       = 1;
        $userInfo           = $this->getInfoByCond($cond); //查找用户
        if (!$userInfo) {
            return -1; //用户不存在或被禁用
        } else {
            if (!compare_password($password . $username, $userInfo['password'])) {
                return -2; //密码错误
            } else {
                /* 更新登录信息 */
//                $data = [
//                    'login_num'       => ['exp', '`login_num`+1'],
//                    'last_login_time' => time(),
//                    'last_login_ip'   => get_client_ip(1),
//                ];
//                $this->doEdit($data, $userInfo['id']);  //更新用户登录信息
                auto_login($userInfo, $remember);
                return $userInfo['id'];                 //登录成功，返回用户ID
            }
        }
    }

    /**
     * 用户注销
     * @author   luffy
     * @date     2019-04-03
     */
    public function logout(){
        session('user_auth', null);
        session('user_auth_sign', null);
    }
}