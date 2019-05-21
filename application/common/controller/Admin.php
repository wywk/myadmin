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
use app\admin\library\Auth;
use think\Lang;

/**
 * 后台控制器基类
 * @author	luffy
 * @date    2019-04-01
 * @version 1.0
 */
class Admin extends Controller {

    /**
     * 权限控制类
     * @var Auth
     */
    protected $auth = null;

	protected $userId;

	protected $userInfo;

	protected $_time;

	/**
	 * 初始化方法
	 *
	 */
	public function _initialize(){
		parent::_initialize();

        $modulename     = $this->request->module();
        $controllername = strtolower($this->request->controller());
        $actionname     = strtolower($this->request->action());

        // 设置当前请求的URI
        $path = '/' . $modulename . '/' . str_replace('.', '/', $controllername) . '/' . $actionname;
        $this->auth->setRequestUri($path);

        // 定义是否GET请求
        defined('IS_GET') or define('IS_GET', $this->request->isGet());

        // 定义是否POST请求
        defined('IS_POST') or define('IS_POST', $this->request->isPost());

        // 定义是否AJAX请求
        defined('IS_AJAX') or define('IS_AJAX', $this->request->isAjax());

		$this->_time = time();
		$user_id = is_login();
		if (!$user_id && !in_array($this->rule, ['admin/login/login', 'admin/login/logout'])) {
			if (IS_AJAX) {
				$this->error("您还没有登录！", url('admin/login/login'));
			} else {
				$this->redirect('admin/login/login');
				exit();
			}
		}

		if (!in_array($this->rule, ['admin/login/login', 'admin/login/logout'])) {
			//是否是超级管理员
			define('IS_ROOT', is_administrator());
//			if (!IS_ROOT && config('admin_allow_ip')) {
//				//检查IP地址访问
//				if (!in_array(get_client_ip(), explode(',', config('admin_allow_ip')))) {
//					$this->error('403:禁止访问');
//				}
//			}

			//检测系统权限
//			if (!IS_ROOT) {
//				$access = $this->accessControl();
//				if (false === $access) {
//					$this->error('403:禁止访问');
//				} elseif (null === $access) {
//
//					$dynamic = $this->checkDynamic(); //检测分类栏目有关的各项动态权限
//					if ($dynamic === null) {
//						//检测访问权限
//						if (!$this->checkRule($this->rule, ['in', '1,2'])) {
//							$this->error('未授权访问!');
//						} else {
//							//检测分类及内容有关的各项动态权限
//							$dynamic = $this->checkDynamic();
//							if (false === $dynamic) {
//								$this->error('未授权访问!');
//							}
//						}
//					} elseif ($dynamic === false) {
//						$this->error('未授权访问!');
//					}
//				}
//			}
			$model = model('user');
			$this->userId = $user_id;
			$this->userInfo = $model->getInfo($user_id);
            //记录请求数据
//            $this->addOperateLog();
			$this->assign('userId', $this->userId);
			$this->assign('userInfo', $this->userInfo);
			//URL
			$this->assign('domain', \think\Request::instance()->domain());
			$this->assign('baseUrl', \think\Request::instance()->baseUrl());
			$this->assign('editUrl', url(MODULE_NAME.'/'.CONTROLLER_NAME.'/edit'));
			$this->assign('dropUrl', url(MODULE_NAME.'/'.CONTROLLER_NAME.'/drop'));
			//加载当前控制器方法所对应的JS文件
			$this->appJs();
		}
	}
	
	/**
	 * 权限检测
	 * 
	 * @author 	matengfei
	 * @param 	string $rule 检测的规则
	 * @param 	int $type 权限规则分类
	 * @param 	string $mode check模式
	 * @return 	boolean
	 */
	final protected function checkRule($rule, $type=\app\common\model\AuthRule::type, $mode='url'){
		static $Auth = null;
		if (!$Auth) {
			$Auth = model('auth', 'service');
		}
		if (!$Auth->check($rule, session('user_auth.uid'), $type, $mode)) {
			return false;
		}
		return true;
	}
	
	/**
	 * 检测是否是需要动态判断的权限
	 * @return boolean|null
	 *      返回true则表示当前访问有权限
	 *      返回false则表示当前访问无权限
	 *      返回null，则表示权限不明
	 *
	 * @author matengfei <matengfei2000@gmail.com>
	 */
	protected function checkDynamic(){
		if (IS_ROOT) {
			return true; //管理员允许访问任何页面
		}
		return null; //不明,需checkRule
	}
	
