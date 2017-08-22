<?php
namespace app\common\model;


use think\Config;
use think\Controller;
use think\Lang;
use think\Model;

/**
 * 公用的控制器，pc、app、微信各端不需要控制权限的控制器，必须继承该控制器
 *
 * @author aierui github  https://github.com/Aierui
 * @version 1.0
 */
class basemodel extends Model
{
	/**
	 * 添加数据
	 * @param    array    $data    数据
	 * @return   integer           新增数据的id
	 */
	/*
	public function addData($data){
		$id=$this->add($data);
		return $id;
	}
	*/
	/**
	 * 修改数据
	 * @param    array    $map    where语句数组形式
	 * @param    array    $data   修改的数据
	 * @return    boolean         操作是否成功
	 */
	/*
	public function editData($map,$data){
		$result=$this->where($map)->save($data);
		return $result;
	}
	*/
	
	/**
	 * 删除数据
	 * @param    array    $map    where语句数组形式
	 * @return   boolean          操作是否成功
	 */
	/*
	public function deleteData($map){
		$result=$this->where($map)->delete();
		return $result;
	}
	*/

}