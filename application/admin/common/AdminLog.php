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

namespace app\admin\model;

use app\common\model\Base;

/**
 * 管理员日志模型
 * @author	luffy
 * @date    2019-04-01
 * @version 1.0
 */
class AdminLog extends Base {
	
    protected $table;
    public $db;
    //自定义日志标题
    protected static $title = '';
    //自定义日志内容
    protected static $content = '';

    public function initialize(){
        parent::initialize();
        $this->table = 'admin_log_'.date('Y').'_'.date('m');
        $this->inittable();
        $this->db = db($this->table);
    }

    /**
     * 初始化表
     */
    public function inittable(){
        if (!$this->table_exists($this->table)) {
            $tbl = config('database.prefix').$this->table;
            $sql = "CREATE TABLE `{$tbl}` (
				`id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
				`admin_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '管理员ID',
				`username` varchar(20) NOT NULL DEFAULT '' COMMENT '管理员用户名',
				`operate_module` varchar(20) NOT NULL COMMENT '操作模块',
				`operate_controller` varchar(20) NOT NULL COMMENT '操作控制器',
				`operate_action` varchar(20) NOT NULL COMMENT '操作方法',
				`operate_param` varchar(200) NOT NULL DEFAULT '' COMMENT '操作参数',
				`url` varchar(100) NOT NULL DEFAULT '' COMMENT '操作页面',
				`title` varchar(100) NOT NULL DEFAULT '' COMMENT '日志标题',
				`content` varchar(1000) NOT NULL DEFAULT '' COMMENT '内容',
				`ip` varchar(50) NOT NULL DEFAULT '' COMMENT 'IP',
				`useragent` varchar(255) NOT NULL DEFAULT '' COMMENT 'User-Agent',
				`sessionid` varchar(100) NOT NULL DEFAULT '' COMMENT 'sessionID',
				`create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '操作时间',
				PRIMARY KEY (`id`),
				KEY `name` (`username`)
				) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='管理员日志表';";
            $this->query($sql);
        }
        return $this->table;
    }
    
    public static function setTitle($title){
    	self::$title = $title;
    }
    
    public static function setContent($content){
    	self::$content = $content;
    }
    
    public function record($title = ''){
    	$admin = session('admin_auth');
    	$admin_id = $admin ? $admin['id'] : 0;
    	$username = $admin ? $admin['username'] : __('Unknown');
    	$content = self::$content;
    	if (!$content) {
    		$content = request()->param();
    		foreach ($content as $k => $v) {
    			if (is_string($v) && strlen($v) > 200 || stripos($k, 'password') !== false) {
    				unset($content[$k]);
    			}
    		}
    	}
    	$title = self::$title;
    	if (!$title) {
    		$title = [];
    		$breadcrumb = \app\admin\library\Auth::instance()->getBreadcrumb();
    		foreach ($breadcrumb as $k => $v) {
    			$title[] = $v['title'];
    		}
    		$title = implode(' ', $title);
    	}
    	$data = [
    		'admin_id'           => $admin_id,
    		'username'           => $username,
    		'operate_module'     => request()->module(),
    		'operate_controller' => request()->controller(),
    		'operate_action'     => request()->action(),
    		'operate_param'      => !empty(request()->param()) ? '?'.http_build_query(request()->param()) : '',
    		'url'                => request()->url(),
	    	'title'              => $title,
	    	'content'            => !is_scalar($content) ? json_encode($content) : $content,
	    	'ip'                 => request()->ip(),
	    	'useragent'          => request()->server('HTTP_USER_AGENT'),
	    	'sessionid'          => session_id(),
	    	'create_time'        => time(),
     	];
    	$this->db->insert($data);
    }
    
}
