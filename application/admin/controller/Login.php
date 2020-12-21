<?php

namespace app\admin\controller;

use think\Controller;

class Login extends Controller
{
    public function login(){
        $this->view->engine->layout(false);
        return view();
    }
}
