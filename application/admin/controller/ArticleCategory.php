<?php
namespace app\admin\controller;

use app\common\controller\Backend;

/**
 * 会员管理控制器
 * @author hujp
 * @date 2019-04-16
 */
class ArticleCategory extends Backend
{
    protected $model;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('ArticleCategory','service');
    }

    /**
     * 列表页
     * @author hujp
     * @date 2019-04-16
     */
    public function index()
    {
        $cond['mark'] = 1;
        $this->fuzzyCond($cond,'name');
        $this->fuzzyCond($cond,'sort');
        $this->timeCond($cond);
        if( $this->params['page'] && $this->params['limit']){
            $list   = $this->model->getList($cond, '*', 'id DESC', $this->params['page'], $this->params['limit']);
            foreach($list as $key => $value){
                $list[$key]['format_add_time'] = date('Y-m-d H:i',$value['add_time']);
            }
            $data   = ['code' => 0,'count' => $this->model->getCount($cond), 'msg' => 'ok', 'data'  => $list];
            return json($data);
        }

        return $this->fetch('article_category/index');
    }

    /**
     * 添加
     * @author hujp
     * @date 2019-04-17
     * @return mixed
     */
    public function add()
    {
        if (IS_POST){
            $ArticleCategoryService = model('ArticleCategory','service');
            if(method_exists($ArticleCategoryService, 'buildDataAdd')){
                $arr = $ArticleCategoryService->buildDataAdd();
            }
            if(method_exists($ArticleCategoryService, 'goEdit')){
                $ArticleCategoryService->goEdit($arr);
            }
        }
        return $this->fetch('article_category/add');
    }

    /**
     * 编辑
     * @author hujp
     * @date 2019-04-16
     * @return mixed|void
     */
    public function edit()
    {
        $id = input('param.id');
        if (empty($id)){
            return $this->error('参数错误！');
        }

        $data = $this->model->getInfo($id);

        if (IS_POST){
            $ArticleCategoryService = model('ArticleCategory','service');
            if (method_exists($ArticleCategoryService,'buildDataEdit')){
                $arr = $ArticleCategoryService->buildDataEdit();
            }
            if (method_exists($ArticleCategoryService,'goEdit')){
                $ArticleCategoryService->goEdit($arr);
            }
        }

        $this->assign('id',$id);
        $this->assign('data',$data);

        return $this->fetch('article_category/edit');
    }

    /**
     * 设置状态
     * @author hujp
     * @date 2019-04-16
     */
    public function setStatus()
    {
        $id     = input('post.id/d');
        $status = input('post.status/d');
        if (empty($id) || empty($status)){
            return $this->error("参数错误！");
        }

        if (IS_POST){
            $ArticleCategoryService = model('ArticleCategory','service');
            if (method_exists($ArticleCategoryService,'goEdit')){
                $ArticleCategoryService->goEdit(['id'=>$id,'status'=>$status]);
            }
        }
    }

    /**
     * 删除
     * @author hujp
     * @date 2019-04-16
     */
    public function drop()
    {
        $id = input('post.id');
        if(empty($id)){
            return $this->error('参数错误！');
        }
        if (IS_POST){
            $ArticleCategoryService = model('ArticleCategory','service');
            if (method_exists($ArticleCategoryService,'goDrop')){
                $ArticleCategoryService->goDrop($id);
            }
        }
    }
}