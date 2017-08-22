<?php
namespace app\admin\controller;

use app\common\controller\Common;
use think\Controller;
use think\Loader;
use think\Session;
use think\Request;
use think\Url;
use app\common\tools;
use think\Db;


/**
* 后台controller基础类
* @author aierui github  https://github.com/Aierui
 *
* @version 1.0 
*/
class Admin extends Common
{
	protected $uid = 0;
	protected $role_id = 0;
	protected  $accessRule = [];
	protected $fatherRule = NULL;

	function _initialize()
	{
		parent::_initialize();
		//判断是否已经登录
		$request = Request::instance();
		$requestData = $request->param();
		//print_data($requestData);
		if(!isset($requestData['uid']))
		{
			$requestData['uid'] = 0;
		}
		else 
		{
			//$uid = $requestData['uid'];
		}
		if( !Session::has(md5($requestData['uid']), 'admin') ) {
			$this->error('Please login first', url('admin/Login/index'));
		}
		$userRow = Session::get(md5($requestData['uid']), 'admin');
		//print_data($userRow);
		//验证权限
		
		$rule_val = $request->module().'/'.$request->controller().'/'.$request->action();
		//print_data($rule_val);
		$rule_val = strtolower($rule_val);// 变成小写
		$this->uid = $userRow['id'];
		
		//need to get role-id
		$this->role_id = $userRow['role_id'];
		
		
		//if($userRow['administrator']!=1 && !$this->checkRule($this->uid, $rule_val)) {
		if(!$this->checkRule($this->role_id, $rule_val)) {
			$this->error(lang('Without the permissions page'));
		}
		//print_data($this->role_id);
		//$this->$fatherRule = 
		$this->accessRule = $this->getNavInfo($this->role_id);
		
	}

	public function goLogin()
	{
		Session::clear();
		$this->redirect( url('admin/login/') );
	}

	public function checkRule($rid, $rule_val)
	{
		$authRule = Loader::model('AuthRule');
		if(!$authRule->isCheck($rule_val)) {
			return true;
		}
		$authAccess = Loader::model('AuthAccess');
		//print_data($rule_val);
		if(in_array($rule_val, $authAccess->getRuleVals($rid))){
			//$this->setAccessRule($rid);
			return true;
		}
		return false;
	}

