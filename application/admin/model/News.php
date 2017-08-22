<?php
namespace app\admin\model;

use \think\Config;
use think\Db;
use \think\Model;
use \think\Session;


/**
 * 角色权限
 *
 * @author chengbin
 */
class News extends Admin
{
	public function index()
	{

	}
	public function getNewsByPage($fatherId)
	{
		//$list = $this->where('father_module',$fatherId)->field('id,title,public_time,public_person,is_sign,is_pic_news,pic_path')->paginate($paginate);
		//$newsContent = $this->_fmtNews($list);
		//print_data($newsContent);
		//$list = $this->where('father_module',$fatherId)->column('id,title,public_time,public_person,is_sign,is_pic_news,pic_path');
		$data = $this->order('public_time desc')->where( 'father_module',$fatherId )->field('id,title,public_time,public_person,is_sign,is_pic_news,pic_path')->select();
		return $this->_fmtNews($data);
	
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
	public function deleteById($id)
	{
		$result = News::destroy($id);
		if ($result > 0) {
			return info('Delete succeed', 1);
		}
	}
}