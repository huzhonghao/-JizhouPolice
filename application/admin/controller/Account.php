<?php
namespace app\admin\controller;

use app\common\controller\Common;
use think\Controller;
use think\Loader;
use think\Request;
use think\Url;
use think\Session;
use think\Config;
use think\db;

/**
 * 系统信息首页
 * @author aierui github  https://github.com/Aierui
 * @version 1.0
 */
class Account extends Admin
{
	/**
	 * 后台登录首页
	 */
	public function index()
	{
		
	}
	public function _empty()
	{
		$role_id = $this->role_id;
		//print_data($role_id);
		//获得导航栏信息
		$result = $this->getNavInfo($role_id);
		$this->assign('moduleInfo',$result);
		//print_data($id);
		
		$this->redirect(url('admin/index/index'));
	}
}