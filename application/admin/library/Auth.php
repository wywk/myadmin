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

namespace app\admin\library;

use think\Validate;
use fast\Random;
use fast\Tree;

/**
 * 权限认证类
 * @author	luffy
 * @date    2017-08-01
 * @version 1.0
 */
class Auth extends \com\Auth {
	
	protected $model = null;
	protected $auth_rule_model = null;
	protected $requestUri = '';
	protected $breadcrumb = [];
	
	/**
	 * 构造方法
	 */
	public function __construct(){
		parent::__construct();
		$this->model = model('admin');
		$this->auth_rule_model = model('auth_rule');
	}
	
	public function __get($name){
		return session('admin_auth.' . $name);
	}
	
	/**
	 * 用户登录
	 * @author	luffy
	 * @param	string $username
	 * @param	string $password
	 * @param	int $keeptime
	 * @param	array $cond
	 * @return	int
	 */
	public function login($username, $password, $keeptime = 0, $cond = []){
		$cond['mark'] = 1;
		//匹配登录方式
		if (Validate::is($username, 'email')) {
			$cond['email'] = $username; //邮箱登陆
		} elseif (preg_match("/^1\d{10}$/", $username)) {
			$cond['mobile'] = $username; //手机登陆
		} else {
			$cond['username'] = $username; //用户名登陆
		}
		$admin = $this->model->get($cond);
		if (!$admin) {
			return -1; //用户名不正确
		}
		if ($admin->status == 2) { //用户名被禁用
			return -2;
		}
		if (!compare_password($password, $admin->password, $admin->salt)) {
			$admin->login_failure++;
			$admin->save();
			return -3; //密码错误
		}
		$token = Random::uuid();
		/* 更新登录信息 */
		$data = [
			'login_failure' => 0,
			'login_num'     => ['exp', '`login_num`+1'],
			'last_time'     => time(),
			'last_ip'       => get_client_ip(1),
			'token'         => $token,
		];
		$this->model->doEdit($data, $admin->id); //更新用户登录信息
		$auth = [
			'id'        => $admin->id,
			'username'  => $admin->username,
			'avatar'    => $admin->avatar,
 		];
		$this->auto_login($auth);
		$this->keeplogin($keeptime);
		return $admin->id; //登录成功，返回用户ID	
	}
	
	/**
	 * 设置登录状态
	 * @author 	luffy
	 * @param 	array $auth
	 * @return 	int
	 */
	protected function auto_login($auth){
		// 记录登录SESSION
		session('admin_auth', $auth);
		session('admin_auth_sign', data_auth_sign($auth));
		return $this->isLogin();
	}
	
	/**
	 * 注销登录
	 * @author	luffy
	 * @return	bool
	 */
	public function logout(){
		$admin = $this->model->get(intval($this->id));
		if (!$admin) {
			return true;
		}
		$admin->token = '';
		$admin->save();
		session('admin_auth', null);
		session('admin_auth_sign', null);
		cookie('keeplogin', null);
		return true;
	}
	
	
	
	/**
	 * 自动登录
	 * @author	luffy
	 * @return 	bool
	 */
	public function autologin(){
		$keeplogin = cookie('keeplogin');
		if (!$keeplogin) {
			return false;
		}
		list($id, $keeptime, $expiretime, $key) = explode('|', $keeplogin);
		if ($id && $keeptime && $expiretime && $key && $expiretime > time()) {
			$admin = $this->model->get($id);
			if (!$admin || !$admin->token) {
				return false;
			}
			//token有变更
			if ($key != md5(md5($id) . md5($keeptime) . md5($expiretime) . $admin->token)) {
				return false;
			}
			$auth = [
				'id'       => $admin->id,
				'username' => $admin->username,
				'avatar'   => $admin->avatar,
			];
			$this->auto_login($auth);
			//刷新自动登录的时效
			$this->keeplogin($keeptime);
			return true;
		} else {
			return false;
		}
	}
	
