<?php
 namespace M\Action;
 
class WxUserInfo
{

	public function callback($openId) { 	
		//根据openid和access_token查询用户信息
		$access_token =$this->accessToken();// ;$json_obj['access_token'];		 
		$get_user_info_url = 'https://api.weixin.qq.com/cgi-bin/user/info?access_token='.$access_token.'&openid='.$openId.'&lang=zh_CN';
		$ch = curl_init();
		curl_setopt($ch,CURLOPT_URL,$get_user_info_url);
		curl_setopt($ch,CURLOPT_HEADER,0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1 );
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
		$res = curl_exec($ch);
		curl_close($ch);
		//解析json
		$user_obj = json_decode($res,true);		
		//echo dump($user_obj);		
		return $user_obj['headimgurl'];
	}

	function accessToken(){
		$tokenFile = "./access_tokenkey.txt";//缓存文件名 
		$data = json_decode(file_get_contents($tokenFile)); 
		 
		if (!$data or $data->expire_time < time()) {
			vendor('Weixinpay.WxPayJsApiPay');
			$appid =  \WxPayConfig::APPID;
			$secret = \WxPayConfig::APPSECRET;
			
			$get_token_url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.$appid.'&secret='.$secret;
			$ch = curl_init();
			curl_setopt($ch,CURLOPT_URL,$get_token_url);
			curl_setopt($ch,CURLOPT_HEADER,0);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1 );
			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
			$res = curl_exec($ch);
			curl_close($ch); 			
			$json_obj = json_decode($res,true);
				
			$access_token = $json_obj['access_token'];			
			if($access_token) 
			{
		        $data2['expire_time'] = time() + 7000; 
		        $data2['access_token'] = $access_token; 				 
		        $fp = fopen($tokenFile, "w+");
		        fwrite($fp, json_encode($data2));
		        fclose($fp);  
	      	}
			return $access_token;
	    }
	    else
	    {	       
	      $access_token = $data->access_token; 
	    }
	    return $access_token;
	} 
	
	
	
	/******
	 * 下面用于，微信分享
	 * ******/
	 
	 
	 
	 
	 
	 
	 
	 
	 
	 
} 