<?php
namespace app\index\controller;
use think\Controller;
use think\Loader;
use think\Session;
use think\Request;
use think\Url;
use app\common\tools;



class Index extends Controller
{
    public function index()// 太弱智了 需要改！！！！！！！！！！！！
    {
    	
    	
    	$Module = Loader::model('Module');
    	$sonModule = Loader::model('SonModule');
    	//step0  将所有模块。字模块name id 发送给前端
    	$ModuleData = $Module->getAllModel();
    	$SonModuleData = $sonModule->getallSonModuel();
    	//print_data($ModuleData);
    	$wholeModuleInfo = array();
    	
    	foreach ($ModuleData as $key => $value) {
    		$temp['id'] = $ModuleData[$key]['id'];
    		$temp['name'] = $ModuleData[$key]['name'];
    		$temp2 = array();
    		foreach ($SonModuleData as $key2 => $value2)
    		{
    			$temp3 = NULL;
    			if($SonModuleData[$key2]['father'] == $temp['id'])
    			{
    				$temp3['id'] = $SonModuleData[$key2]['id'];
    				$temp3['name'] = $value2['name'];
    				$temp3['father'] = $value2['father'];
    				array_push($temp2, $temp3);
    			}
    		}
    		$temp['son'] = array();
    		$temp['son'] = $temp2;
    		array_push($wholeModuleInfo, $temp);
    	}
    	//print_data($wholeModuleInfo);
    	$this->assign('wholeModuleInfo',$wholeModuleInfo);
    	
    	//is login 
    	//$loginFuc = Loader::controller('Login');
    	//$loginData =$loginFuc->status(1001); 
    	//$this->assign('loginInfo',$loginData);
    	//print_data($loginData);
    	
        //return '<style type="text/css">*{ padding: 0; margin: 0; } .think_default_text{ padding: 4px 48px;} a{color:#2E5CD5;cursor: pointer;text-decoration: none} a:hover{text-decoration:underline; } body{ background: #fff; font-family: "Century Gothic","Microsoft yahei"; color: #333;font-size:18px} h1{ font-size: 100px; font-weight: normal; margin-bottom: 12px; } p{ line-height: 1.6em; font-size: 42px }</style><div style="padding: 24px 48px;"> <h1>:)</h1><p> ThinkPHP V5<br/><span style="font-size:30px">十年磨一剑 - 为API开发设计的高性能框架</span></p><span style="font-size:22px;">[ V5.0 版本由 <a href="http://www.qiniu.com" target="qiniu">七牛云</a> 独家赞助发布 ]</span></div><script type="text/javascript" src="http://tajs.qq.com/stats?sId=9347272" charset="UTF-8"></script><script type="text/javascript" src="http://ad.topthink.com/Public/static/client.js"></script><thinkad id="ad_bd568ce7058a1091"></thinkad>';
    	// step1   get information from the database.
    	$newslist = Loader::model('News');
    	
    	
    	//$newslistValue = $newslist->getNewslistInfo(2,7);
    	$wholeList = $newslist->getWholeNewList();
    	
    	//print_data($wholeList,true);
    	
    	$icount = 0;
    	$newslistValue = array();
    	foreach ($wholeList as $key => $value) {
    		if($wholeList[$key]['father_module'] == 2)
    		{
    			array_push($newslistValue,$wholeList[$key]);
    			$icount++;
    		}
    		if ($icount > 7)
    		{
    			break;
    		}
    	}
    	$this->assign('tonggaotongzhi',$newslistValue);
    	
    	$newslistValue = array();
    	$icount = 0 ;
    	foreach ($wholeList as $key => $value) {
    		if($wholeList[$key]['father_module'] == 3)
    		{
    			array_push($newslistValue,$wholeList[$key]);
    			$icount++;
    		}
    		if ($icount > 7)
    		{
    			break;
    		}
    	}
    	//$this->assign('tebieguanzhu',$newslistValue);
    	
    	
    	$newslistValue = array();
    	$icount = 0 ;
    	foreach ($wholeList as $key => $value) {
    		if($wholeList[$key]['father_module'] == 4)
    		{
    			array_push($newslistValue,$wholeList[$key]);
    			$icount++;
    		}
    		if ($icount > 7)
    		{
    			break;
    		}
    	}
    	$this->assign('lingdaojianghu',$newslistValue);
    	
    	$newslistValue = array();
    	$icount = 0 ;
    	foreach ($wholeList as $key => $value) {
    		if($wholeList[$key]['father_module'] == 5)
    		{
    			array_push($newslistValue,$wholeList[$key]);
    			$icount++;
    		}
    		if ($icount > 7)
    		{
    			break;
    		}
    	}
    	$this->assign('gonganxinxi',$newslistValue);
    	
    	//简报专刊
    	$newslistValue = array();
    	$icount = 0 ;
    	foreach ($wholeList as $key => $value) {
    		if($wholeList[$key]['father_module'] == 6)
    		{
    			array_push($newslistValue,$wholeList[$key]);
    			$icount++;
    		}
    		if ($icount > 7)
    		{
    			break;
    		}
    	}
    	$this->assign('jianbaozhuankan',$newslistValue);
    	
    	//执法晾晒
    	$newslistValue = array();
    	$icount = 0 ;
    	foreach ($wholeList as $key => $value) {
    		if($wholeList[$key]['father_module'] == 7)
    		{
    			array_push($newslistValue,$wholeList[$key]);
    			$icount++;
    		}
    		if ($icount > 7)
    		{
    			break;
    		}
    	}
    	$this->assign('zhifaliangshai',$newslistValue);
    	
    	//信访晾晒
    	$newslistValue = array();
    	$icount = 0 ;
    	foreach ($wholeList as $key => $value) {
    		if($wholeList[$key]['father_module'] == 8)
    		{
    			array_push($newslistValue,$wholeList[$key]);
    			$icount++;
    		}
    		if ($icount > 7)
    		{
    			break;
    		}
    	}
    	$this->assign('xinfangliangshai',$newslistValue);
    	
    	//每日警情
    	$newslistValue = array();
    	$icount = 0 ;
    	foreach ($wholeList as $key => $value) {
    		if($wholeList[$key]['father_module'] == 9)
    		{
    			array_push($newslistValue,$wholeList[$key]);
    			$icount++;
    		}
    		if ($icount > 7)
    		{
    			break;
    		}
    	}
    	$this->assign('meirijingqing',$newslistValue);
    	
    	
    	//预警信息
    	$newslistValue = array();
    	$icount = 0 ;
    	foreach ($wholeList as $key => $value) {
    		if($wholeList[$key]['father_module'] == 10)
    		{
    			array_push($newslistValue,$wholeList[$key]);
    			$icount++;
    		}
    		if ($icount > 7)
    		{
    			break;
    		}
    	}
    	$this->assign('yujingxinxi',$newslistValue);
    	
    	//情报分析
    	$newslistValue = array();
    	$icount = 0 ;
    	foreach ($wholeList as $key => $value) {
    		if($wholeList[$key]['father_module'] == 11)
    		{
    			array_push($newslistValue,$wholeList[$key]);
    			$icount++;
    		}
    		if ($icount > 7)
    		{
    			break;
    		}
    	}
    	$this->assign('qingbaofenxi',$newslistValue);
    	
    	//机关动态
    	$newslistValue = array();
    	$icount = 0 ;
    	foreach ($wholeList as $key => $value) {
    		if($wholeList[$key]['father_module'] == 12)
    		{
    			array_push($newslistValue,$wholeList[$key]);
    			$icount++;
    		}
    		if ($icount > 7)
    		{
    			break;
    		}
    	}
    	$this->assign('jiguandongtai',$newslistValue);
    	
    	//刑侦动态
    	$newslistValue = array();
    	$icount = 0 ;
    	foreach ($wholeList as $key => $value) {
    		if($wholeList[$key]['father_module'] == 13)
    		{
    			array_push($newslistValue,$wholeList[$key]);
    			$icount++;
    		}
    		if ($icount > 7)
    		{
    			break;
    		}
    	}
    	$this->assign('xingzhendongtai',$newslistValue);
    	
    	//派出所动态
    	$newslistValue = array();
    	$icount = 0 ;
    	foreach ($wholeList as $key => $value) {
    		if($wholeList[$key]['father_module'] == 14)
    		{
    			array_push($newslistValue,$wholeList[$key]);
    			$icount++;
    		}
    		if ($icount > 7)
    		{
    			break;
    		}
    	}
    	$this->assign('paichusuodongtai',$newslistValue);
    	
    	//队伍建设
    	$newslistValue = array();
    	$icount = 0 ;
    	foreach ($wholeList as $key => $value) {
    		if($wholeList[$key]['father_module'] == 15)
    		{
    			array_push($newslistValue,$wholeList[$key]);
    			$icount++;
    		}
    		if ($icount > 7)
    		{
    			break;
    		}
    	}
    	$this->assign('duiwujianshe',$newslistValue);
    	
    	//经验交流
    	$newslistValue = array();
    	$icount = 0 ;
    	foreach ($wholeList as $key => $value) {
    		if($wholeList[$key]['father_module'] == 16)
    		{
    			array_push($newslistValue,$wholeList[$key]);
    			$icount++;
    		}
    		if ($icount > 7)
    		{
    			break;
    		}
    	}
    	$this->assign('jingyanjiaoliu',$newslistValue);
    	
    	//调查研究
    	$newslistValue = array();
    	$icount = 0 ;
    	foreach ($wholeList as $key => $value) {
    		if($wholeList[$key]['father_module'] == 17)
    		{
    			array_push($newslistValue,$wholeList[$key]);
    			$icount++;
    		}
    		if ($icount > 7)
    		{
    			break;
    		}
    	}
    	$this->assign('diaochayanjiu',$newslistValue);
    	
    	//规章制度
    	$newslistValue = array();
    	$icount = 0 ;
    	foreach ($wholeList as $key => $value) {
    		if($wholeList[$key]['father_module'] == 18)
    		{
    			array_push($newslistValue,$wholeList[$key]);
    			$icount++;
    		}
    		if ($icount > 7)
    		{
    			break;
    		}
    	}
    	$this->assign('guizhangzhidu',$newslistValue);
    	
    	//执法规范
    	$newslistValue = array();
    	$icount = 0 ;
    	foreach ($wholeList as $key => $value) {
    		if($wholeList[$key]['father_module'] == 19)
    		{
    			array_push($newslistValue,$wholeList[$key]);
    			$icount++;
    		}
    		if ($icount > 7)
    		{
    			break;
    		}
    	}
    	$this->assign('zhifaguifan',$newslistValue);
    	
    	//重要文件
    	$newslistValue = array();
    	$icount = 0 ;
    	foreach ($wholeList as $key => $value) {
    		if($wholeList[$key]['father_module'] == 20)
    		{
    			array_push($newslistValue,$wholeList[$key]);
    			$icount++;
    		}
    		if ($icount > 7)
    		{
    			break;
    		}
    	}
    	$this->assign('zhongyaowenjian',$newslistValue);
    	//test
    	
    	//$row = mysql_fetch_array($newslistValue);
    	
    	//print_data($newslistValue,true);
    	
    	//print_data(date('m-d',$newslistValue['public_time']),true);
    	
    	// step2 push the information to the front page.
    	//$this->assign('tonggaotongzhi',$newslistValue);
    	//phpinfo();
    	//step3 return
    	return $this->fetch();
    }
    
