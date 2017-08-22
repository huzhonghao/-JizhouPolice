<?php
namespace app\admin\model;

use think\Config;
use think\Db;
use think\Loader;
use think\Model;
use traits\model\SoftDelete;

class User extends Admin
{
	//use SoftDelete;
    //protected $deleteTime = 'delete_time';
    //protected $create_time = 'register_time';
	protected $createTime = 'register_time';
	//protected $updateTime = 'last_login_time';
	
	/**
	 *  用户登录
	 */
	public function login(array $data)
	{
		$code = 1;
		$msg = '';
		$userValidate = validate('User');
		if(!$userValidate->scene('login')->check($data)) {
			return info(lang($userValidate->getError()), 4001);
		}
		if( $code != 1 ) {
			return info($msg, $code);
		}
		$map = [
			'user_no' => $data['user_no'],
				'type' => 1
		];
		$userRow = $this->where($map)->find();
		if( empty($userRow) ) {
			return info(lang('You entered the account or password is incorrect, please again'), 5001);
		}
		$md_password = mduser( $data['password'] );
		if( $userRow['password'] != $md_password ) {
			return info(lang('You entered the account or password is incorrect, please again'), 5001);
		}
		return info(lang('Login succeed'), $code, '', $userRow);
	}


	public function getList( $request )
	{
		
		//$request = $this->fmtRequest( $request );
		//print_data($request);
		//$data = $this->order('register_time desc')->where( $request['map'] )->limit($request['offset'], $request['limit'])->select();
		//$data = $this->order('register_time desc')->where( $request )->field('id,user_no,name,register_time,phone,email,status,type,role_id')->select();
		//print_data($request);
		
		$data = Db::view('user','id,user_no,name,register_time,phone,email,status,type,role_id')
		->view('role',['name'=>'role'],'role.id=user.role_id')
		->where($request)
		->order('register_time desc')
		->select();
		//print_data($data);
		return $this->_fmtData( $data );
	}

	public function saveData( $data )
	{
		if( isset( $data['id']) && !empty($data['id'])) {
			$result = $this->edit( $data );
		} else {
			$result = $this->add( $data );
		}
		//print_data($result);
		return $result;
	}


	public function add(array $data = [])
	{
		$userValidate = validate('User');
		if(!$userValidate->scene('add')->check($data)) {
			return info(lang($userValidate->getError()), 4001);
		}
		//$user = User::get(['phone' => $data['phone']]);
		$moblie = $this->where(['phone'=>$data['phone']])->where('id', '<>', $data['id'])->where('type',$data['type'])->value('phone');
		if (!empty($moblie)) {
			return info(lang('Mobile already exists'), 0);
		}
		$ruler = $this->where(['user_no'=>$data['user_no']])->where('type',$data['type'])->value('id');
		if (!empty($ruler)) {
			return info(lang('user_no under this type already exists'), 0);
		}
		if($data['password2'] != $data['password']){
            return info(lang('The password is not the same twice'), 0);
        }
		unset($data['password2']);
		unset($data['id']);
		$data['password'] = mduser($data['password']);
		$data['register_time'] = time();
		//print_data($data);
		$this->allowField(true)->save($data);
		if($this->id > 0){
            return info(lang('Add succeed'), 1, '', $this->id);
        }else{
            return info(lang('Add failed') ,0);
        }
	}

	public function edit(array $data = [])
	{
		//print_data($data);
		$userValidate = validate('User');
		if(!$userValidate->scene('edit')->check($data)) {
			return info(lang($userValidate->getError()), 4001);
		}
		//print_data($data);
		
		$moblie = $this->where(['phone'=>$data['phone']])->where('id', '<>', $data['id'])->where('type',$data['type'])->value('phone');
		if (!empty($moblie)) {
			return info(lang('Mobile already exists'), 0);
		}
		
		$ruler = $this->where(['user_no'=>$data['user_no']])->where('id', '<>', $data['id'])->where('type',$data['type'])->value('id');
		if (!empty($ruler)) {
			return info(lang('user_no under this type already exists'), 0);
		}
		
		if($data['password2'] != $data['password']){
            return info(lang('The two passwords No match!'),0);
        }
        if (empty($data['password']))
        {
        	unset($data['password']);
        }
        else 
        {
        	$data['password'] = mduser($data['password']);
        }
        unset($data['password2']);
        $data['update_time'] = time();
		//print_data($data);
		$res = $this->allowField(true)->save($data,['id'=>$data['id']]);
		if($res == 1){
            return info(lang('Edit succeed'), 1);
        }else{
            return info(lang('Edit failed'), 0);
        }
	}

	public function deleteById($id)
	{
		$result = User::destroy($id);
		if ($result > 0) {
            return info(lang('Delete succeed'), 1);
        }   
	}
	public function deleteByRoleID($role_id)
	{
		$result = $this->where(['role_id'=>$role_id])->delete();
		if ($result > 0) {
			return info(lang('Delete succeed'), 1);
		}
	}

	//格式化数据
	private function _fmtData( $data )
	{
		if(empty($data) && is_array($data)) {
			return $data;
		}

		foreach ($data as $key => $value) {
			$data[$key]['register_time'] = date('Y-m-d H:i:s',$value['register_time']);
			$data[$key]['status'] = $value['status'] == 1 ? lang('Start') : lang('Off');
		}
    //print_data($data,true);
		return $data;
	}

}