	//执行该动作必须验证权限，否则抛出异常
	public function mustCheckRule( $uid,$rule_val = '' )
	{
		$userRow = Session::get(md5($uid), 'admin');
		
		if( empty($rule_val) ) {
			$request = Request::instance();
			$rule_val = $request->module().'/'.$request->controller().'/'.$request->action();
		}

		if(!model('AuthRule')->isCheck($rule_val)) {
			$this->error(lang('This action must be rule'));
		}
	}
	protected function setAccessRule($rid)
	{
		$authAccess = Loader::model('AuthAccess');
		$tempdata = $authAccess->getRuleByRid($rid);
		//print_data($tempdata);
		//$accessRule = array();
		if(empty($tempdata))
		{
			return null;
		}
		
		//step1 find root level  控制器
		$controlerInfo = [];
		foreach ($tempdata as $key => $value) 
		{
			if($tempdata[$key]['level'] == 1)
			{
				$temp = [];
				$temp['id']  = $tempdata[$key]['id'];
				$temp['title']  = $tempdata[$key]['title'];
				$temp['pid']  = $tempdata[$key]['pid'];
				$temp['level']  = $tempdata[$key]['level'];
				$temp['rule_val']  = $tempdata[$key]['rule_val'];
				$temp['son'] = array();
				array_push($controlerInfo, $temp);
			}
		}
		//print_data($accessRule);
		//step2 find Moduel
		$Moduletemp = []; // 模块
		$res = $this->getModuleInfoByRoleId($rid);
		foreach ($tempdata as $key => $value)
		{
			if($tempdata[$key]['level'] == 2)
			{
				$temp = [];
				$temp['id']  = $tempdata[$key]['id'];
				$temp['title']  = $tempdata[$key]['title'];
				$temp['pid']  = $tempdata[$key]['pid'];
				$temp['level']  = $tempdata[$key]['level'];
				$temp['rule_val']  = $tempdata[$key]['rule_val'];
				$temp['scope']=[];
				//如果是文章管理，需要找到当前用户管理的文章模块
				if($value['id'] == 8) //文章管理
				{
					//find 相应roleid下有修改权限的文章模块
					
					$temp['scope'] = $res;
					//print_data($res);
				}
				else 
				{
					//$res = $this->getModuleInfoByRoleId($rid);
					foreach ($res as $key2 => $value2)
					{
						if($temp['title'] == $value2['moduleName'])// 这里要求模块名字和控制权限的title相同
						{
							array_push($temp['scope'], $value2);
						}
					}
					
				}
				$temp['son'] = array();
				array_push($Moduletemp, $temp);
			}
		}
		
		
		
		//step4 find method
		$methodInfo = [];
		foreach ($tempdata as $key => $value)
		{
			foreach ($Moduletemp as $key1 => $value1)
			{
				if($value['pid'] == $value1['id'])
				{
					$temp = [];
					$temp['id']  = $tempdata[$key]['id'];
					$temp['title']  = $tempdata[$key]['title'];
					$temp['pid']  = $tempdata[$key]['pid'];
					$temp['level']  = $tempdata[$key]['level'];
					$temp['rule_val']  = $tempdata[$key]['rule_val'];
					array_push($Moduletemp[$key1]['son'], $temp);
				}
			}
		}
		
		//array_push($controlerInfo[0]['son'], $Moduletemp);
		$controlerInfo[0]['son']= $Moduletemp;
		//array_push($accessRule, $controlerInfo);
		$this->accessRule = $Moduletemp;
		//print_data($this->accessRule);
		return NULL;
	}
	
