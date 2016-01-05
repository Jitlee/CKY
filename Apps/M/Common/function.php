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
