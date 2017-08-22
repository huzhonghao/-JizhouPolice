<?php
namespace app\admin\controller;

use think\Loader;


/**
* 用户管理
* @author aierui github  https://github.com/Aierui
* @version 1.0 
*/
class User extends Admin
{

    function _initialize()
    {
        parent::_initialize();
    }

    /**
     * 列表
     * 输入 rid 当前rule id
     */
    public function index()
    {
    	//$role_id = $this->role_id;
    	//print_data($rid);
    	//获得导航栏信息
    	//$result = $this->getNavInfo($role_id);
    	$result = $this->accessRule;
    	$this->assign('moduleInfo',$result);
    	//print_data($this->uid);
    	$uid = $this->uid;
    	$this->assign('uid',$uid);
    	$request = request()->param();
    	//$result2 = $this->findSonFromTree($result,$rid);
    	$this->assign('scope',$request['scope']);
    	$this->assign('param',$request['param']);
    	//print_data($result2);
    	//print_data('11111111');
    	
        return view();
    }
    /*
	public function clientshow()
	{
		$map['type']=0;
		$data = model('User')->getList( $map );
		//print_data($data);
		//json(['data'=>$data,'code'=>1,'message'=>'操作完成'])
		//return json(['data'=>$data,'code'=>1,'message'=>'操作完成']);
		return $data;
	}
	*/

    /**
     * 异步获取列表数据
     *
     * @author chengbin
     * @return mixed
     */
    public function getList()
    {
        if(!request()->isAjax()) {
            $this->error(lang('Request type error'), 4001);
        }

        $request = request()->param();
    	//print_data($request);
    	$map['type']=$request['type'];
    	$data = model('User')->getList( $map );
        return $data;
    }

    /**
     * 添加
     */
    public function add()
    {
    	if(!request()->isAjax()) {
    		$this->error(lang('Request type error'), 4001);
    	}
    	 
    	$request = request()->param();
    	//print_data($request);
    	$type = $request['type'];
        $roleData = model('role')->getKvData($type);
        $this->assign('type', $type);
        $this->assign('roleData', $roleData);
        $this->assign('uid',$request['uid']);
        return $this->fetch('edit');
    }

    /**
     * 编辑
     * @param  string $id 数据ID（主键）
     */
    public function edit()
    {   
    	//print_data($id);
    	if(!request()->isAjax()) {
    		$this->error(lang('Request type error'), 4001);
    	}
    	
    	$request = request()->param();
    	//print_data($request);
    	$id = $request['id'];
    	$type = $request['type'];
        if(intval($id) < 0){
            return info(lang('Data ID exception'), 0);
        }
        
        $roleData = model('role')->getKvData($type);
        $this->assign('roleData', $roleData);
        $this->assign('type', $type);
        //print_data($roleData);
        $data = model('User')->get(['id'=>$id]);
        //print_data($data);
        $this->assign('data',$data);
        $this->assign('uid',$request['uid']);
        return $this->fetch();
    }

    /**
     * 保存数据
     * @param array $data
     *
     * @author chengbin
     */
    public function saveData()
    {
    	$request = request()->param();
        $this->mustCheckRule( $request['uid'],'admin/user/edit' );
        if(!request()->isAjax()) {
            return info(lang('Request type error'));
        }

        $data = input('post.');
        //$data['password'] = mduser( $data['password']);
        //$data['password2'] = mduser( $data['password2']);
        //var_dump($data);die;
        
        
        //print_data($data);
        
        return model('User')->saveData( $data );
    }

    /**
     * 删除
     * @param  string $id 数据ID（主键）
     */
    public function delete($id = 0){
    	if(!request()->isAjax()) {
    		$this->error(lang('Request type error'), 4001);
    	}
    	 
    	$request = request()->param();
    	//print_data($request);
    	
        if(empty($id)){
            return info(lang('Data ID exception'), 0);
        }
        
        return Loader::model('User')->deleteById($request['id']);
    }
    
    
   
}