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


function  log_result($file,$word)
{
     $fp = fopen($file,"a");
     flock($fp, LOCK_EX) ;
     fwrite($fp,"执行日期：".strftime("%Y-%m-%d-%H：%M：%S",time())."\n".$word."\n\n");
     flock($fp, LOCK_UN);
     fclose($fp);
}


function traceHttp()
{
    logger("\n\nREMOTE_ADDR:".$_SERVER["REMOTE_ADDR"].(strstr($_SERVER["REMOTE_ADDR"],'101.226')? " FROM WeiXin": "Unknown IP"));
    logger("QUERY_STRING:".$_SERVER["QUERY_STRING"]);
}
function logger($log_content)
{
    if(isset($_SERVER['HTTP_APPNAME'])){   //SAE
        sae_set_display_errors(false);
        sae_debug($log_content);
        sae_set_display_errors(true);
    }else{ //LOCAL
        $max_size = 500000;
        $log_filename = "log.xml";
        if(file_exists($log_filename) and (abs(filesize($log_filename)) > $max_size)){unlink($log_filename);}
        file_put_contents($log_filename, date('Y-m-d H:i:s').$log_content."\r\n", FILE_APPEND);
    }
}

