<?php
//no use php file
namespace app\index\controller;

class Blog
{
	public function get($id)
	{
		return 'check id = '.$id." ' s content";
	}
	public function read($name)
	{
		return '查看name=' . $name . '的内容';
	}

	public function archive($year, $month)
	{
		return '查看' . $year . '/' . $month . '的归档内容';
	}
}