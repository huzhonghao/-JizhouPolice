<?php
namespace app\index\validate;

use think\Validate;

class User extends Validate
{

    protected $rule =   [
    	'user_no'			=> 'require|length:4',
        'mobile'              => 'require|length:11',
        'password'              => 'length:6,16',
        'role_id' => 'require',
    ];

    protected $message  =   [
    		'user_no'			=> 'require',
        'mobile.require'      => 'Mobile require',
        'mobile.length'       => 'Please enter a correct mobile',
        'password.length'       => 'Please enter a correct password',
    ];

    protected $scene = [
        'add' => ['user_no','mobile','password', 'role_id'],
        'login' =>  ['user_no','password'],
        'edit' => ['user_no','mobile', 'password', 'role_id']
    ];

}