	/**
	 * action访问控制,在 **登陆成功** 后执行的第一项权限检测任务
	 *
	 * @return boolean|null  返回值必须使用 `===` 进行判断
	 *
	 *   返回 **false**, 不允许任何人访问(超管除外)
	 *   返回 **true**, 允许任何管理员访问,无需执行节点权限检测
	 *   返回 **null**, 需要继续执行节点权限检测决定是否允许访问
	 * @author matengfei <matengfei2000@gmail.com>
	 */
	final protected function accessControl(){
		$allow = config('allow_visit');
		$deny  = config('deny_visit');

		$cond = [];
		$cond['name'] = 'allow_visit';
		$config_model = model('config');
		$configInfo = $config_model->getInfoByCond($cond);
		$allow2 = [];
		if ($configInfo['value']) {
			$allow2 = explode("\r\n", $configInfo['value']);
		}
		$allow = array_merge($allow, $allow2);
		
		$cond = [];
		$cond['name'] = 'deny_visit';
		$config_model = model('config');
		$configInfo = $config_model->getInfoByCond($cond);
		$deny2 = [];
		if ($configInfo['value']) {
			$deny2 = explode("\r\n", $configInfo['value']);
		}
		$deny = array_merge($deny, $deny2);

		$check = $this->rule . (!empty($this->param) ? '?'.http_build_query($this->param) : '');
		if (!empty($deny) && in_array_case($check, $deny)) {
			return false; //非超管禁止访问deny中的方法
		}
		if (!empty($allow) && in_array_case($check, $allow)) {
			return true;
		}
		foreach ($deny as $key => $val) {
			if (substr($val, -2) == '/*') {
				$arr = explode('/', $this->rule);
				$sub_rule = $arr[0] . '/' . $arr[1];
				if (substr($val, 0, -2) == $sub_rule) {
					return false;
				}
			}
		}
		foreach ($allow as $key => $val) {
			if (substr($val, -2) == '/*') {
				$arr = explode('/', $this->rule);
				$sub_rule = $arr[0] . '/' . $arr[1];
				if (substr($val, 0, -2) == $sub_rule) {
					return true;
				}
			}
		}
		return null; //需要检测节点权限
	}
	
	/**
	 * 软删除
	 *
	 * @author 	matengfei
	 * @param 	int $id
	 * @param 	bool $all
	 * @param 	bool $has_child
	 * @param 	string $func
	 * @return 	void
	 */
	public function drop($id='', $all=false, $has_child=false, $func='', $model_val = 'model'){
		$id = input('?get.id') ? input('get.id/d') : $id;
		$all = input('?get.all') ? input('get.all/b') : $all;
		$has_child = input('?get.has_child') ? input('get.has_child/b') : $has_child;
		$func = input('?get.func') ? input('get.func/s') : $func;
		$model_val = input('?get.model_val') ? input('get.model_val/s') : $model_val;
		if (!isset($this->$model_val)) return $this->error('模型不存在');
		if (IS_POST) { //批量删除，有删除权限就有批量删除权限，避免重复设置权限
			$act = input('?post.act') ? input('post.act/s') : '';
			if ($act == 'batchDrop') {
				$ids = input('?post.ids') ? input('post.ids/s') : '';
				if (!$ids) {
					return $this->error('参数错误！');
				}
				$all = input('post.all/b') ? true : false;
				$has_child = input('post.has_child/b') ? true : false;
				$id_arr = explode(',', $ids);
				foreach ($id_arr as $key => $id) {
					$result = $this->_drop($this->$model_val, $id, $all, $has_child, $func, true);
					if ($result['code'] === 0) {
						return $result;
					}
				}
				$count = count($id_arr);
				return $this->success("成功删除{$count}条记录！");
			} else {
				return $this->error('操作未定义！');
			}
		}
		$result = $this->_drop($this->$model_val, $id, $all, $has_child, $func);
		return $result;
	}
	
