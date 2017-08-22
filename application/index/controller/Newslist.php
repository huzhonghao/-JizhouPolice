<?php
namespace app\index\controller;
use think\Controller;
use think\Loader;
use think\Session;
use think\Request;
use think\Url;
use app\common\tools;
use think\Db;


class Newslist extends Controller
{

	public function index($id,$sonid)
	{
		
		//test 
		//print_data($sonmoduleInfo,true);
		$Module = Loader::model('Module');
		$sonModule = Loader::model('SonModule');
		//step0  将所有模块。字模块name id 发送给前端
		$ModuleData = $Module->getAllModel();
		$SonModuleData = $sonModule->getallSonModuel();
		//print_data($ModuleData);
		$wholeModuleInfo = array();
		
		foreach ($ModuleData as $key => $value) {
			$temp['id'] = $ModuleData[$key]['id'];
			$temp['name'] = $ModuleData[$key]['name'];
			$temp2 = array();
			foreach ($SonModuleData as $key2 => $value2)
			{
				$temp3 = NULL;
				if($SonModuleData[$key2]['father'] == $temp['id'])
				{
					$temp3['id'] = $SonModuleData[$key2]['id'];
					$temp3['name'] = $value2['name'];
					$temp3['father'] = $value2['father'];
					array_push($temp2, $temp3);
				}
			}
			$temp['son'] = array();
			$temp['son'] = $temp2;
			array_push($wholeModuleInfo, $temp);
		}
		//print_data($wholeModuleInfo);	
		$this->assign('wholeModuleInfo',$wholeModuleInfo);
		
		//step1  根据$id找到当前父模块的名字，显示
		//$moduleInfo = $Module->getModudelInfo($id);
		//print_data($moduleInfo,true);
		foreach ($wholeModuleInfo as $key => $value)
		{
			if ($wholeModuleInfo[$key]['id'] == $id)
			{
				$moduleInfo['id'] = $value['id'];
				$moduleInfo['name'] = $value['name'];
				$sonmoduleInfo = $value['son'];
			}
		}
		
		//print_data($moduleInfo);
		$this->assign('moduleInfo',$moduleInfo);
		
		//step2 根据$id找到所属子模块名字，列表显示
		
		//$sonmoduleInfo = $sonModule->getsonModudelList($id);
		//print_data($sonmoduleInfo,true);
		$this->assign('sonmoduleInfo',$sonmoduleInfo);
		
		
		//step3 get sonmoudel name 
		$sonModuleName = null;
		foreach ($sonmoduleInfo as $key => $value) {
			if($sonmoduleInfo[$key]['id'] == $sonid)
			{
				$sonModuleName['name'] = $sonmoduleInfo[$key]['name'];
				$sonModuleName['id'] = $sonid;
			}
		}
		//print_data($sonModuleName,true);
		$this->assign('sonmoduleName',$sonModuleName);
		
		//step3 根据当前sonid 找到子模块下的新闻列表，然后分页显示
		// 查询状态为1的用户数据 并且每页显示10条数据
		$News = Loader::model('News');
		$list = $News->getNewsByPage($sonid);
		// 获取分页显示
		$page = $list->render();
		// 模板变量赋值
		$this->assign('list', $list);
		$this->assign('page', $page);
		// 渲染模板输出
		//return $this->fetch();
		
		return $this->fetch('list_wz');
	}
	public function _empty()
	{
		$this->redirect(url('index/index/index'));
		return null;
	}
}