	/**
	 * 刷新保持登录的Cookie
	 * @author	luffy
	 * @param 	int $keeptime
	 * @return 	boolean
	 */
	protected function keeplogin($keeptime = 0){
		if ($keeptime) {
			$expiretime = time() + $keeptime;
			$key = md5(md5($this->id) . md5($keeptime) . md5($expiretime) . $this->token);
			$data = [$this->id, $keeptime, $expiretime, $key];
			cookie('keeplogin', implode('|', $data));
			return true;
		}
		return false;
	}
	
	public function check($name, $uid = '', $relation = 'or', $mode = 'url'){
		return parent::check($name, $this->id, $relation, $mode);
	}
	
	/**
	 * 检测当前控制器和方法是否匹配传递的数组
	 * @author	luffy
	 * @param 	array $arr 需要验证权限的数组
	 * @return	bool
	 */
	public function match($arr = []){
		$arr = is_array($arr) ? $arr : explode(',', $arr);
		if (!$arr) {
			return false;
		}
	
		// 是否存在
		if (in_array(strtolower(request()->action()), $arr) || in_array('*', $arr)) {
			return true;
		}
	
		// 没找到匹配
		return false;
	}
	
	/**
	 * 检测是否登录
	 * @author 	luffy
	 * @return 	int 0-未登录，大于0-当前登录用户ID
	 */
	public function isLogin(){
		$auth = session('admin_auth');
		if (empty($auth)) {
			return 0;
		} else {
			return session('admin_auth_sign') == data_auth_sign($auth) ? $auth['id'] : 0;
		}
	}
	
	/**
	 * 获取当前请求的URI
	 * @author	luffy
	 * @return 	string
	 */
	public function getRequestUri(){
		return $this->requestUri;
	}
	
	/**
	 * 设置当前请求的URI
	 * @author	luffy
	 * @param 	string $uri
	 */
	public function setRequestUri($uri){
		$this->requestUri = $uri;
	}
	
	public function getGroups($uid = null){
		$uid = is_null($uid) ? $this->id : $uid;
		return parent::getGroups($uid);
	}
	
	public function getRuleList($uid = null){
		$uid = is_null($uid) ? $this->id : $uid;
		return parent::getRuleList($uid);
	}
	
	public function getUserInfo($uid = null){
		$uid = is_null($uid) ? $this->id : $uid;
		return $uid != $this->id ? $this->model->get(intval($uid)) : session('admin_auth');
	}
	
	public function getRuleIds($uid = null){
		$uid = is_null($uid) ? $this->id : $uid;
		return parent::getRuleIds($uid);
	}
	
	public function getAdminRuleIds($uid = null){
		$uid = is_null($uid) ? $this->id : $uid;
		return parent::getAdminRuleIds($uid);
	}
	
	public function isSuperAdmin(){
		return in_array('*', $this->getRuleIds()) ? true : false;
	}
	
	/**
	 * 获得面包屑导航
	 * @author	luffy
	 * @param 	string $path
	 * @return 	array
	 */
	public function getBreadCrumb($path = ''){
		if ($this->breadcrumb || !$path) {
			return $this->breadcrumb;
		}			
		$path_rule_id = 0;
		foreach ($this->rules as $rule) {
			$path_rule_id = $rule['name'] == $path ? $rule['id'] : $path_rule_id;
		}
		if ($path_rule_id) {
			$this->breadcrumb = Tree::instance()->init($this->rules)->getParents($path_rule_id, true);
			foreach ($this->breadcrumb as $k => &$v) {
				$v['url'] = url($v['name']);
			}
		}
		return $this->breadcrumb;
	}
	
