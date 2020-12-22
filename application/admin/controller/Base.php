<?php
namespace app\admin\controller;

use think\Controller;
use think\Request;

class Base extends Controller {
    public function __construct(Request $request = null)
    {
        //实现父类的构造函数
        parent::__construct($request);
        //登录检测
        if ( ! session('?manager_info')){
            //没登录
            return $this->redirect('admin/login/login');
        }
    }
}