<?php
namespace app\index\model;

use app\common\model\basemodel;
use think\Db;
use think\Model;

/**
 * 前端Sign model类
 *
 * Class news
 * @package app\index\model
 */

class Sign extends basemodel
{
	public function index()
	{

	}
	//格式化数据
	private function _fmtTime( $data )
	{
		if(empty($data) && is_array($data)) {
			return $data;
		}
	
		foreach ($data as $key => $value) {
			$data[$key]['sign_time'] = date('Y-m-d',$value['sign_time']);
			//$data[$key]['status'] = $value['status'] == 1 ? lang('Start') : lang('Off');
		}
	
		return $data;
	}
	//当前单位是否签收当前文件
	private function IsSign($articleID,$userRoleID)
	{
		$data = $this->where('news_id',76)
		->where('sign_role',1)->select();
		$this->_fmtTime($data);
		if($data == null)
		{
			return -1;
		}
		$detail = $data[0];
		if($detail['is_sign'])
		{
			return $detail;//已经签收，返回签收信息
		}
		else 
		{
			return 0;//未签收
		}
	}
	//签收 
	//input articleID : 文章ID， userRoleID 用户所在单位ID， userID 用户ID 签收IP  签收时间，
	public function Sign($articleID,$userRoleID,$userID,$signIP,$signTime)
	{
		
		//step2 查看当前文件是否被当前单位签收
		$signInfo = $this->IsSign($articleID,$userRoleID);
		//step3 签收
		if(is_int($signInfo))
		{
			if($signInfo == -1)
			{
				return -1;///不需要签收
			}
			elseif ($signInfo == 0) 
			{
				$this->where('news_id', $articleID)
				->where('sign_role',$userRoleID)->update(['sign_person'=>$userID,'is_sign' => 1,'sign_ip'=>$signIP,'sign_time'=>$signTime]);
				return 0;
			}
		}
		else 
		{
			return $signInfo;
		}
	}
	
	//获得签收列表
	public function getSignList($articleID)
	{
		$data = $this->where('news_id',$articleID)->select();
		_fmtTime($data);
		$detail = $data[0];
		return $detail;
	}
	
}