	/**
	 * 软删除
	 *
	 * @author	matengfei
	 * @param 	int $id
	 * @param 	bool $all
	 * @param 	bool $has_child
	 * @param 	string $func
	 * @return 	void
	 */
	private function _drop($model, $id, $all=false, $has_child=false, $func='', $is_batch=false){
		if (empty($id)) {
			if ($is_batch==true) {
				return ['code'=>0, 'msg'=>"非法操作！"];
			} else {
				return $this->error("非法操作！");
			}
		}
		$info = $model->getInfo($id);
		if (!$info) {
			if ($is_batch==true) {
				return ['code'=>0, 'msg'=>"您指定的数据不存在，请核实后再试！"];
			} else {
				return $this->error("您指定的数据不存在，请核实后再试！");
			}
		}
		if ($has_child == 1) {
			$cond = [];
			$cond['mark'] = 1;
			$cond['pid'] = $id;
			$childs_count = $model->getCount($cond);
			if ($childs_count>0) {
				if ($is_batch==true) {
					return ['code'=>0, 'msg'=>"您指定的数据有下级存在，不能删除！"];
				} else {
					return $this->error("您指定的数据有下级存在，不能删除！");
				}
			}
		}
		$result = $model->drop($id, $all);
		if (false !== $result) {
			if ($func) {
				$this->$func($id);
			}
			if ($is_batch==true) {
				return ['code'=>1, 'msg'=>"删除成功！"];
			} else {
				return $this->success("删除成功！");
			}
		} else {
			if ($is_batch==true) {
				return ['code'=>0, 'msg'=>"删除失败！"];
			} else {
				return $this->error("删除失败！");
			}
		}
	}
	
	/**
	 * 改变状态
	 * 
	 * @author	matengfei
	 * @param 	int $id
	 * @param 	string $type
	 * @param 	int $status
	 * @return 	void
	 */
	protected function status(){
		$id = input('?post.id') ? input('post.id/d') : '';
		if (!$id) {
			return $this->error("参数错误");
		}
		$type = input('?post.type') ? input('post.type/s') : 'status';
		$status = input('post.status/d');
		$this->model->update_user = $this->userId;
		$result = $this->model->doEdit([$type=>$status], $id);
		if (false === $result) {
			return $this->error("操作失败！");
		} else {
			return $this->success("操作成功！");
		}
	}
	
	/**
	 * 重置缓存
	 *
	 * @author	matengfei
	 * @param 	int $id
	 * @return 	void
	 */
	protected function cache(){
		$id = input('?post.id') ? input('post.id/d') : '';
		if (!$id) {
			return $this->error("参数错误！");
		}
		$result = $this->model->_cacheReset($id);
		if (!$result) {
			return $this->error("重置失败！");
		}
		return $this->success("重置成功！");
	}
	
	/**
	 * 批量重置缓存
	 *
	 * @author	matengfei
	 * @param 	string $ids
	 * @return 	void
	 */
	protected function batchCache(){
		$ids = input('?post.ids') ? input('post.ids/s') : '';
		if (!$ids) {
			return $this->error("参数错误！");
		}
		$id_arr = explode(',', $ids);
		$count = 0;
		foreach ($id_arr as $id) {
			$result = $this->model->_cacheReset($id);
			if (!$result) {
				return $this->error("重置失败！");
			}
			$count++;
		}
		return $this->success("成功重置{$count}条记录！");
	}
	
	/**
	 * 批量排序
	 *
	 * @author	matengfei
	 * @param 	string $ids
	 * @return 	void
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
	 *
	 * @author	matengfei
	 * @param 	array $cond
	 * @param	string $fields
	 * @param	string $keywords_name
	 * @return	void
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
	 *
	 * @author	matengfei
	 * @param 	array $cond
	 * @param	string $field_name
	 * @return	void
	 */
	protected function fuzzyCond(&$cond=[], $field_name='name'){
		$show_field = '';
	    if (strpos($field_name, '.') !== false) {//连表查询条件
	        $field_name_arr = explode('.', $field_name);
	        $show_field = $field_name_arr[1];
	    } else {
	        $show_field = $field_name;
	    }
		$field = input("?get.{$show_field}") ? input("get.{$show_field}/s", '', 'trim') : '';
		if ($field) {
			$cond[$field_name] = ['LIKE', "%{$field}%"];
		}
		$this->assign($show_field, $field);
	}
	
