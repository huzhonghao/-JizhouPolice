<?php
//test php file
namespace app\index\controller;

class Captcha extends \think\Controller
{

    // 验证码表单
    public function index()
    {
        return $this->fetch();
    }

}