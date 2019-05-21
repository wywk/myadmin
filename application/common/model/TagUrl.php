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
 * 标签链接模型
 *
 * @author	matengfei
 * @date 	2017-08-01
 * @version 1.0
 */
class TagUrl extends Base {

	/**
	 * 自定义初始化
	 */
	protected function initialize(){
		parent::initialize();
	}
	
	/**
	 * 添加或修改
	 *
	 * @author	matengfei
	 * @param 	array $data
	 * @param 	int $relation_id 标签关系ID
	 * @param 	int $user_id 用户ID
	 * @return 	int or false
	 */
	function editByRelationId($data, $relation_id, $user_id){
		if (!$relation_id) {
			return false;
		}
		//先更新已有数据为不可用
		$this->dropByRelationId($relation_id, $user_id);
		
		//添加或修改标签链接
		$i = 0;
		if ($data['url']) {
			foreach ($data['url'] as $key => $url) {
				if ($url) {
					$murl = $data['murl'][$key] ? trim($data['murl'][$key]) : '';
					$sort = $data['sort'][$key] ? (int)$data['sort'][$key] : '125';
					$id = $this->isExist(['url'=>$url,'relation_id'=>$relation_id]);
					if ($id) { //url已存在,更新						
						$url_data = [];
						$url_data['murl'] = $murl;
						$url_data['sort'] = $sort;
						$url_data['update_user'] = $user_id;
						$url_data['update_time'] = time();
						$url_data['mark'] = 1;
						$cond = [];
						$cond['id'] = $id;								
						$this->where($cond)->update($url_data);
					} else { //url不存在,添加
						$url_data = [];
						$url_data['relation_id'] = $relation_id;
						$url_data['url'] = $url;
						$url_data['murl'] = $murl;
						$url_data['sort'] = $sort;
						$url_data['create_user'] = $user_id;
						$url_data['create_time'] = time();
						$this->create($url_data);
					}
					$i++;
				}
			}
		}
		return $i;
	}
	
	/**
	 * 根据标签关系删除标签链接
	 *
	 * @author	matengfei
	 * @param 	int $relation_id
	 * @param	int $user_id
	 * @return	void
	 */
	function dropByRelationId($relation_id, $user_id){
		$data = [];
		$data['update_user'] = $user_id;
		$data['update_time'] = time();
		$data['mark'] = 0;
		$cond = [];
		$cond['relation_id'] = $relation_id;
		$cond['mark'] = 1;	
		$this->where($cond)->update($data);
	}
	
}