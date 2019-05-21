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
 * 文章管理服务类
 *
 * @author 	matengfei
 * @date 	2017-08-01
 * @version 1.0
 */
class Article extends Base {
	
	private $model;
	private $tag_relation_model;
	private $tag_url_model;
	private $tag_relation_obj;
	private $is_water;
	
	/**
	 * 自定义初始化
	 */
	protected function initialize(){
		parent::initialize();
		$this->model = model('article');
		$this->tag_relation_model = model('tag_relation');
		$this->tag_url_model = model('tag_url');
		$this->tag_relation_obj = model('tag_relation', 'service');
		$this->is_water = false; //是否添加水印
	}
	
	/**
	 * 过滤内容中的标签
	 * 
	 * @author	matengfei
	 * @param 	string $content
	 * @param 	string $title
	 * @return 	string
	 */
	public function filterContent($content, $title){
		$content = $this->replaceTitle($content, $title, $title);
		$content = ecm_stripTags(['a','A'], $content);
		//$content = preg_replace('/\s*(height|width)=(\'|").*?\2/', '', $content);
		$content = preg_replace('/\s*(?:height|width)=(\'|").*?\1/', '', $content);
		$pattern = "/\s*(<p[^>]*?>[\s]*?<br(?:[^>])*?>[\s]*?<\/p>)/i";
		if (preg_match($pattern, $content)) {
			$content = trim(preg_replace($pattern, '', $content));
		}
		return $content;
	}
	
	/**
	 * 过滤图片标签中的title和alt属性
	 *
	 * @author	matengfei
	 * @param 	string $str
	 * @param 	string $title
	 * @param 	string $alt
	 * @param	string $class
	 * @return 	string
	 */
	private function replaceTitle($str, $title, $alt, $class='img-cls'){
		$str = str_replace('class="'.$class.'" ', '', $str);
		$class = 'class="'.$class.'"';
		$title = 'title="'.$title.'"';
		$alt = 'alt="'.$alt.'"';
		$title_flag = preg_match_all('/<\s*img\s+[^>]*?(title=\"[^\"]+\")[^>]*?\/?\s*>/i', $str, $title_arr);
		$alt_flag = preg_match_all('/<\s*img\s+[^>]*?(alt=\"[^\"]+\")[^>]+>/isU', $str, $alt_arr);
		if (!$title_flag) {
			$str = str_replace('<img', '<img '.$title.' ', $str);
		} else {
			for ($i=0; $i<count($title_arr[1]); $i++) {
				$str = str_replace($title_arr[1][$i], $title, $str);
			}
		}
		if (!$alt_flag) {
			$str = str_replace('<img', '<img '.$alt.' ', $str);
		} else {
			for ($i=0; $i<count($alt_arr[1]); $i++) {
				$str = str_replace($alt_arr[1][$i], $alt, $str);
			}
		}
		return $str;
	}
	
	/**
	 * 保存内容中的图片
	 *
	 * @author	matengfei
	 * @param 	string $content
	 * @param	string $dir
	 * @param	int $file_type
	 * @param	int $id
	 * @return 	array
	 */
	public function saveImgByContent($content, $dir='article', $file_type=IMG_ARTICLE_CONTENT, $id=false) {
		$image_obj = model('image', 'service');
		$temp = $image_obj->getImgByContent($content, $dir);
		$content = $temp['content'];
		$arrImg  = $temp['image'];
		$img = [];
		if ($arrImg) {
			$cut_image_model = model('image_cut');
			foreach ($arrImg as $iId => $iVal) {
				$oldPath = ecm_rename($iVal, $dir);
				$path = str_replace('pic/', '', $oldPath);
				if (!$id) { //添加
					if (!in_array(pathinfo($path, PATHINFO_EXTENSION), config('img_upload.exts'))) {
						continue;
					}
					$pic_str = '{PIC_URL}' . $path;
					$img[$pic_str] = '';
					$content = str_replace($iVal, $pic_str, $content);
					$cut_image_model->isMd5 = true;
					$cut = $cut_image_model->crop($oldPath, $file_type, $this->is_water);
					$cut_image_model->isMd5 = false;
					if (!$cut) {
						continue;
					}
					$cut_str = str_replace(PIC_URL, '{PIC_URL}', current($cut));
					$img[$pic_str] = $cut_str;
					$content = str_replace($pic_str, $cut_str, $content);		
				} else { //编辑
					$pic_str = $this->model->getImgByCut($id, $path);
					if ($pic_str) {
						$cut_str = '{PIC_URL}' . $path;
						$img[$pic_str] = $cut_str;
						$content = str_replace($iVal, $cut_str, $content);
					} else {
						if (!in_array(pathinfo($path, PATHINFO_EXTENSION), config('img_upload.exts'))) {
							continue;
						}
						$pic_str = '{PIC_URL}' . $path;
						$img[$pic_str] = '';
						$content = str_replace($iVal, $pic_str, $content);
						$cut_image_model->isMd5 = true;
						$cut = $cut_image_model->crop($oldPath, $file_type, $this->is_water);
						$cut_image_model->isMd5 = false;
						if (!$cut) {
							continue;
						}
						$cut_str = str_replace(PIC_URL, '{PIC_URL}', current($cut));
						$img[$pic_str] = $cut_str;
						$content = str_replace($pic_str, $cut_str, $content);	
					}
				}
			}
		}
		$list['content'] = $content;
		$list['imgs']    = !empty($img) ? serialize($img) : '';
		return $list;
	}
	
