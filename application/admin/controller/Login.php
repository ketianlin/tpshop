<?php

namespace app\admin\controller;

use think\Controller;
use app\admin\model\Manager;

class Login extends Controller
{
    public function login(){
        //一个方法 处理两个业务逻辑：页面展示  表单提交
        if (request()->isPost()){
            //post请求  表单提交
            //接收参数  username  password  code
            $params = input();
            $rule = [
                'username|用户名' => 'require',
                'password|密码' => 'require',
                'code|验证码' => 'require'
                //'code|验证码' => 'require|captcha'
            ];
            $res = $this->validate($params, $rule);
            if($res !== true){
                return $this->error($res);
            }
            //验证码手动校验
            if(!captcha_check($params['code'])){
                return $this->error('验证码错误');
            }
            //查询管理员用户表
            $manager = Manager::where('username', $params['username'])->find();
            if (empty($manager)){
                //用户名或密码错误
                return $this->error('用户名或密码错误');
            }
            //登录成功
            //设置登录标识到session
            session('manager_info', $manager->toArray());
            //页面跳转
            return $this->success('登录成功', 'admin/index/index');
        }
        //get请求  页面展示
        //临时关闭全局模板布局
        $this->view->engine->layout(false);
        return view();
    }

    public function logout(){
        //清空所有session
        session(null);
        $this->redirect('admin/login/login');
    }
}
