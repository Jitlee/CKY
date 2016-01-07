<?php

//// 发送http请求
//function ajax($url, $data = null, $method = "GET") {
//	$postdata = http_build_query($data);
//	$options = array(
//	'http' => array(
//			'method'	=> $method,//or GET
//	  		'header' 	=> 'Content-type:application/x-www-form-urlencoded',
//	  		'content' 	=> $postdata,
//	  		'timeout'	=> 30 // 超时时间（单位:s）
//	  	)
//	);
//	$context = stream_context_create($options);
//	$result = file_get_contents($url, false, $context);
//	return $result;
//}

function timeToString($time) {
	$time = floatval($time);
	$hour = floor($time);
	$muite = ($time - $hour) * 60;
	return str_pad((string)$hour, 2, STR_PAD_LEFT).':'.
		str_pad((string)$muite, 2, STR_PAD_LEFT);
}


function getopenid()
{
	//	$userlogin=session('userloginobj');
	//	$openid=$userlogin["openid"];
	//	return $openid;
	 	
	return "openid11111";
}
