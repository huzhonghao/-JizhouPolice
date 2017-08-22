<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

//这两句话 相当于'hello/[:name]' => 'index/index/hello', 这句话
/*use think\Route;
//Route::rule('hello/[:name]$', 'index/hello'); //$ 完全匹配
Route::rule('hello/[:name]$',function ($name)
		{
	return 'hello~'.$name.'!!';
	
});*/

return [
    '__pattern__' => [ //全局变量,如果下面子项中又定义该变量，则会覆盖全局变量
        //'name' => '\w+',
    	'name'  => '\w+',
    	'id'    => '\d+',
    	'year'  => '\d{4}',
    	'month' => '\d{2}',
    ],
	//hello 路由分组
    '[hello]'     => [
        ':id'   => ['index/hello', ['method' => 'get'], ['id' => '\d+']],
        ':name' => ['index/hello', ['method' => 'post']],
    ],
	//使用[]把路由规则中的变量包起来，就表示该变量为可选
	'hello/[:name]' => ['index/index/hello',['method'=>'get','ext'=>'html']], // 输入 http://localhost/hello/lhh.html访问
	//普通blog 路由
	//'blog/:year/:month' => ['blog/archive',['method'=>'get'],['year'=>'\d{4}','month'=>'\d{2}']],
	'blog-<year>-<month>' => ['blog/archive',['method'=>'get'],['year'=>'\d{4}','month'=>'\d{2}']],
	//'blog/:id' => ['blog/get',['method'=>'get'],['id'=>'\d+']],
	//'blog/:name' => ['blog/read',['method'=>'get'],['name'=>'\w+']],
	//blog 路由分组
	'[blog]' =>[
			//':year/:month' => ['blog/archive',['method'=>'get'],['year'=>'\d{4}','month'=>'\d{2}']],
			
			':id' => ['blog/get',['method'=>'get'],['id'=>'\d+']],
			':name' => ['blog/read',['method'=>'get'],['name'=>'\w+']],
	],

];
