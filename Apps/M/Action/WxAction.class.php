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

		//session("wxposition","1");
//		$json_obj =$this->accessToken();
//		//session("wxaccess_token",$json_obj);
//		//根据openid和access_token查询用户信息
//		$access_token = $json_obj['access_token'];
//		$openid = $json_obj['openid'];
//		//session("openid2",$access_token);
//		$get_user_info_url = 'https://api.weixin.qq.com/sns/userinfo?access_token='.$access_token.'&openid='.$openid.'&lang=zh_CN';
//		$user_obj = $this->getJson($get_user_info_url);
//		//$_SESSION['user'] = $user_obj;

		$appid = "wx9c7c9bb54952b54d";
		$secret = "d4624c36b6795d1d99dcf0547af5443d";
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
//		$userlogin=session('userloginobj');
//		$openid=$userlogin["openid"];
//		if(empty($openid))
//		{
//			$this->redirect('Home/getwxerror');
//			exit;
//		}
		
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
//		$data = json_decode(file_get_contents($tokenFile)); 
//		session("wxposition","2");
//		session("token",$data);
//		if (!$data or $data->expire_time < time() or !$data->expire_time) {
			//session("wxposition","3"); 
			$appid = "wx9c7c9bb54952b54d";
			$secret = "d4624c36b6795d1d99dcf0547af5443d";
			$code = $_GET["code"];
			$get_token_url = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid='.$appid.'&secret='.$secret.'&code='.$code.'&grant_type=authorization_code';
				 
			$res = $this->getJson($get_token_url); 
			return $res;
//			$access_token = $res['access_token']; 
//			if($access_token) 
//			{
//				//session("wxposition","4");  
//		        $data2['expire_time'] = time() + 7000; 
//		        $data2['access_token'] = $res['access_token']; 
//				$k["123"]=11;
//				$k["abc"]="dg";
//		        $fp = fopen($tokenFile, "w+");
//		        fwrite($fp, json_encode($res));
//		        fclose($fp);
//				$t2=json_decode(file_get_contents($tokenFile));
//				session("wxac",$k);
//				return $access_token;
//	      	}
//			return $access_token;
//	    }
//	    else
//	    {
//	      session("wxposition","5");  
//	      $access_token = $data->access_token; 
//	    }
//	    return $access_token;
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
		//session("wxposition","6");  
//		$openid=$userlogin["openid"];
//		if(!empty($openid))
//		{
//			session("wxposition","7");  
//			$this->loginRedrect();
//			exit;
//		}
//		session("wxposition","8");  
		$APPID='wx9c7c9bb54952b54d';
		//$REDIRECT_URI='http://' . $_SERVER['HTTP_HOST'] . U('callback', '', '');
		$REDIRECT_URI="http://cky.ritacc.net/index.php/M/Wx/callback";
		//$REDIRECT_URI='http://cky.ritacc.net/callback.php';
		$scope='snsapi_base';
		//$scope='snsapi_userinfo';//需要授权 
		$url='https://open.weixin.qq.com/connect/oauth2/authorize?appid='.$APPID.'&redirect_uri='.urlencode($REDIRECT_URI).'&response_type=code&scope='.$scope.'&state='.$state.'#wechat_redirect'; 
		header("Location:".$url);
		//$this->display();
	}
}