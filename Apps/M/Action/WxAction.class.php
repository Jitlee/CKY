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

		$json_obj =$this->accessToken();
		//根据openid和access_token查询用户信息
		$access_token = $json_obj['access_token'];
		$openid = $json_obj['openid'];
		 	
		$get_user_info_url = 'https://api.weixin.qq.com/sns/userinfo?access_token='.$access_token.'&openid='.$openid.'&lang=zh_CN';
		$user_obj = $this->getJson($get_user_info_url);
		//$_SESSION['user'] = $user_obj;
		
		session("userloginobj",$user_obj);
		$this->loginRedrect();
	}

	function loginRedrect()
	{
		$this->redirect('Person/index');
		exit;
//		$backurl=session("loginbackurl");
//		if($backurl)
//		{
//			$this->redirect($backurl);
//			session("loginbackurl",null);
//			exit;
//		}
//		else
//		{
//			$this->redirect('Person/index');
//			exit;
//		}
	}
	function accessToken(){
		$tokenFile = "./access_tokenkey.txt";//缓存文件名 
		$data = json_decode(file_get_contents($tokenFile)); 
		session("token",$data);
		if ($data or $data->expire_time < time() or !$data->expire_time) { 
			$appid = "wx9c7c9bb54952b54d";
			$secret = "d4624c36b6795d1d99dcf0547af5443d";
			$code = $_GET["code"];
			$get_token_url = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid='.$appid.'&secret='.$secret.'&code='.$code.'&grant_type=authorization_code';
				 
			$res = $this->getJson($get_token_url); 
			$access_token=$res;
			//$access_token = $res['access_token']; 
			if($access_token) 
			{ 
		        $data2['expire_time'] = time() + 7000; 
		        $data2['access_token'] = $res['access_token']; 
		        $fp = fopen($tokenFile, "w");
		        fwrite($fp, json_encode($data2));
		        fclose($fp);
	      	}
	    }
	    else
	    { 
	      $access_token = $data->access_token; 
	    }
	    return $access_token;
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
	
	public function getcodeurl() {
		$userlogin=session('userloginobj');
		if(isset($userlogin))
		{
			$this->loginRedrect();
			exit;
		}
		
		$APPID='wx9c7c9bb54952b54d';
		$REDIRECT_URI='http://' . $_SERVER['HTTP_HOST'] . U('callback', '', '');
		
		//$REDIRECT_URI='http://cky.ritacc.net/callback.php';
		$scope='snsapi_base';
		//$scope='snsapi_userinfo';//需要授权 
		$url='https://open.weixin.qq.com/connect/oauth2/authorize?appid='.$APPID.'&redirect_uri='.urlencode($REDIRECT_URI).'&response_type=code&scope='.$scope.'&state='.$state.'#wechat_redirect'; 
		header("Location:".$url);
		$this->display();
	}
}