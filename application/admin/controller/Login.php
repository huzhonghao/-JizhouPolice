<?php
namespace app\admin\controller;

use app\common\controller\Common;
use think\Controller;
use think\Loader;
use think\Request;
use think\Url;
use think\Session;
use think\Config;

/**
* 登录
* @author aierui github  https://github.com/Aierui
* @version 1.0 
*/
class Login extends Common
{
	/**
	 * 后台登录首页
	 */
	public function index()
	{
		//if( Session::has('userinfo', 'admin') ) {
			//$this->redirect( url('admin/index/index') );
		//}
		return view();
	}

	/**
	 * 登录验证
	 */
	public function doLogin()
	{
		if( !Request::instance()->isAjax() ) {
			return $this->success( lang('Request type error') );
		}

		$postData = input('post.');
		$captcha = $postData['captcha'];
		if(!captcha_check($captcha)){
			return $this->error( lang('Captcha error') );
		};
		$loginData = array(
			'user_no'=>$postData['user_no'],
			'password'=>$postData['password'],
				'type' => 1
		);
		$ret = Loader::model('User')->login( $loginData );
		if ($ret['code'] !== 1) {
			return $this->error( $ret['msg'] );
		}
		unset($ret['data']['password']);
		//print_data($ret);
		//get user's role-id
		//$role_id = Loader::model('UserRole')->getRoleIDbyUserID($ret['data']['id']);
		//if( empty($role_id) ) {
			//return info(lang('The user is not belong to any role, please again'), 5001);
		//}
		//$ret['data']['role_id'] = $role_id[0];
		//print_data($ret);
		Session::set(md5($ret['data']['id']), $ret['data'], 'admin');
		//Session::set('jzhuserinfo', $ret['data'], 'admin');
		//print_data($ret);
		Loader::model('LogRecord')->record( lang('Login succeed'),1);
		return $this->success($ret['msg'], url('admin/index/index',['uid'=>$ret['data']['id']]));
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
		if( Session::has(md5($postData['uid']), 'admin') )//此处uid为用户id
		{
			$ReturnData = Session::get(md5($postData['uid']), 'admin') ;
			return $ReturnData;
		}
		else
		{
			return null;
		}
	
	}

	/**
	 * 退出登录
	 */
	public function out()
	{
		session::clear('admin');
		return $this->success('退出成功！', url('admin/login/index'));
	}
}