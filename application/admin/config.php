<?php
//配置文件
return [
    'template' => [
        //开启布局
        'layout_on'    => true,
        //布局文件名称
        'layout_name'    => 'layout'
    ],
    'msg' => [
        'sys_err_msg' => '非法访问'
    ],
    //验证码配置
    'captcha' => [
        // 验证码位数
        'length' => 4,
        // 是否画混淆曲线
        'useCurve' => false
    ]
];