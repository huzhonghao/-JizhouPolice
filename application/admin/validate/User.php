<?php
namespace app\admin\validate;

use think\Validate;

class User extends Validate
{

    protected $rule =   [
        'user_no'              => 'require',
        'password'              => 'length:6,16',
        'role_id' => 'require',
    ];

    protected $message  =   [
        'phone.require'      => 'phone require',
        'phone.length'       => 'Please enter a correct mobile',
        'password.length'       => 'Please enter a correct password',
    ];

    protected $scene = [
        'add' => ['user_no','phone','password', 'role_id'],
        'login' =>  ['user_no','password'],
        'edit' => ['user_no','phone', 'password', 'role_id']
    ];

}


