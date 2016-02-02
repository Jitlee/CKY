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
//	public function _initialize() {
//     vendor('Weixinpay.WxPayJsApiPay');
//  }
		
//	public function callback() {
//		$appid = "wx06dcafb051f5e21f";
//		$secret = "0408887ca15441398695ddd0b70b9ff4";
//		//$code = $_GET["code"];
//		//$get_token_url = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid='.$appid.'&secret='.$secret.'&code='.$code.'&grant_type=authorization_code';
//		$get_token_url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.$appid.'&secret='.$secret;
//		$ch = curl_init();
//		curl_setopt($ch,CURLOPT_URL,$get_token_url);
//		curl_setopt($ch,CURLOPT_HEADER,0);
//		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1 );
//		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
//		$res = curl_exec($ch);
//		curl_close($ch); 
//		
//		$tools = new \JsApiPay();
//      $openId = $tools->GetOpenid();		
//		$json_obj = json_decode($res,true);
//		
//		//根据openid和access_token查询用户信息
//		$access_token = $json_obj['access_token'];
//		 
//		$get_user_info_url = 'https://api.weixin.qq.com/cgi-bin/user/info?access_token='.$access_token.'&openid='.$openId.'&lang=zh_CN';
//		$ch = curl_init();
//		curl_setopt($ch,CURLOPT_URL,$get_user_info_url);
//		curl_setopt($ch,CURLOPT_HEADER,0);
//		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1 );
//		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
//		$res = curl_exec($ch);
//		curl_close($ch);
//		//解析json
//		$user_obj = json_decode($res,true);
//		session("userloginobj",$user_obj);
//		$this->display();
//	}
//
//	function loginRedrect()
//	{
//		$userlogin=session('userloginobj');
//		$openid=$userlogin["openid"].'';
//		if(strlen($openid)<10)
//		{
//			$this->redirect('Home/getwxerror');
//			exit;
//		}
//		$this->redirect('Person/index');
//		exit;
//	}
// 
//	
//	public function getJson($url)
//	{
//		$ch = curl_init();
//		curl_setopt($ch,CURLOPT_URL,$url);
//		curl_setopt($ch,CURLOPT_HEADER,0);
//		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1 );
//		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
//		$output=curl_exec($ch);
//		curl_close($ch);
//		return json_decode($output, true);
//	}
//	
//	public function getcodeurl() {
//		$userlogin=session('userloginobj');
//		$openid=$userlogin["openid"];
//		if(strlen($openid)>10)
//		{
//			$this->loginRedrect();
//			exit;
//		}
//		else
//		{
//			//1、获取openid
//			vendor('Weixinpay.WxPayJsApiPay');
//	        $tools = new \JsApiPay();
//	        $openId = $tools->GetOpenid();
//			
//			echo $openId;
//			
//			$userlogin["openid"]=$openId;
//			session('userloginobj',$userlogin);
//			$openid=$userlogin["openid"].'';
//			if(strlen($openid)>10)
//			{
//				$this->redirect('Person/index');
//				exit;
//			}
//		}
//
//	}
}