<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件

if ( ! function_exists('encrypt_password')){
    //密码加密函数
    function encrypt_password($password){
        //加盐
        $salt = '111111';
        return md5($salt . md5($password));
    }
}

if( ! function_exists('curl_request')){
    //发送curl请求
    function curl_request($url, $type = false, $params = [], $https=false){
        //调用curl_init() 初始化请求
        $ch = curl_init($url);
        //调用curl_setopt()设置请求选项
        if ($type){
            //true 发送post请求  false 默认发送get请求
            //post请求  设置请求方式
            curl_setopt($ch, CURLOPT_POST, true);
            //设置请求参数
            curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
        }
        //如果是https请求 需要禁止从服务器端验证本地的证书
        if ($https){
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        }
        //模拟浏览器访问
        $agent = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/86.0.4240.75 Safari/537.36';
        curl_setopt($ch, CURLOPT_USERAGENT, $agent);
        //调用curl_exec() 发送请求 获取结果
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $res = curl_exec($ch);

        if(!$res){
            //错误信息
            $error = curl_error($ch);
            //错误码
            $errno = curl_errno($ch);
            dump($error);
            dump($errno);exit;
        }
        //调用curl_close() 关闭请求
        curl_close($ch);

        return $res;
    }
}