	/**
	 * 根据文章ID匹配标签
	 *
	 * @author	matengfei
	 * @param	int $id
	 * @return 	bool
	 */
	function matchContentById($id){
		$info = $this->model->getInfo($id, 'item_id,cate_id,pid,title,content,post_time');
		$content = $info['content'];
		$type    = 1;
		$type_id = $info['cate_id'];
		$pid     = $info['pid'];
		
		$cond = [];
		$cond['type']    = $type;
		$cond['type_id'] = $type_id;
		$relations = $this->tag_relation_model->getAll(0, $cond);
		$vcontent = '';
		$mcontent = '';
		$vtags = [];
		$mtags = [];
		$tags = [];
		if ($relations) {
			$vcontent = $this->tag_relation_obj->matchContentByTypeAndTypeId($content, $type, $type_id, $id);
			$vtags = $this->tag_relation_obj->getMatchedTags();
			$mcontent = $this->tag_relation_obj->matchContentByTypeAndTypeId($content, $type, $type_id, $id, 1);
			$mtags = $this->tag_relation_obj->getMatchedTags();
		} else { //当前栏目无匹配标签则匹配上级标签
			if ($pid) {
				$vcontent = $this->tag_relation_obj->matchContentByTypeAndTypeId($content, $type, $pid, $id);
				$vtags = $this->tag_relation_obj->getMatchedTags();
				$mcontent = $this->tag_relation_obj->matchContentByTypeAndTypeId($content, $type, $pid, $id, 1);
				$mtags = $this->tag_relation_obj->getMatchedTags();
			}
		}
		if ($vtags) {
			foreach ($vtags as $key => $val) {
				$tags[$key]['url'] = $val;
				$tags[$key]['murl'] = $mtags[$key];
			}
		}
		$matchedTagsName = $this->tag_relation_obj->matchedTagsName;
		if ($matchedTagsName) {
			$keywords = implode(',', $matchedTagsName);
		} else {
			$keywords = $info['title'];
		}
		
		$data = [];
		$data['id'] = $id;
		$more = [];
		$more['keywords'] = $keywords;
		$more['tags']     = !empty($tags) ? serialize(array_values($tags)) : '';
		$more['vcontent'] = $vcontent;
		$more['mcontent'] = $mcontent;
		$result = $this->model->editInfo($data, $more);
		if (!$result) {
			return false;
		}
		
		if ($matchedTagsName) {
			foreach ($matchedTagsName as $val) {
				$cond = [];
				$cond['name'] = $val;
				$cond['mark'] = 1;
				$tag_id = model('tag')->isExist($cond);
				$data = [];
				$data['type']    = $type;
				$data['type_id'] = $type_id;
				$data['tag_id']  = $tag_id;
				$relation_id = $this->tag_relation_model->isExist($data);
				if ($relation_id) {
					$url = $this->model->getUrlById($id, $info['item_id'], $info['post_time'], false, true);
					$murl = $this->model->getUrlById($id, $info['item_id'], $info['post_time'], true, true);
					$cond = [];
					$cond['relation_id'] = $relation_id;
					$cond['url']  = $url;
					$cond['mark'] = 1;
					$tag_url_id = $this->tag_url_model->isExist($cond);
					if (!$tag_url_id) {
						$url_data = [];
						$url_data['relation_id'] = $relation_id;
						$url_data['url'] = $url;
						$url_data['murl'] = $murl;
						$url_data['create_user'] = 1;
						$url_data['create_time'] = time();
						$result = $this->tag_url_model->create($url_data);
						$tag_url_id = $this->tag_url_model->isExist($cond);
						if ($tag_url_id) {
							$cond = [];
							$cond['relation_id'] = $relation_id;
							$cond['mark'] = 1;
							$url_number = $this->tag_url_model->getCount($cond);
							$data = [];
							$data['url_number'] = $url_number;
							$data['state'] = 1;
							$data['update_user'] = 1;
							$data['update_time'] = time();
							$this->tag_relation_model->doEdit($data, $relation_id);
						}
					}
				}
			}
		}
		$data = [];
		$data['state'] = 1;
		$result = $this->model->doEdit($data, $id);
		if ($result === false) {
			return false;
		} else {
			return true;
		}
	}
	
}