	/**
	 * 获取左侧菜单栏
	 * @author	luffy
	 * @param 	array $params URL对应的badge数据
	 * @param	string $fixedPage
	 * @param	int $id
	 * @return 	string
	 */
	public function getSidebar($params = [], $fixedPage = 'dashboard', $id = 0){
		$colorArr = ['red', 'green', 'yellow', 'blue', 'teal', 'orange', 'purple'];
		$colorNums = count($colorArr);
		$badgeList = [];
		$module = request()->module();
		// 生成菜单的badge
		foreach ($params as $k => $v) {
			if (stripos($k, '/') === false) {
				$url = '/' . $module . '/' . $k;
			} else {
				$url = url($k);
			}
			if (is_array($v)) {
				$nums = isset($v[0]) ? $v[0] : 0;
				$color = isset($v[1]) ? $v[1] : $colorArr[(is_numeric($nums) ? $nums : strlen($nums)) % $colorNums];
				$class = isset($v[2]) ? $v[2] : 'label';
			} else {
				$nums = $v;
				$color = $colorArr[(is_numeric($nums) ? $nums : strlen($nums)) % $colorNums];
				$class = 'label';
			}
			//必须nums大于0才显示
			if ($nums) {
				$badgeList[$url] = '<small class="' . $class . ' pull-right bg-' . $color . '">' . $nums . '</small>';
			}
		}
		
		// 读取管理员当前拥有的权限节点
		$userRule = $this->getRuleList();
		$select_id = 0;
		$activeUrl = '/' . $module . '/' . $fixedPage;
		// 必须将结果集转换为数组
		$ruleList = collection(model('auth_rule')
						->where('ismenu', 1)
						->order('weigh', 'desc')
						->cache('__menu__')
						->select())
					->toArray();
		if ($id) {
			$id_arr = $this->auth_rule_model->getChildList($id, ['mark'=>1], 'id', 'sort ASC', true);
			$arr = [];
			foreach ($id_arr as $val) {
				$arr[] = $val['id'];
			}
		}
		foreach ($ruleList as $k => &$v) {
			if ($id && !in_array($v['id'], $arr)) {
				unset($ruleList[$k]);
				continue;
			}
			if (!in_array($v['name'], $userRule)) {
				unset($ruleList[$k]);
				continue;
			}
			$select_id = $v['name'] == $activeUrl ? $v['id'] : $select_id;
			$v['url'] = $v['name'];
			$v['badge'] = isset($badgeList[$v['name']]) ? $badgeList[$v['name']] : '';
			$v['py'] = \fast\Pinyin::get($v['title'], true);
			$v['pinyin'] = \fast\Pinyin::get($v['title']);
		}
		// 构造菜单数据
		Tree::instance()->init($ruleList);
		$menu = Tree::instance()->getTreeMenu($id, '<li class="@class" id="menu_@id"><a href="javascript:;" addtabs="@id" url="@url"><i class="@icon"></i> <span>@title</span> <span class="pull-right-container">@caret @badge</span></a> @childlist</li>', $select_id, '', 'ul', 'class="treeview-menu"');
		return $menu;
	}
	
	/**
	 * 获取顶部导航栏
	 * @author	luffy
	 * @param	string $fixedPage
	 * @return 	array
	 */
	public function getNavbar(){
		// 读取管理员当前拥有的权限节点
		$userRule = $this->getRuleList();
		// 必须将结果集转换为数组
		$ruleList = collection(model('auth_rule')
				->where('ismenu', 1)
				->where('pid', 0)
				->order('weigh', 'desc')
				->cache('__nav__')
				->select())
				->toArray();
		$i = 0;
		foreach ($ruleList as $k => &$v) {
			if (!in_array($v['name'], $userRule)) {
				unset($ruleList[$k]);
				continue;
			}
			if ($i == 0) {
				$v['active'] = 1;
			} else {
				$v['active'] = 0;
			}
			$v['url'] = $v['name'];
			$v['py'] = \fast\Pinyin::get($v['title'], true);
			$v['pinyin'] = \fast\Pinyin::get($v['title']);
			$i++;
		}
		return $ruleList;
	}
	
}
