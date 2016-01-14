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
	
	public function getcodeurl() {
		$userlogin=session('userloginobj');
		$openid=$userlogin["openid"];
		if(strlen($openid)>10)
		{
			$this->loginRedrect();
			exit;
		}
//		session("wxposition","8");  
		$APPID='wx06dcafb051f5e21f';
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