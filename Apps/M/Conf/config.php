<?php
return array(
	    'SESSION_PREFIX'			=> 'user_', 	// SESSION前缀配置
		'COOKIE_PREFIX'			=> 'user_', 		// COOKIE前缀配置
		'LAYOUT_ON'				=>	true, 					// 启用布局
		'scorerate'				=>100,
		'LOG_RECORD' 			=> true, 					// 开启日志记录
		'LOG_LEVEL'  			=>'EMERG,ALERT,CRIT,ERR',  // 只记录EMERG ALERT CRIT ERR 错误
 
//		'URL_ROUTER_ON'	=> false, // 开启路由
//		'URL_ROUTE_RULES'=>array(
//		    '/'          => '/Mobile/Index/index',
//		    'Index$'          => '/Mobile/Index/index',
//		    'Index/index$'          => '/Mobile/Index/index',
//		    
//		    'Group$'		=> '/Mobile/Group/index',
//		    'Group/index$'		=> '/Mobile/Group/index',
//		),
		
 
		//'URL_PARAMS_BIND_TYPE'	=> 1,
 
		
		'TMPL_PARSE_STRING' 		=> array( // 模板相关的配置
			'__JQ__'				=> '//cdn.bootcss.com/jquery/1.11.3/jquery.min.js',
			'__KO__'				=> '/Apps/M/View/default/js/knockout-3.4.0.js',

			'__FONT__'				=> '//at.alicdn.com/t/font_8h1k2yjn8o6zjjor.css',
			'__IMG__'				=> '/Apps/M/View/default/images',

			'__JS__'				=> '/Apps/M/View/default/js',
			'__CSS__'				=> '/Apps/M/View/default/css',
			'__ROOTCSS__'			=> __ROOT__.'/Public/css',
			'__CSS__'				=> '/Apps/M/View/default/css',
			'__WEBROOT__'				=> 'http://www.cukayun.cn',
		),
		'alipay_config'=>array(
			'partner' 		=>'2088221515120177',   //这里是你在成功申请支付宝接口后获取到的PID；
			'seller_id' 		=>'2088221515120177',   //
			
			'key'			=>'b89bnds1e4bvme8a65go5uyz6h52xnrt',//这里是你在成功申请支付宝接口后获取到的Key
			'sign_type'		=>strtoupper('MD5'),
			'input_charset'	=> strtolower('utf-8'),
			'cacert'		=> getcwd().'\\cacert.pem',
			'transport'		=> 'http',
			'payment_type'	=>1,
			'service'		=>"alipay.wap.create.direct.pay.by.user",	
			'notify_url'=>'http://www.cukayun.cn/index.php/M/PayAli/notify', 	//这里是异步通知页面url，提交到项目的Pay控制器的notifyurl方法；
			'return_url'=>'http://www.cukayun.cn/index.php/M/PayAli/returnurl',  //这里是页面跳转通知url，提交到项目的Pay控制器的returnurl方法；
       ),
      
     
	

);
?>
