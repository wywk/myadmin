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
 * 文章模型
 *
 * @author	matengfei
 * @date 	2017-08-01
 * @version 1.0
 */
class Article extends Base {
	
	//是否显示
	public $is_show = [
		1 => '显示',
		2 => '定时发布',
		3 => '关闭',
	];
	
	//是否推荐
	public $is_recom = [
		1 => '推荐',
		2 => '取消',
	];
	
	//审核状态
	public $status = [
		1 => '已审核',
		2 => '未审核',
		3 => '草稿',
	];

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
			//是否推荐
			$info['is_recom_name'] = $info['is_recom'] ? $this->is_recom[$info['is_recom']] : '';
			//所属频道
			$info['item_name'] = '';
			if ($info['item_id']) {
				$itemInfo = model('item')->get($info['item_id'])->toArray();
				$info['item_name'] = $itemInfo['name'];
			}
			//所属栏目
			$info['cate_name'] = '';
			if ($info['cate_id']) {
				$cateInfo = model('cate')->get($info['cate_id'])->toArray();
				$info['cate_name'] = $cateInfo['name'];
			}
			if ($info['post_time']) {
				$info['format_post_time'] = '';
				if ($info['post_time']) {
					$info['format_post_time'] = date('Y-m-d H:i:s', $info['post_time']);
				}
			}
			//关联信息表
			$more = $this::get($id);
			if ($more->relate) {
				$moreInfo = $more->relate->_cacheInfo($id);
				$info = array_merge($info, $moreInfo);
			}
		}
		$info = $this->formatInfo($info);
		return $info;
	}
	
	/**
	 * 根据文章切图获取原图
	 *
	 * @author	matengfei
	 * @param	int $id
	 * @param 	string $file 图片路径
	 * @return	false or string
	 */
	public function getImgByCut($id, $file){
		if (strpos($file, '{PIC_URL}') === false) {
			$file = '{PIC_URL}' . $file;
		}
		$more = $this::get($id);
		if ($more->relate) {
			$moreInfo = $more->relate->getInfo($id, 'imgs');
			if ($moreInfo['imgs']) {
				$imgs_flip = array_flip($moreInfo['imgs']);
				if (isset($imgs_flip[$file])) {
					return $imgs_flip[$file];
				} else {
					return false;
				}
			}
			return false;
		} else {
			return false;
		}
	}
	
	/**
	 * 根据文章ID获取文章地址
	 *
	 * @author	matengfei
	 * @param	int $id
	 * @param	int $item_id
	 * @param	int $post_time
	 * @param	bool $is_mobile
	 * @param	bool $format
	 * @return	string
	 */
	function getUrlById($id, $item_id, $post_time, $is_mobile=false, $format=false){
		$url = '';
		$date = date('Ymd', $post_time);
		$a_id = str_pad($id, 6, '0', STR_PAD_LEFT);
		if ($item_id == 1) {
			$cat = 'a';
		} else {
			$cat = 'a';
		}
		if ($format) {
			$domain = $is_mobile ? '{M_URL}' : '{SITE_URL}';
		} else {
			$domain = $is_mobile ? M_URL : SITE_URL;
		}
		$url = $domain . "{$cat}/{$date}/{$a_id}.html";
		return $url;
	}
	
}