<?php
 return array(
	    'SESSION_PREFIX'			=> 'user_', // SESSION前缀配置
		'COOKIE_PREFIX'			=> 'user_', // COOKIE前缀配置
		'LAYOUT_ON'				=>	true, // 启用布局
	
		'TMPL_PARSE_STRING' 		=> array( // 模板相关的配置
			'__JQ__'				=> '//cdn.bootcss.com/jquery/1.11.3/jquery.min.js',
			'__KO__'				=> '/Apps/M/View/default/js/knockout-3.4.0.js',
			'__FONT__'			=> '//at.alicdn.com/t/font_1452437583_2707355.css',
			'__IMG__'			=> '/Apps/M/View/default/images',
			'__JS__'				=> '/Apps/M/View/default/js',
			'__CSS__'			=> '/Apps/M/View/default/css',
			'__ROOTCSS__'		=> __ROOT__.'/Public/css',
			'__CSS__'			=> '/Apps/M/View/default/css',
		),
	);
?>
