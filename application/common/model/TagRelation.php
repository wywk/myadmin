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
 * 标签关系模型
 *
 * @author	matengfei
 * @date 	2017-08-01
 * @version 1.0
 */
class TagRelation extends Base {
	
	//类型
	public $type = [
		1 => '栏目标签',
	];
	
	//状态
	public $status = [
		1 => '已执行',
		2 => '执行中',
		3 => '待执行',
	];
	
	//标签链接数
	public $urlNum = 5;

	/**
	 * 自定义初始化
	 */
	protected function initialize(){
		parent::initialize();
	}
	
	/**
	 * 缓存基本信息
	 *
	 * @author 	matengfei
	 * @param 	int $id
	 * @return 	array
	 */
	protected function _cacheInfo($id){
		$info = $this::get($id)->toArray();
		if ($info) {			
			//所属频道
			$info['item_name'] = '';
			if ($info['item_id']) {
				$itemInfo = model('item')->get($info['item_id'])->toArray();
				$info['item_name'] = $itemInfo['name'];
			}
			//所属栏目
			$info['type_name'] = '';
			if ($info['type_id']) {
				if ($info['type'] == 1) {
					$cateInfo = model('cate')->get($info['type_id'])->toArray();
					$info['type_name'] = $cateInfo['name'];
				}
			}
				
			$tagInfo = model('tag')->get($info['tag_id'])->toArray();
			$info['name'] = $tagInfo['name'];
				
			$cond = [];
			$cond['relation_id'] = $id;
			$cond['mark'] = 1;
			//$urls = model('tag_url')->getList($cond);
			$urls = db('tag_url')->where($cond)->select(); //直接查库，防止缓存嵌套
			$info['list'] = $urls;
		}
		$info = $this->formatInfo($info);
		return $info;
	}
	
	/**
	 * 根据分类和分类ID获取标签
	 *
	 * @author	matengfei
	 * @param	int $type 类型：1栏目
	 * @param	int or string or array $type_id 类型ID
	 * @return 	array
	 */
	public function getTagsByTypeAndTypeId($type, $type_id){
		$cond = [];
		$cond['b.mark'] = 1;
		$cond['b.length'] = ['>', 0];
		$cond['a.type'] = $type;
		$cond['a.url_number'] = ['>', 0];
		if (is_array($type_id)) {
			$cond['a.type_id'] = ['IN', $type_id];
		} else {
			if (strpos($type_id, ',') !== false) {
				$cond['a.type_id'] = ['exp', "IN ({$type_id})"];
			} else {
				$cond['a.type_id'] = $type_id;
			}
		}
		$result = db('tag_relation')
			->alias('a')
			->join('tag b', 'a.tag_id=b.id', 'LEFT')
			->where($cond)
			->field('a.id,b.name')
			->order('b.zhishu DESC,b.length DESC')
			->select();

		$list = [];
		if ($result) {
			foreach ($result as $val) {
				$cond = [];
				$cond['relation_id'] = $val['id'];
				$cond['mark'] = 1;
				$urls = db('tag_url')->where($cond)->field('url,murl')->select(); //直接查库，防止缓存嵌套
				$list[$val['name']] = $urls;
			}
		}
		return $list;
	}
	
	/**
	 * 根据条件缓存信息
	 *
	 * @author 	matengfei
	 * @param 	int $pri 0自然数组 1主键数组
	 * @return 	array
	 */
	protected function _cacheAll($pri=0, $cond=['mark'=>1]){
		$list = $this->getTagsByTypeAndTypeId($cond['type'], $cond['type_id']);
		return $list;
	}
	
}