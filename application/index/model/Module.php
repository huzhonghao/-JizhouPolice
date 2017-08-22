<?php
namespace app\index\model;

use app\common\model\basemodel;
use think\Db;
use think\Model;

/**
 * 前端top module model类
 *
 * Class module
 * @package app\index\model
 */

class Module extends basemodel
{
	public function index()
	{
	
	}
	
	public function getAllModel()
	{
		$data = $this->where('type','=',0)->select();
		return $data;
	}
	//根据输入的模块id获得模块名称
	public function getModudelInfo($id)
	{
		$data = $this->where('id',$id)->select();
		$moduleInfo = $data[0];
		
		//return name
		return $moduleInfo;
	}
}