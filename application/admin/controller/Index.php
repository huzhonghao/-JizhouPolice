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
* 登录
* @author aierui github  https://github.com/Aierui
* @version 1.0 
*/
class Index extends Admin
{
	/**
	 * 后台登录首页
	 */
	public function index()
	{
		//print_data('111111');
		//$data['ip'] = Loader::model('LogRecord')->UniqueIpCount();
		//$moduleInfo = $this->accessRule;
		//print_data($moduleInfo);
		//$this->assign('moduleInfo',$moduleInfo);
		//$this->assign('data', $data);
		//动态显示左侧导航列表
		$result = $this->accessRule;
		$uid = $this->uid;
		//print_data($result);
    	$this->assign('moduleInfo',$result);
    	$this->assign('uid',$uid);
    	
		$sysInfo = $this->getSysInfo();
		$this->assign('sysInfo',$sysInfo);
		
		if (empty($result))
		{
			return info('当前用户没有任何权限');
		}
		return view();
	}
	
	protected function getSysInfo()
	{
		$mysysinfo = array(
				'os' => $_SERVER["SERVER_SOFTWARE"], //获取服务器标识的字串
				'version' => PHP_VERSION, //获取PHP服务器版本
				'ThinkPHP' => THINK_VERSION,
				'time' => date("Y-m-d H:i:s", time()), //获取服务器时间
				'pc' => $_SERVER['SERVER_NAME'], //当前主机名
				'osname' => php_uname(), //获取系统类型及版本号
				'language' => $_SERVER['HTTP_ACCEPT_LANGUAGE'], //获取服务器语言
				//'port' => $_SERVER['SERVER_PORT'], //获取服务器Web端口
				'max_upload' => ini_get("file_uploads") ? ini_get("upload_max_filesize") : "Disabled", //最大上传
				'max_ex_time' => ini_get("max_execution_time")."秒", //脚本最大执行时间
				'mysql_version' => $this->_mysql_version(),
				//'mysql_size' => $this->_mysql_db_size(),
		);
		//print_data($mysysinfo);
		return $mysysinfo;
	}
	private function _mysql_version()
	{
		$version = mysqli_get_client_info();
		return $version;
	}
	
}