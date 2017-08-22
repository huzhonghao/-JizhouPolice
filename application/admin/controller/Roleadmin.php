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
class Roleadmin extends Admin
{
	/**
	 * 后台登录首页
	 */
	public function index()
	{
		$this->redirect(url('admin/index/index'));
	}
}