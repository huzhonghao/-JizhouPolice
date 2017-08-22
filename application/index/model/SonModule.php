<?php
namespace app\index\model;

use app\common\model\basemodel;
use think\Db;
use think\Model;

/**
 * 前端son module model类
 *
 * Class sonmodule
 * @package app\index\model
 */

class SonModule extends basemodel
{
	public function index()
	{
		
	}
	//获得当前父模块下的字模块列表
	public function getsonModudelList($id)
	{
		$data = $this->where('father',$id)->select();
		//print_data($data);
		//$moduleInfo = $data[0];
		
		//return name
		return $data;
	}
	
	public function getsonModudelInfo($id)
	{
		$data = $this->where('id',$id)->select();
		//print_data($data);
		$moduleInfo = $data[0];
	
		//return name
		return $moduleInfo;
	}
	
	public function getsonModuelNum()
	{
		$data = $this->where('type','=',0)->count();
		
		$moduleNum = $data[0];
		
		//return name
		return $moduleNum;
	}
	public function getallSonModuel()
	{
		$data = $this->where('type','=',0)->select();
		return $data;
	}
}