	public function getAccessRole()
	{
		//print_data($this->accessRule);
		//return $this->accessRule;
	}
	
	
	//获取导航栏信息
	protected function getNavInfo($rid)
	{
		//使用view 方法
		//$result = Db::view('sign',['is_sign'=>'is_sign','sign_ip'=>'sign_ip','sign_time'=>'sign_time','sign_person'=>'userid'])
		//->view('role',['name'=>'rolename','id'=>'roleid'],'role.id=sign.sign_role')
		//->view('user',['name'=>'username','id'=>'userid'],'user.id=sign.sign_person')
		//->where('news_id',$id)
		//->order('sign_time is_sign desc')
		//->select();
		/*
			$result = Db::view('auth_access',['role_id'=>'role_id'])
			->view('auth_rule',['title'=>'title','rule_val'=>'rule'],'auth_rule.id=auth_access.rule_id and auth_rule.level=2')
			->view('module_role',['module_id'=>'son_module_id'],'module_role.role_id=auth_access.role_id')
			->view('son_module',['id'=>'son_module_id','name'=>'sonname'],'son_module.id=module_role.module_id')
			->view('module',['name'=>'modulename'],'module.id=son_module.father')
			->where('role_id',$rid)
			->order('modulename desc')
			->select();
			*/
		$result = Db::view('auth_access',['role_id'=>'role_id'])
		->view('auth_rule',['id'=>'id','title'=>'title','rule_val'=>'rule','pid'=>'pid','level'=>'level','is_scope'=>'scope','param'=>'param'],'auth_rule.id=auth_access.rule_id')//and auth_rule.level=2
		//->view('module_role',['module_id'=>'son_module_id'],'module_role.role_id=auth_access.role_id')
		//->view('son_module',['id'=>'son_module_id','name'=>'sonname'],'son_module.id=module_role.module_id')
		//->view('module',['name'=>'modulename'],'module.id=son_module.father')
		->where('role_id',$rid)
		//->order('auth_rule.id asc')
		->order(['auth_rule.level'=>'asc','auth_rule.id'=>'asc'])
		->select();
		//print_data($result);
		if (empty($result))
		{
			return info('the user has no responsable access',1);
		}
		$treeRes = $this->arr2tree($result,0);
		
		//print_data($treeRes);
		return $treeRes;
		
		/*
		$result2 = Db::view('module_role',['role_id'=>'role_id'])
		->view('son_module',['id'=>'son_id','name'=>'sonname'],'module_role.module_id=son_module.id')
		->view('module',['id'=>'id','name'=>'name'],'module.id=son_module.father')
		->where('role_id', $rid)
		->order('module.id')
		->select();
	
		$result3 = [];
		//print_data($result2);
		foreach ($result as $key=>$value)
		{
			//$scopeInfo = [];
			$result[$key]['scope'] = [];
			if($value['is_scope'] == 1)//需要查找权限内的模块 只有文章模块有这种问题
			{
	
				$module['id'] = $result2[0]['id'];
				$module['name'] = $result2[0]['name'];
				$module['myurl'] = '';
				$module['son'] = [];
				//print_data($module);
				//array_push($scopeInfo, $module);
				foreach ($result2 as $key2 => $value2)
				{
					if($value2['id'] == $module['id'])
					{
						$sonmodule['id'] = $value2['son_id'];
						$sonmodule['name'] = $value2['sonname'];
						$sonmodule['myurl'] = $value['rule'];
						array_push($module['son'], $sonmodule);
					}
					else
					{
						//print_data($module);
						//$scopeInfo = $module;
						array_push($result3,$module);
						$module['id'] = $value2['id'];
						$module['name'] = $value2['name'];
						$module['myurl'] = '';
						$module['son'] = [];
						$sonmodule['id'] = $value2['son_id'];
						$sonmodule['name'] = $value2['sonname'];
						$sonmodule['myurl'] = $value['rule'];
						array_push($module['son'], $sonmodule);
					}
				}
				//$scopeInfo = $module;
				array_push($result3,$module);
			}
			else
			{
				$module = [];
				$module['id'] = 0;
				$module['name'] = $value['title'];
				$module['myurl'] = $value['rule'];
				$module['son'] = [];
				array_push($result3,$module);
			}
			//print_data($scopeInfo);
				
			//$result[$key]['scope'] = $scopeInfo;
		}
	
		//print_data($result3);
		return $result3;
		*/
	}
	/*
	protected function checkParam()
	{
		$role_id = $this->role_id;
		
	}
	*/
	// 将数据按照所属关系封装 见图2
	protected function arr2tree($tree, $rootId = 0) 
	{  
	    $return = array();  
	    foreach($tree as $leaf) {  
	        if($leaf['pid'] == $rootId) {  
	            foreach($tree as $subleaf) {  
	                if($subleaf['pid'] == $leaf['id']) {  
	                    $leaf['son'] = $this->arr2tree($tree, $leaf['id']);
	                    break;  
	                }
	                else 
	                {
	                	$leaf['son'] = [];
	                }
	            }  
	            $return[] = $leaf;  
	        }  
	    }  
	    return $return;  
	} 
	
	/**
	 * 查找level为3的子模块
	 *
	 * @param  $rule 当前role下拥有的权限树 leve from 2 -> 4
	 * 			$rid -> rule_id
	 */
	protected function findSonFromTree($rule, $rid)
	{
		if (empty($rule))
		{
			return NULL;
		}
		$level = 3;
		$status = [];
		foreach ($rule as $key=>$value)
		{
			if($value['level']<$level)
			{
				$status = $this->findSonFromTree($value['son'], $rid);
			}
			else
			{
				if($value['id'] == $rid)
				{
					$status = $value['son'];
					break;
				}
				else
				{
					continue;
				}
	
			}
			if(!empty($status))
			{
				break;
			}
		}
		return $status;
	}
   

}

