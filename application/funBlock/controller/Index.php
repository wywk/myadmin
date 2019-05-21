<?php
namespace app\funBlock\controller;

use app\common\controller\Dad;

class Index extends Dad {

    public function index() {
//        <a href="{:url('roundScheduleIndicator/index')}">圆形进度指示器</a>
//        $this->view->engine->layout(false);
        return $this->fetch();
    }
}
