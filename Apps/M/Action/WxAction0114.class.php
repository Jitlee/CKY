<?php
namespace M\Action;
/**
 * ============================================================================
 * 粗卡云:
  
 * 联系方式:
 * ============================================================================
 * 首页（默认）控制器
 */
use Think\Controller;
class WxAction extends Controller {
		
	public function callback() {
		$appid = "wx06dcafb051f5e21f";
		$secret = "0408887ca15441398695ddd0b70b9ff4";
		$code = $_GET["code"];
		$get_token_url = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid='.$appid.'&secret='.$secret.'&code='.$code.'&grant_type=authorization_code';
		$ch = curl_init();
		curl_setopt($ch,CURLOPT_URL,$get_token_url);
		curl_setopt($ch,CURLOPT_HEADER,0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1 );
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
		$res = curl_exec($ch);
		curl_close($ch);
		$json_obj = json_decode($res,true);
		//根据openid和access_token查询用户信息
		$access_token = $json_obj['access_token'];
		$openid = $json_obj['openid'];
		$get_user_info_url = 'https://api.weixin.qq.com/sns/userinfo?access_token='.$access_token.'&openid='.$openid.'&lang=zh_CN';
		$ch = curl_init();
		curl_setopt($ch,CURLOPT_URL,$get_user_info_url);
		curl_setopt($ch,CURLOPT_HEADER,0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1 );
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
		$res = curl_exec($ch);
		curl_close($ch);
		//解析json
		$user_obj = json_decode($res,true);

		session("userloginobj",$user_obj);
		$this->loginRedrect();
	}

	function loginRedrect()
	{
		$userlogin=session('userloginobj');
		$openid=$userlogin["openid"];
		if(empty($openid))
		{
			$this->redirect('Home/getwxerror');
			exit;
		}
		
		$this->redirect('Person/index');
		exit;
	}
	 
	public function getJson($url)
	{
		$ch = curl_init();
		curl_setopt($ch,CURLOPT_URL,$url);
		curl_setopt($ch,CURLOPT_HEADER,0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1 );
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
		$output=curl_exec($ch);
		curl_close($ch);
		return json_decode($output, true);
	}
	
	function accessToken(){
		$tokenFile = "./access_tokenkey.txt";//缓存文件名 
		$data = json_decode(file_get_contents($tokenFile)); 
		//session("wxposition","2");
		//session("token",$data);
		if (!$data or $data->expire_time < time()) {
			//session("wxposition","3"); 
			//$appid = "wx06dcafb051f5e21f";
			//$secret = "F39CA8630A1F402E984B99EC96D1ED68";
			//$code = $_GET["code"];
			//$get_token_url = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid='.$appid.'&secret='.$secret.'&code='.$code.'&grant_type=authorization_code';
			$get_token_url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.\WxPayConfig::APPID.'&secret='.\WxPayConfig::APPSECRET;
			
			$res = $this->getJson($get_token_url);
			$access_token = $res['access_token']; 
			if($access_token) 
			{
				//session("wxposition","4");  
		        $data2['expire_time'] = time() + 7000; 
		        $data2['access_token'] = $res['access_token'];
		        $fp = fopen($tokenFile, "w+");
		        fwrite($fp, json_encode($res));
		        fclose($fp);
				$t2=json_decode(file_get_contents($tokenFile));
				return $access_token;
	      	}
			return $access_token;
	    }
	    else
	    {
	      //session("wxposition","5");  
	      $access_token = $data->access_token; 
	    }
	    return $access_token;
	} 

	
	public function getcodeurl() {
		vendor('Weixinpay.WxPayJsApiPay');
        //1、获取openid
       
		
		$userlogin=session('userloginobj');
		$openid=$userlogin["openid"];
		
		if(strlen($openid)>10)
		{
			$this->loginRedrect();
			exit;
		}
		else
		{
			 $tools = new \JsApiPay();
        	 $openid = $tools->GetOpenid();
			 $access_token=$this->accessToken();
			 
			 $get_user_info_url = 'https://api.weixin.qq.com/sns/userinfo?access_token='.$access_token.'&openid='.$openid.'&lang=zh_CN';
			 session("access_token",$get_user_info_url);
//			$ch = curl_init();
//			curl_setopt($ch,CURLOPT_URL,$get_user_info_url);
//			curl_setopt($ch,CURLOPT_HEADER,0);
//			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1 );
//			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
//			$res = curl_exec($ch);
//			curl_close($ch);
			//解析json
			//$user_obj = json_decode($res,true);
			
			$user_obj=$this->getJson($get_user_info_url);
			session("userloginobj2",$user_obj);
			session("userloginobj",$user_obj);
			$this->loginRedrect();
		
//			 $userlogin["openid"]=$openId;
//			 session('userloginobj',$userlogin);
			 //$this->loginRedrect();
			 exit;
		}
//		session("wxposition","8");  
//		$APPID='wx06dcafb051f5e21f';
//		//$REDIRECT_URI='http://' . $_SERVER['HTTP_HOST'] . U('callback', '', '');
//		$REDIRECT_URI="http://cky.ritacc.net/index.php/M/Wx/callback";
//		//$REDIRECT_URI='http://cky.ritacc.net/callback.php';
//		$scope='snsapi_base';
//		//$scope='snsapi_userinfo';//需要授权 
//		$url='https://open.weixin.qq.com/connect/oauth2/authorize?appid='.$APPID.'&redirect_uri='.urlencode($REDIRECT_URI).'&response_type=code&scope='.$scope.'&state='.$state.'#wechat_redirect'; 
//		header("Location:".$url);
		//$this->display();
	}
}