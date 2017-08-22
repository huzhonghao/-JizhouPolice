<?php
namespace app\index\model;

use app\common\model\basemodel;
use think\Db;
use think\Model;


/**
 * 前端user model类
 *
 * Class user
 * @package app\index\model
 */

class User extends basemodel
{
	protected $createTime = 'register_time';
	protected $updateTime = 'last_login_time';
	public function index()
	{
		
	}
	
	public function login($data)
	{
		$code = 1;
		$msg = '';
		$userValidate = validate('User');
		if(!$userValidate->scene('login')->check($data)) {
			return info($userValidate->getError(), 4001);
		}
		if( $code != 1 ) {
			return info($msg, $code);
		}
		$map = [
				'user_no' => $data['user_no'],
				'status' => 1,
				'type' => 0 //前端用户
		];
		$userRow = $this->where($map)->find();
		
		if( empty($userRow) ) {
			return info('You entered the account or password is incorrect, please again', 5001);
		}
		$md_password = mduser( $data['password'] );
		if( $userRow['password'] != $md_password ) {
			return info('You entered the account or password is incorrect, please again', 5001);
		}
		//$mytemp=$userRow;
		
		$mytemp = array(
				'user_no'=>$userRow['user_no'],
				'name'=>$userRow['name'],
				'register_time'=>$userRow['register_time'],
				'phone'=>$userRow['phone'],
				'email'=>$userRow['email'],
				'status'=>$userRow['status'],
				'id'=>$userRow['id'],
				'last_login_time'=>$userRow['last_login_time'],
				'last_login_ip'=>$userRow['last_login_ip'],
				'role_id' => $userRow['id']
		);
		
		$retData = info('Login succeed', $code, '', $mytemp);
		//print_data($retData);
		
		$userRow->last_login_ip     = $data['ip'];
		$userRow->save();
		
		//
		//print_data($retData);
		return $retData;
	}
}