<?php
namespace app\admin\model;

use think\Config;
use think\Db;
use think\Loader;
use think\Model;

class UserRole extends Admin
{
	public function getRoleIDbyUserID($uid)
	{
		//print_data($uid);
		return $this->where('user_id',$uid)->column('role_id');
	}
}