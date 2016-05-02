<?php
 return array(
	    'SESSION_PREFIX'			=> 'user_', // SESSION前缀配置
		'COOKIE_PREFIX'			=> 'user_', // COOKIE前缀配置
		'LAYOUT_ON'				=>	true, // 启用布局
	
		'TMPL_PARSE_STRING' 		=> array( // 模板相关的配置
			'__JQ__'				=> '//cdn.bootcss.com/jquery/1.11.3/jquery.min.js',
			'__KO__'				=> '/Apps/M/View/default/js/knockout-3.4.0.js',

			'__FONT__'				=> '//at.alicdn.com/t/font_1462208730_1957695.css',
			'__IMG__'				=> '/Apps/M/View/default/images',

			'__JS__'				=> '/Apps/M/View/default/js',
			'__CSS__'				=> '/Apps/M/View/default/css',
			'__ROOTCSS__'			=> __ROOT__.'/Public/css',
			'__CSS__'				=> '/Apps/M/View/default/css',
		),
//		define('WEB_HOST', 'cky.ritacc.net'),
//		'WxPayConf_pub'=>array(
//			'APPID' => 'wx426b3015555a46be',
//			'MCHID' => '1292995201',
//			'KEY' => 'e10adc3949ba59abbe56e057f20f883e',
//			'APPSECRET' => '01c6d59a3f9024db6336662ac95c8e74',
//			'JS_API_CALL_URL'	=> WEB_HOST.'/index.php/M/Pay/wxpay',
//			'SSLCERT_PATH'		=> WEB_HOST.'/ThinkPHP/Library/Vendor/WxPayPubHelper/cacert/apiclient_cert.pem',
//			'SSLKEY_PATH'		=> WEB_HOST.'/ThinkPHP/Library/Vendor/WxPayPubHelper/cacert/apiclient_key.pem',
//			'NOTIFY_URL'		=> WEB_HOST.'/index.php/M/Pay/notify',
//			'CURL_TIMEOUT'		=> 30
//		),
	);
?>
