<?php
namespace app\admin\controller;

use think\Session;
use think\Request;
use think\Loader;
use think\Db;

/**
* 角色管理
* @author aierui github  https://github.com/Aierui
* @version 1.0 
*/
class Role extends Admin
{
    private $role;
    function _initialize()
    {
        parent::_initialize();
        $this->role = Loader::model('role');
    }

    /**
     * 列表
     */
    public function index()
    {
    	$result = $this->accessRule;
    	$this->assign('moduleInfo',$result);
    	//print_data($result);
    	$uid = $this->uid;
    	$this->assign('uid',$uid);
    	$request = request()->param();
    	$this->assign('scope',$request['scope']);
    	$this->assign('param',$request['param']);
        return view();
    }

    public function getList()
    {
        if(!request()->isAjax()) {
            $this->error(lang('Request type error'), 4001);
        }

        $request = request()->param();
        //print_data($request);
        $map['type']=$request['type'];
        $data = model('Role')->getList( $map );
        
        //print_data($request);
        return $data;
    }

    public function add()
    {
    	$request = request()->param();
    	//print_data($request);
    	$this->assign('uid',$request['uid']);
        return $this->fetch('edit');
    }


    public function edit($id = 0)
    {
    	$request = request()->param();
    	//print_data($request);
    	$this->assign('uid',$request['uid']);
        $id = $request['id'];
        $data = model('role')->get(['id'=>$id]);
        //print_data($data);
        $this->assign('data', $data);
        return $this->fetch();
        //$a = $_SERVER['SERVER_SOFTWARE'];
    }

    public function saveData()
    {
        if( !request()->isAjax() ) {
            $this->error(lang('Request type error'));
        }
        $data = input('post.');
        return model('role')->saveData( $data );
    }

    /**
     * 删除
     * @param  string $id 数据ID（主键）
     */
    public function delete($id = 0){
        if(empty($id)){
            return info(lang('Data ID exception'), 0);
        }
        model('User')->deleteByRoleID($id);
        return model('Role')->deleteById($id);
    }
}