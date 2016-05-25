<?php
 return array(
	    'SESSION_PREFIX'			=> 'user_', // SESSION前缀配置
		'COOKIE_PREFIX'			=> 'user_', // COOKIE前缀配置
		'LAYOUT_ON'				=>	true, // 启用布局
	
		'TMPL_PARSE_STRING' 		=> array( // 模板相关的配置
			'__JQ__'				=> '//cdn.bootcss.com/jquery/1.11.3/jquery.min.js',
			'__KO__'				=> '/Apps/M/View/default/js/knockout-3.4.0.js',

			'__FONT__'				=> '//at.alicdn.com/t/font_1464103195_839827.css',
			'__IMG__'				=> '/Apps/M/View/default/images',

			'__JS__'				=> '/Apps/M/View/default/js',
			'__CSS__'				=> '/Apps/M/View/default/css',
			'__ROOTCSS__'			=> __ROOT__.'/Public/css',
			'__CSS__'				=> '/Apps/M/View/default/css',
		),
		'alipay_config'=>array(
			'partner' 		=>'2088221515120177',   //这里是你在成功申请支付宝接口后获取到的PID；
			'key'			=>'b89bnds1e4bvme8a65go5uyz6h52xnrt',//这里是你在成功申请支付宝接口后获取到的Key
			'sign_type'		=>strtoupper('MD5'),
			'input_charset'	=> strtolower('utf-8'),
			'cacert'		=> getcwd().'\\cacert.pem',
			'transport'		=> 'http',
       ),
      //以上配置项，是从接口包中alipay.config.php 文件中复制过来，进行配置；
     
	'alipay'   =>array(
	  //这里是卖家的支付宝账号，也就是你申请接口时注册的支付宝账号
	'seller_email'=>'pay@xxx.com',
	
	//这里是异步通知页面url，提交到项目的Pay控制器的notifyurl方法；
	'notify_url'=>'http://www.cukayun.cn/Pay/notifyurl', 
	
	//这里是页面跳转通知url，提交到项目的Pay控制器的returnurl方法；
	'return_url'=>'http://www.cukayun.cn/Pay/returnurl',
	
	//支付成功跳转到的页面，我这里跳转到项目的User控制器，myorder方法，并传参payed（已支付列表）
	'successpage'=>'User/myorder?ordtype=payed',   
	
	//支付失败跳转到的页面，我这里跳转到项目的User控制器，myorder方法，并传参unpay（未支付列表）
	'errorpage'=>'User/myorder?ordtype=unpay', 
),




	);
?>
