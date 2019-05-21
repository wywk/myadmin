<?php
namespace app\common\lib;

class ViewFileConfig {
    protected $ts;  //控制器对象
    protected $m;
    protected $c;
    protected $a;
    protected $ca;
    protected $aloneFile;   //单独文件
    public    $appBeforeAction  = NULL;
    public    $appAfterAction   = NULL;

    public function __construct($controller){
        $this->ts   = $controller;
        $this->m    = $this->ts->params['module'];
        $this->c    = $this->ts->params['controller'];
        $this->a    = $this->ts->params['action'];
        $this->ca   = $this->c . '::' . $this->a;

        $appBeforeAction    = 'appBefore' . \think\Loader::parseName($this->m, 1);
        $appAfterAction     = 'appAfter'  . \think\Loader::parseName($this->m, 1);
        if (is_callable([$this, $appBeforeAction])) $this->appBeforeAction  = $appBeforeAction;
        if (is_callable([$this, $appAfterAction]))  $this->appAfterAction   = $appAfterAction;
    }

    /**
     * 公共前端文件配置，每次访问URL方法之前都会执行(仅对admin模块有效)
     * @author	luffy
     * @date    2018-09-22
     */
    public function appBeforeAdmin(){
        $this->ts->assign->addCss('public/artDialog/css/simple.css');
        $this->ts->assign->addCss('public/layui/css/layui.css');
        $this->ts->assign->addCss('public/css/global.css');
        $this->ts->assign->addCss('public/css/animate.css');
        $this->ts->assign->addCss('public/awesome-4.7.0/css/font-awesome.min.css');
        $this->ts->assign->addCss('public/css/index.css');

        $this->ts->assign->addJs('public/js/jquery-3.2.1.min.js');
        $this->ts->assign->addJs('public/layui/layui.js');
        $this->ts->assign->addJs('public/js/global.js');
        $this->ts->assign->addJs('public/js/common.js');
        $this->ts->assign->addJs('public/kindeditor/kindeditor.js');
    }

    /**
     * 公共前端文件配置，每次访问URL方法之前都会执行(仅对admin模块有效)
     * @author	luffy
     * @date    2018-09-22
     */
    public function appAloneAdmin($string){
        switch ($string){
            case 'indexss/index':
                break;
        }
    }

    /**
     * 公共前端文件配置，每次访问URL方法之前都会执行(仅对funBlock模块有效)
     * @author	luffy
     * @date    2018-09-19
     */
    public function appBeforeFunblock(){
        $this->ts->assign->addCss('public/artDialog/css/simple.css');
        $this->ts->assign->addCss('public/layui/css/layui.css');
        $this->ts->assign->addCss('public/css/global.css');
        $this->ts->assign->addCss('public/css/animate.css');
        $this->ts->assign->addCss('public/awesome-4.7.0/css/font-awesome.min.css');
        $this->ts->assign->addCss('public/css/index.css');

        $this->ts->assign->addJs('public/js/jquery-3.2.1.min.js');
        $this->ts->assign->addJs('public/layui/layui.js');
        $this->ts->assign->addJs('public/js/global.js');
//        $this->ts->assign->addJs('public/kindeditor/kindeditor.js');
//        $this->ts->assign->addJs('public/js/jquery.nicescroll.min.js');
    }

    /**
     * 公共前端文件配置，每次访问URL方法之前都会执行(仅对funBlock模块有效)
     * @author	luffy
     * @date    2018-09-19
     */
    public function appAloneFunblock($string){
        switch ($string){
            case 'RoundScheduleIndicator/index':
                $this->ts->assign->addJs('public/js/radialIndicator/radialIndicator.min.js');
            case 'Article/add':
                $this->ts->assign->addJs('public/kindeditor/kindeditor.js');
            break;
        }
    }
}
