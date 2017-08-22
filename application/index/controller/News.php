<?php
namespace app\index\controller;
use think\Controller;
use think\Loader;
use think\Session;
use think\Request;
use think\Url;
use app\common\tools;
use think\Db;


class News extends Controller
{
	
	public function index($id)
	{
		
		$news = Loader::model('News');
		$newsContent = $news->getNewsInfo($id);	
		$fatherId = $newsContent['father_module'];
		//print_data($newsContent,true);
		$this->assign('newscontent',$newsContent);
		
		$sonModule = Loader::model('SonModule');
		$sonmoduleInfo = $sonModule->getsonModudelInfo($fatherId);
		//print_data($sonmoduleInfo,true);
		$this->assign('sonmoduleInfo',$sonmoduleInfo);

		$module = Loader::model('Module');
		$moduleInfo = $module->getModudelInfo($sonmoduleInfo['father']);
		//print_data($moduleInfo,true);
		$this->assign('moduleInfo',$moduleInfo);

		$newContext = $news->getContext($id,$fatherId);
		$this->assign('newContext',$newContext);
		
		//print_data($newContext,true);
		return $this->fetch('news');
	}
	public function getSignList($id)
	{
		//使用view 方法
		$result = Db::view('sign',['is_sign'=>'is_sign','sign_ip'=>'sign_ip','sign_time'=>'sign_time','sign_person'=>'userid'])
		->view('role',['name'=>'rolename','id'=>'roleid'],'role.id=sign.sign_role')
		//->view('user',['name'=>'username','id'=>'userid'],'user.id=sign.sign_person')
		->where('news_id',$id)
		->order('sign_time is_sign desc')
		->select();
		//print_data($result);
		
		foreach ($result as $key => $value) {
			$result[$key]['sign_time'] = date('Y-m-d',$value['sign_time']);
			//$data[$key]['status'] = $value['status'] == 1 ? lang('Start') : lang('Off');
			if($result[$key]['userid'] > 0)
			{
				$tempName = Db::view('user',['name'=>'username','id'=>'userid'])->where('id',$result[$key]['userid'])->select();
				$result[$key]['username'] = $tempName[0]['username'];
			}
		}
		
		//dump($result);
		return $result;
	}
	
	public function sign()//$id,$userID,$roleID
	{
		
		$request = Request::instance();
		$signIP = $request->ip();
		$signTime = time();
		$postData = input('post.');
		//print_data($postData);
		if (empty($postData))
		{
			return null;
		}
		//step1  验证当前userid 是在roleid 部门中
		//$UserRole = Loader::model('UserRole');
		//$res = $UserRole->CheckUser($postData['userID'],$postData['roleID']);
		//$res = $UserRole->CheckUser(2,3);
		//print_data($res);
		//if(empty($res))
		//{
			//return  json("用户不属于该部门,无法签收");;
		//}
		//step1  验证当前userid 是否可以签收当前部门
		if( Session::has(md5($postData['userID']), 'index') )//此处uid为用户id
		{
			$ReturnData = Session::get(md5($postData['userID']), 'index') ;
			if($ReturnData['role_id'] != $postData['roleID'])
			{
				return  json("用户不属于该部门,无法签收");
			}
			//print_data($ReturnData);
			//return $ReturnData;
		}
		else
		{
			return  json("请先登录");
		}
		//step2 签收
		$sign = Loader::model('Sign');
		//$result = $sign->sign($id,$userID,$roleID);
		$result = $sign->sign($postData['id'],$postData['roleID'],$postData['userID'],$signIP,$signTime);
		if(is_int($result))
		{
			if($result == -1)
			{
				$re = json("不在签收范围");
			}
			elseif ($result == 0)
			{
				$re = json("执行签收");
			}
		}
		else 
		{
			$re = $result;
		}
		//dump($re);
		json($re);
		
		return ($re);;
	}
	
	
}