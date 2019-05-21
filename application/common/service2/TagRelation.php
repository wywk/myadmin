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

namespace app\common\service;

use app\common\model\Base;

/**
 * 标签关系服务类
 *
 * @author 	matengfei
 * @date 	2017-08-01
 * @version 1.0
 */
class TagRelation extends Base {
	
	private $model;
	private $article_model;
	public $matchedTagsName;
	public $matchedTags;
	public $replaceLimit;
	public $urlMaxLimit;
	
	/**
	 * 自定义初始化
	 */
	protected function initialize(){
		parent::initialize();
		$this->model = model('tag_relation');
		$this->article_model = model('article');
		$this->replaceLimit = 10;
		$this->urlMaxLimit = 2;
	}	
	
	/**
	 * 匹配文章内容中的标签，替换为链接
	 *
	 * @author	matengfei
	 * @param	string $content 文章内容
	 * @param	int $type 类型：1栏目
	 * @param	int or string $type_id 类型ID
	 * @param	int $id 文章ID
	 * @param	bool $is_mobile 是否手机链接
	 * @return  false or string $content
	 */
	public function matchContentByTypeAndTypeId($content, $type=1, $type_id, $id, $is_mobile=false){
		$this->matchedTagsName = [];
		$this->matchedTags = [];
		if (!$content) {
			return false;
		}
		if ($type == 1) { // 栏目标签
			$info = $this->article_model->getInfo($id, 'item_id,post_time');
			$a_url = $this->article_model->getUrlById($id, $info['item_id'], $info['post_time'], $is_mobile, true);
		}
		$cond = [];
		$cond['type'] = $type;
		$cond['type_id'] = $type_id;
		$this->tags = $tags = $this->model->getAll(0, $cond);
		
		//保留原有的图片和链接
		$tagsHash = [];
		$patterns = ["/<img[^>]*>/i", "/<a[^>]*>[^>]*>/i"];
		foreach ($patterns as $pattern) {
			preg_match_all($pattern, $content, $matches);
			if (!$matches[0]) {
				continue;
			}
			foreach($matches[0] as $key => $val) {
				$hash = 'flag' . md5(microtime(true) . $val . rand(10000,99999));
				$content = str_replace($val, $hash, $content);
				$tagsHash[$hash] = $val;
			}
		}
		//替换关键词
		$replaceCount = 0;
		foreach ($tags as $key => $val) {
			if (false === strpos($content, $key)) {
				continue;
			}
			
			$hash = 'flag' . md5($key . microtime(true) . rand(10000, 99999));
			$tagsHash[$hash] = $key;
			$this->matchedTagsName[] = $key;
			
			if ($replaceCount >= $this->replaceLimit) {
				break;
			}
			
			$urlReplaceCount = 0;
			foreach ((array)$val as $k => $v) {
				if ($urlReplaceCount >= $this->urlMaxLimit) {
					break;
				}
				$url = $is_mobile ? $v['murl'] : $v['url'];
				//内链不允许和当前文章URL一致
				if ($url == $a_url) {
					continue;
				}
				$pattern = "/{$key}/i";
				$repalcement = "<a href=\"{$url}\" target=\"_blank\">{$hash}</a>";
				$content = preg_replace($pattern, $repalcement, $content, 1);
				
				$replaceCount++;
				$urlReplaceCount++;
				$this->matchedTags[$key] = $url;
			}
		}
		
		$content = str_replace(array_keys($tagsHash), array_values($tagsHash), $content);
		return $content;
	}
	
	/**
	 * 获取匹配内容的标签名称
	 *
	 * @author	matengfei
	 * @param	void
	 * @return 	array
	 */
	function getMatchedTagsName(){
		return $this->matchedTagsName;
	}
	
	/**
	 * 获取匹配内容的有内链标签
	 *
	 * @author	matengfei
	 * @param	void
	 * @return 	array
	 */
	function getMatchedTags(){
		return $this->matchedTags;
	}
	
}