	/**
	 * 精准查询
	 *
	 * @author	matengfei
	 * @param 	array $cond
	 * @param	string $field_name
	 * @param	string $type
	 * @param	array $field_arr
	 * @return	void
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
		} else {
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
	 *
	 * @author	matengfei
	 * @param 	array $cond
	 * @param	string $field_name
	 * @param	string $timeStart
	 * @param	string $timeEnd
	 * @param	bool $if_show_form
	 * @return	void
	 */
	protected function timeCond(&$cond=[], $field_name='create_time', $timeStart='from_time', $timeEnd='to_time', $if_show_form = true){
		$from_time = input("?get.{$timeStart}") ? input("get.{$timeStart}/s") : '';
		$to_time = input("?get.{$timeEnd}") ? input("get.{$timeEnd}/s") : '';
		$f_time = strtotime($from_time);		
		$t_time = strtotime($to_time) + (3600*24 - 1);
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
			$view = new \think\View();
			$view->engine->layout(false);
			$clendar = $view->fetch('public/calendar',[$timeStart=>$from_time, $timeEnd=>$to_time]);
			$this->assign('clendar', $clendar);
		}
	}
	
	/**
	 * 操作用户查询
	 *
	 * @author	matengfei
	 * @param 	string or array $cond
	 * @param	string $field_name
	 * @return	void
	 */
	protected function userCond(&$cond=[], $field_name='create_user'){
		$field = input("?get.{$field_name}") ? input("get.{$field_name}/d") : '';
		if ($field) {
			$cond[$field_name] = $field;
		}
		$users = model('user')->getAll(1);
		$user_option = make_option($users, $field, 'username');
		$this->assign('user_option', $user_option);
	}

	/**
	 * 添加操作日志
	 *
	 * @author	matengfei
	 * @param	array $app
	 * @return	void
	 */
	private function addOperateLog(){
	    $data = ['operate_module'=>strtolower(MODULE_NAME),'operate_controller'=>strtolower(CONTROLLER_NAME),'operate_action'=>strtolower(ACTION_NAME),'operate_param'=>strtolower(PARAM_NAME)];
		$data['url'] = $this->request->domain().$this->url;
		$data['operate_param'] = $this->param;
	    $data['admin_id'] = $this->userId;
		$data['ip'] = $this->request->ip();
		$data['sessionid'] = $_SESSION['think']['user_auth_sign'];
		$data['useragent'] = $this->request->server('HTTP_USER_AGENT');
		$data['content'] = serialize($_REQUEST);
		//用户信息
		$user_info = model('user')->getInfo($this->userId);
		$data['username'] = $user_info['username'];
	    $data['create_time'] = time();
		$data['update_time'] = time();
	    $menu_model = model('menu');
	    $cond = ['module'=>strtolower(MODULE_NAME),'controller'=>strtolower(CONTROLLER_NAME),'action'=>strtolower(ACTION_NAME)];
	    $menuInfo = $menu_model->getInfoByCond($cond);
	    $data['title'] = '';
	    if (!empty($menuInfo['name'])){
            $data['title'] = $menuInfo['name'];
        }
	    $model = new \app\common\model\OperateLog();
	    $tbl = $model->inittable();
	    $model->table($tbl);
	    $model->edit($data);
    }

    /**
     * APP对应JS
     *
     * @author	matengfei
     * @param	array $app
     * @return	void
     */
    protected function appJs($app=[]){
    	$appJs = [];
    	if (!$app) {
    		if (file_exists(ROOT_PATH . 'public/static/' . strtolower(MODULE_NAME) . '/js/app/' . strtolower(CONTROLLER_NAME) . '/' . strtolower(ACTION_NAME) . '.js')) {
    			$appJs[] = '/static/' . strtolower(MODULE_NAME) . '/js/app/' . strtolower(CONTROLLER_NAME) . '/' . strtolower(ACTION_NAME) . '.js';
    		}
    	} else {
    		foreach ($app as $controller => $action) {
    			if (file_exists(ROOT_PATH . 'public/static/' . strtolower(MODULE_NAME) . '/js/app/' . strtolower($controller) . '/' . strtolower($action) . '.js')) {
    				$appJs[] = '/static/' . strtolower(MODULE_NAME) . '/js/app/' . strtolower($controller) . '/' . strtolower($action) . '.js';
    			}
    		}
    	}
    	$this->assign('appJs', $appJs);
    }
    
}