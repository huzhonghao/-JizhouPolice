<?php
namespace app\index\model;

use app\common\model\basemodel;
use think\Db;
use think\Model;
use think\Paginator;

/**
 * 前端news model类
 *
 * Class news
 * @package app\index\model
 */

class News extends basemodel
{
	//根据uid返回新闻详情
	public function getNewsInfo( $uid )
	{
		// get news 
		$data = $this->where('id',$uid)->select();
		$viewCount = $data[0]['view_count']+1;
		// news' read conunt ++
		$this->where('id', $uid)->update(['view_count' => $viewCount]);
		//return format news
		$newsContent = $this->_fmtNews($data)[0];
		return $newsContent;
	}
	
	public function getNewsByPage($fatherId)
	{
		$list = $this->where('father_module',$fatherId)->field('id,title,public_time,is_sign')->paginate(10);
		//$newsContent = $this->_fmtNews($list);
		//print_data($newsContent);
		return $this->_fmtNews($list);
		
	}
	// 根据输入的当前文章id 获得上一篇 和下一篇文章的 title 和 id
	public function getContext($uid, $fatherID)
	{
		if (is_numeric($uid))
		{
			//$uidF = $uid + 1;
			//$uidB = $uid - 1;
			$temp = $this->order('id desc')->where('father_module',$fatherID)
			->where('id', '<', $uid)
			->field('id,title')->limit(1)->select();
			if (!count($temp))
			{
				$data[0] = null;
			}
			else 
			{
				$data[0] = $temp[0];
			}
			$temp = $this->order('id asc')->where('father_module',$fatherID)
			->where('id', '>', $uid)
			->field('id,title')->limit(1)->select();
			//print_data($temp,true);
			if (!count($temp))
			{
				$data[1] = null;
			}
			else
			{
				$data[1] = $temp[0];
			}
		}
		else 
		{
			
		}
		return $data;
	}
	
	//根据新闻模块ID返回新闻列表
	public function getNewslistInfo( $uid, $limitNum )
	{
		//$data = $this->where('father_module',$uid)->column('title','public_time','id');
		$data = $this->order('public_time desc')->where('father_module',$uid)->field('id,title,public_time')->limit($limitNum)->select(); 
		return $this->_fmtNewsList($data);
	}
	
	//加载首页，返回所有模块的前7条新闻题目别表
	public function getWholeNewList()
	{
		$data = $this->order('public_time desc')->where(1)->field('id,title,public_time,father_module')->select();
		return $this->_fmtNewsList($data);
	}
	//format the news list
	//格式化数据
	private function _fmtNewsList( $data )
	{
		if(empty($data) && is_array($data)) {
			return $data;
		}
	
		foreach ($data as $key => $value) {
			$data[$key]['public_time'] = date('m-d',$value['public_time']);
			//$data[$key]['status'] = $value['status'] == 1 ? lang('Start') : lang('Off');
		}
	
		return $data;
	}
	
	//format the news 
	//格式化数据
	private function _fmtNews( $data )
	{
		if(empty($data) && is_array($data)) {
			return $data;
		}
	
		foreach ($data as $key => $value) {
			$data[$key]['public_time'] = date('Y-m-d',$value['public_time']);
			//$data[$key]['status'] = $value['status'] == 1 ? lang('Start') : lang('Off');
		}
	
		return $data;
	}
	
}