    //普通用户登录，用户签收文件，记录网站访问log
    public function userLogin()
    {
    	
    }
    
    //
    
    /* test begin
    public function hello($name = 'thinkphp')
    {
    	//return 'hello,thinkphp!';
    	$this->assign('myname',$name);
    	return $this->fetch();
    }
    
    public function test()
    {
    	return '这是一个测试方法!';
    }
    
    public function usedatabase()
    {
    	$data = Db::name('data')->find();// name function 指定数据表，不含前缀； find 查找单个记录
    	$this->assign('result',$data);
    	return $this->fetch('showdatabase');
    }
    
    protected function hello2()
    {
    	return '只是protected方法!';
    }
    
    private function hello3()
    {
    	return '这是private方法!';
    }
    
    public function myrequest($name = "world")
    {
    	$request = Request::instance();
    	//gei current URL
    	echo 'url = '.$request->url().'<br/>';
    	return 'hello'.$name.'!';
    }
    public function getparam(Request $request)
    {
		echo "请求参数：";
		dump($request->param());
		echo 'name：'.$request->param('name');
    }
    
    public function myfilter(Request $request)
    {
    	echo 'name:'.$request->param('name','World','strtolower');
    	echo '<br/>test:'.$request->param('test','thinkphp','strtoupper');
    }
    test end*/
    public function _empty()
    {
    	$this->redirect(url('index/index/index'));
    	return null;
    }
   
    
}
