<?php
namespace app\admin\model;

use \think\Config;
use \think\Model;
use \think\Session;


/**
 * 操作日志记录
 */
class logRecord extends Admin
{
    protected $updateTime = false;
    protected $insert     = ['ip', 'user_no','browser','os'];
    protected $type       = [
        'create_time' => 'int',
    ];

    /**
     * 记录ip地址
     */
    protected function setIpAttr()
    {
        return \app\common\tools\Visitor::getIP();
    }

    /**
     * 浏览器把版本
     */
    protected function setBrowserAttr()
    {
        return \app\common\tools\Visitor::getBrowser().'-'.\app\common\tools\Visitor::getBrowserVer();
    }

    /**
     * 系统类型
     */
    protected function setOsAttr()
    {
        return \app\common\tools\Visitor::getOs();
    }

    /**
     * 用户id
     */
    protected function setUserNoAttr()
    {
        $user_no = 0;
        if (Session::has('jzhuserinfo', 'admin') !== false) {
            $user = Session::get('jzhuserinfo','admin');
            
           
            
            $user_no = $user['user_no'];
        }
        //print_data(‘11111111111’);
        return $user_no;
    }
 
    public function record($remark,$pos)
    {
        $this->save(['remark' => $remark,'log_pos' => $pos]);
    }


    public function UniqueIpCount()
    {   
        $data = $this->column('ip');
        $data = count( array_unique($data) );
        return $data;
    }

}
