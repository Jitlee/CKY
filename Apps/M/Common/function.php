<?php

//// 发送http请求
//function ajax($url, $data = null, $method = 'GET') {
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
	$hour = floor((float)$time);
	$muite = ((float)$time - $hour) * 60;
	return str_pad($hour, 2, "0", STR_PAD_LEFT).':'.str_pad((string)$muite, 2, "0", STR_PAD_LEFT);
}


function getuid()
{
	$uid=session('uid');
	if(!isset($uid))
	{
		$REDIRECT_URI='http://' . $_SERVER['HTTP_HOST'] . U('Wx/getcodeurl', '', '');
		header('Location:'.$REDIRECT_URI);	
	}	
	return $uid;
}

function  log_result($file,$word)
{
     $fp = fopen($file,'a');
     flock($fp, LOCK_EX) ;
     fwrite($fp,'执行日期：'.strftime('%Y-%m-%d-%H：%M：%S',time()).'\n'.$word.'\n\n');
     flock($fp, LOCK_UN);
     fclose($fp);
}



function traceHttp()
{
    logger('\n\nREMOTE_ADDR:'.$_SERVER['REMOTE_ADDR'].(strstr($_SERVER['REMOTE_ADDR'],'101.226')? ' FROM WeiXin': 'Unknown IP'));
    logger('QUERY_STRING:'.$_SERVER['QUERY_STRING']);
}
function logger($log_content)
{
    if(isset($_SERVER['HTTP_APPNAME'])){   //SAE
        sae_set_display_errors(false);
        sae_debug($log_content);
        sae_set_display_errors(true);
    }else{ //LOCAL
        $max_size = 500000;
        $log_filename = 'log.xml';
        if(file_exists($log_filename) and (abs(filesize($log_filename)) > $max_size)){unlink($log_filename);}
        file_put_contents($log_filename, date('Y-m-d H:i:s').$log_content.'\r\n', FILE_APPEND);
    }
}

/**
 * 掩码手机号:138****8888
 */
function maskPhone($phone) {
	$pattern = '/^(\d{3})\d{4}(\d+)$/';
	$replacement = '$1****$2';
	return preg_replace($pattern, $replacement, $phone);
}

 
function formatOrderStatus($orderStatus) {
	$orderStatus = (int)$orderStatus;
	switch($orderStatus) {
		case -2:
			return '订单已关闭';
		case -1:
			return '订单已取消';
		case 0:
			return '待支付';
		case 1:
			return '订单已支付';
		case 2:
			return '商家已受理';
		case 3:
			return '配送中';
		case 4:
			return '已送达';
		case 5:
			return '已到货';
		case 6:
			return '订单已完成';
		default:
			return '订单已结束';
	}
}

/**
 * Add version to the file for cache problem
 * @param string $url to add version
 * @return string
 */
function autoVer($filename){
	$ext = substr(strrchr($filename, '.'), 1);
	$path = $_SERVER['DOCUMENT_ROOT'].'/Apps/M/View/default/'.$ext.'/'.$filename;
    $ver = filemtime($path);
//	$ver='0415';
    echo $filename.'?v='.$ver;
}

/**
 * 获取评分星星html拼装内容
 */
function getScore($i=5)
{
	$empnum=5-$i;
	$rest="";
	for($in=0;$in<$i;$in++)
	{
		$rest=$rest."<i class='iconfont icon-favorfill star_c'></i>";
	}
	for($in=0;$in<$empnum;$in++)
	{
		$rest=$rest."<i class='iconfont icon-empty-star'></i>";
	}
	return $rest;
}

function test_login() {
		$openid=session('openid').'';	
		//如果openid不存在重新获取
		if(strlen($openid) <= 10) {
			$openid = get_user_open_id();
		}
		//echo $openid;
		if(strlen($openid) > 10) {
			$result=session("MemberItem");
			$strcardid=$result["CardId"];
			if(strlen($strcardid) <= 3) {//session 中不存在。
				//验证是否已经关联
				$mMember = D('M/Member');
				$result = $mMember->GetByOpenid($openid);
				if(!$result) { //如果用户不存在					
					$REDIRECT_URI='http://' . $_SERVER['HTTP_HOST'] . U('Home/selectreg', '', '');
					header('Location:'.$REDIRECT_URI);	
				}
			}
			if($result) {
				session("cardid",$result["CardId"]);					
				session("uid",$result["uid"]);
				session("Mobile",$result["Mobile"]);
				session("MemberItem",$result);	
			}
		} else {
			$REDIRECT_URI='http://' . $_SERVER['HTTP_HOST'] . U('Home/getwxerror', '', '');
			header('Location:'.$REDIRECT_URI);
			
		}
}
/*
 * 尝试登录 不用强制跳转
 */
function try_login() {
	$openid=session('openid').'';
	// 测试默认用户 
//	if(strlen($openid)<10) {
//		$openid="o4CBRwu4gN7w8JZsVCw6leu9g2-Y";
//		session('openid',$openid);
//	}
	
	//如果openid不存在重新获取
	if(strlen($openid) <= 10) {
		$openid = get_user_open_id();
	}
	//echo $openid;
	if(strlen($openid) > 10) {
		$result=session("MemberItem");
		$strcardid=$result["CardId"];
		if(strlen($strcardid) <= 3) {//session 中不存在。
			//验证是否已经关联
			$mMember = D('M/Member');
			$result = $mMember->GetByOpenid($openid);
			if(!$result) { //如果用户不存在
				return;
			}
		}
		if($result) {
			session("cardid",$result["CardId"]);					
			session("uid",$result["uid"]);
			session("Mobile",$result["Mobile"]);
			session("MemberItem",$result);	
		}
	}  
}	
function get_user_open_id() {
	$openId=session('openid').'';
			
	if(strlen($openId)>10) {
		return $openId.'';
	} else {
		//1、获取openid
		vendor('Weixinpay.WxPayJsApiPay');
	    $tools = new \JsApiPay();
	    $openId = $tools->GetOpenid().'';
		session('openid',$openId);
		return $openId.'';
	}
}
	