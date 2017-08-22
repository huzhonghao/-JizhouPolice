<?php
return [
		//网站名称
		'website'   =>      [
				'name'          => '衡水市冀州区公安局',
				'keywords'      =>  '衡水市,冀州区，公安局',
				'description'   =>  '衡水市公安局内部办公网'
		],
		// 默认输出类型
		'default_return_type'               => 'html',
		
		//缓存
		'cache'                             => [
				// 驱动方式
				'type'   => 'File',
				// 缓存保存目录
				'path'   => RUNTIME_PATH.'system/adminData/',
				// 缓存前缀
				'prefix' => '',
				// 缓存有效期 0表示永久缓存
				'expire' => 0,
		],

		// 'app_debug'              => true,

		'session'                => [
				'id'             => '',
				// SESSION_ID的提交变量,解决flash上传跨域
				'var_session_id' => '',
				// SESSION 前缀
				'prefix'         => '',
				// 驱动方式 支持redis memcache memcached
				'type'           => '',
				// 是否自动开启 SESSION
				'auto_start'     => true,
		],

		// 视图输出字符串内容替换
		'view_replace_str'       => [
				'__CSS__'    => STATIC_PATH . 'index/css',
				'__JS__'     => STATIC_PATH . 'index/js',
				'__IMG__'    => STATIC_PATH . 'index/img',
				'__LIB__'    => STATIC_PATH . 'index/lib',
				'__TEST__'    => STATIC_PATH . 'index/test'
		],

		//验证码

		'captcha'  => [
				// 验证码字符集合
				'codeSet'  => '2345678abcdefhijkmnpqrstuvwxyzABCDEFGHJKLMNPQRTUVWXY',
				// 验证码字体大小(px)
				'fontSize' => 50,
				// 是否画混淆曲线
				'useCurve' => false,
				// 验证码图片高度
				'imageH'   => 30,
				// 验证码图片宽度
				'imageW'   => 120,
				// 验证码位数
				'length'   => 5,
				// 验证成功后是否重置
				'reset'    => true
		],

		//伪静态
		'url_html_suffix' => false,
		'user_auth_key'     => 'Astonep@tp-admin!@#$',
];