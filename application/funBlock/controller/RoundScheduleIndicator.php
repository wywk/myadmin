<?php
namespace app\funBlock\controller;

use app\common\controller\Dad;

class RoundScheduleIndicator extends Dad {

    public function index() {
        return $this->fetch();
    }
}

