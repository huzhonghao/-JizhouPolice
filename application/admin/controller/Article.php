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
//global $currentID;
class Article extends Admin
{
	/**
	 * 后台登录首页
	 */
	//public static $currentID;
	public function index()
	{
		$role_id = $this->role_id;
		//print_data($role_id);
		//获得导航栏信息
		$result = $this->getNavInfo($role_id);
		//print_data($result);
		$this->assign('moduleInfo',$result);
		//print_data($id);
		$uid = $this->uid;
    	$this->assign('uid',$uid);
		$request = request()->param();
		//print_data($request);
		
		$this->assign('moduleID',$request['param']);
		//$this->assign('selcModule',$result2);
		
		//$result2 = $this->findSonFromTree($result,$rid);
		//$this->assign('selcModule',$result2);
		
		return view();
		//$this->redirect(url('admin/index/index'));
	}
	
	
	/**
	 * 异步获取列表数据
	 *
	 * @author chengbin
	 * @return mixed
	 */
	public function getList()
	{
		if(!request()->isAjax()) {
			$this->error(lang('Request type error'), 4001);
		}
		$request = request()->param();
		//$data = model('User')->getList( $request );
		//$mycurrentID = self::$currentID;
		//print_data($request);
		$News = Loader::model('News');
		$data = $News->getNewsByPage($request['father']);
		return $data;
		
		//return $data;
	}
	
	public function delete()
	{
		//print_data('222222222');
		if(!request()->isAjax()) {
			$this->error(lang('Request type error'), 4001);
		}
		
		$request = request()->param();
		//print_data($request);
		$id = $request['id'];
		if(empty($id)){
			return info(lang('Data ID exception'), 0);
		}
		if (intval($id == 1 || in_array(1, explode(',', $id)))) {
			return info(lang('Delete without authorization'), 0);
		}
		return Loader::model('News')->deleteById($id);
	}
	public function edit()
	{
		if(!request()->isAjax()) {
			$this->error(lang('Request type error'), 4001);
		}
		
		$request = request()->param();
		$id = $request['id'];
		$this->assign('uid',$request['uid']);
		//print_data($request);
		return $this->fetch();
		
	}
	public function add()
	{
		$request = request()->param();
		$this->assign('uid',$request['uid']);
		return $this->fetch('edit');
	}
	public function savedata()
	{
		$request = request()->param();
	}
}