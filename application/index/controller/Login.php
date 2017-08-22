<?php

//test file
namespace app\index\controller;

use app\common\controller\Common;
use think\Controller;
use think\Loader;
use think\Request;
use think\Url;
use think\Session;
use think\Config;

class Login extends Controller
{
	/**
	 * 存在session 自动登录
	 */
	public function index()
	{
		//if( Session::has('userinfo', 'index') ) {
			//$this->redirect( url('index/index/index') );
		//}
		//return view();
	}
	
	public function status()
	{
		//$request = Request::instance();

		//step2 get the post data
		$postData = input('post.');
		//print_data($postData);
		if (empty($postData))
		{
			return null;
		}
		if( Session::has(md5($postData['uid']), 'index') )//此处uid为用户id
		{
			$ReturnData = Session::get(md5($postData['uid']), 'index') ;
			return $ReturnData;
		}
		else
		{
			return null;
		}
		
	}
	/**
	 * 账号密码登录
	 */
	public function doLogin()
	{
		//step1 check the request type
		$request = Request::instance();
		$loginIP = $request->ip();
		//print_data($request->ip());
		if( !$request->isAjax() ) {
			return $this->success( 'Request type error' );
		}
		//step2 get the post data
		$postData = input('post.');
		
		//step3 check the captcha
		//$captcha = $postData['captcha'];
		//if(!captcha_check($captcha)){
			//return $this->error( 'Captcha error' );
		//};
		//step4 check the database to login the account
		$loginData = array(
				'user_no'=>$postData['user_no'],
				'password'=>$postData['password'],
				'ip' =>$loginIP
		);
		$ret = Loader::model('User')->login( $loginData );
		if ($ret['code'] !== 1) {
			return $this->error( $ret['msg'] );
		}
		unset($ret['data']['password']);
		//step5 write session
		Session::set(md5($ret['data']['id']), $ret['data'], 'index');
		//print_data($ret['data']);
		$ReturnData = array(
				'user_no'=>$ret['data']['user_no'],
				'name'=>$ret['data']['name'],
				'last_login_time'=>$ret['data']['last_login_time'],
				'last_login_ip'=>$ret['data']['last_login_ip'],
				'userid' => $ret['data']['id'],
				'roleid' => $ret['data']['role_id']
		);
		//print_data($ReturnData);
		//step6 record the login information
		//Loader::model('LogRecord')->record( 'Login succeed' );
		//step7 login success and redirect??
		return $this->success($ret['msg'], null,$ReturnData);
	}
	
	/**
	 * 退出登录
	 */
	public function out()
	{
		session::clear('index');
		return $this->success('退出成功！', url('index/index/index'));
	}
}