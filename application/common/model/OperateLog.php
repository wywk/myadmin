<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2017 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: matengfei <matengfei2000@gmail.com>
// +----------------------------------------------------------------------

namespace app\common\model;

use app\common\model\Base;

/**
 * 操作日志模型
 *
 * @author	matengfei
 * @date    2017-08-01
 * @version 1.0
 */
class OperateLog extends Base {
	
	public $is_cache = false;
	
    protected $table;

    public function initialize(){
        parent::initialize();
        $this->table = 'operate_log_'.date('Y').'_'.date('m');
        $this->inittable();
    }

    /**
     * 初始化表
     */
    public function inittable(){
        $tbl = $this->table;
        if (!$this->table_exists($tbl)) {
        	$tbl = config('database.prefix').$tbl;
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
              `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
              PRIMARY KEY (`id`),
              KEY `name` (`username`)
            ) ENGINE=InnoDB AUTO_INCREMENT=509 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='管理员日志表'";
            $this->query($sql);
        } else {
        	$tbl = config('database.prefix').$tbl;
        }
        return $tbl;
    }
    
    /**
     * 数据分页列表
     *
     * @author matengfei
     * @param array $where
     * @param string $order
     * @return mixed
     */
    public function pageList($where = ['mark'=>1], $order = 'id DESC', $fields = '*', $alias = [], $join = [], $group = [], $pageNum =''){
    	if(!$pageNum){
    		$pageNum = config('paginate.list_rows');
    	}
        $list = $this->where($where)->field($fields)->order($order)->paginate($pageNum, false, [
            'query' => $this->param,
        ]);
        if ($list) {
        	$user_model = model('user');
            foreach ($list as &$lt) {
                if ($lt['create_time']){
                    $lt['format_create_time'] = date('Y-m-d H:i:s', $lt['create_time']);
                    if ($lt['operate_user_id']) {
                    	$userInfo = $user_model->getInfo($lt['operate_user_id'], 'id,username');
                        $lt['operate_user_name'] = $userInfo['username'];
                    }
                }
            }
        }
        $result = [
            'list' => $list,
            'page' => $list->render()
        ];
        return $result;
    }
    
}