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
class PersonAction extends Controller {
	public function index() {
		
//		$m = D('M/OneCard');
//		$res=$m->GetUserInfo();

		//用户信息
//		$status= $res["status"];
//		if($status == 0)
//		{
//			$data=$res["data"][0];
//			session("cardid",$data["CardId"]);
//			$mMember = D('M/Member');
//			$result=$mMember->Insert($data);
//			if($result["status"]==-1)
//			{
//				echo "添加到数库失败。";
//			}
//			
//		}
//		else
//		{
//			//出错处理
//		}

		$key="18620554231";
		$mMember = D('M/Member');
		$result=$mMember->Get($key);

		//echo dump($data);
		$this->assign('title', "粗卡云");
		$this->assign('data', $result);		
		$this->display();
	}

	/*****一卡易与用户数据同步***/
	public function Sync()
	{
		$this->display();
	}
	
	public function changepwd()
	{
		if(IS_POST) {
			$key="18620554231";
			$mMember = D('M/OneCard');
			$result=$mMember->UpdatePassword($key,$_POST["oldpwd"],$_POST["newpwd"]);
			$this->ajaxReturn($result, "JSON");
		}
		else
		{
			layout(TRUE);
			$this->assign('title', "修改密码");
			$this->display();	
		}
	}
	
	public function userscore()
	{
		$m = D('M/OneCard');
		$cardid="18620554231";
		$res=$m->GetScoreList($cardid,0,20);
 
 		//用户信息
		$status= $res["status"];
		echo dump($res);
		if($status == 0)
		{
			$data=$res["data"];
			//session("cardid",$data["CardId"]);
			//$mMember = D('M/Member');
			//$result=$mMember->Insert($data);
//			if($result["status"]==-1)
//			{
//				echo "添加到数库失败。";
//			}
			$a=	json_decode($data,true);
			$this->assign('data', $a);		
			echo dump($data);
		}
		else
		{
			//出错处理
		}

		layout(TRUE);
		$this->display();
	}
	/*积分列表*/
	public function scorelist()
	{
		$m = D('M/OneCard');
		$cardid="18620554231";
		$res=$m->GetScoreList($cardid,0,20);
 
 		//用户信息
		$status= $res["status"];
		//echo dump($res);
		if($status == 0)
		{
			$data=$res["data"];
			$a=	json_decode($data,true);
			$this->assign('data', $a);		
			//echo dump($data);
		}
		else
		{
			//出错处理
		}
		layout(TRUE);
		$this->display();
	}
	
	/*充值记录*/
	public function rechargelist()
	{
		$m = D('M/OneCard');
		$cardid="18620554231";
		$res=$m->GetRechargeList($cardid,0,20);
 
 		//用户信息
		$status= $res["status"];
		//echo dump($res);
		if($status == 0)
		{
			$data=$res["data"];
			$a=	json_decode($data,true);
			$this->assign('data', $a);		
			//echo dump($data);
		}
		else
		{
			//出错处理
		}
		layout(TRUE);
		$this->display();
	}
	
	public function recharge()
	{
		layout(TRUE);
		$this->display();
	}
	public function userinfoitem()
	{
		layout(TRUE);
		$this->display();
	}
	
	
//	public function gettoken() {
//		$appid="wx9c7c9bb54952b54d";
//		$secret="d4624c36b6795d1d99dcf0547af5443d";
//		$url='https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.$appid.'&secret='.$secret;
//		$data=$this->GetData($url);
//		$arr=json_decode($data);
//		
//		session("access_token",$arr->access_token);
//		
//		$result["status"]=1;
//		$result["token"]=session("access_token");
//		
//		echo $result;
//	}
//	
//	
//	public function WeChat() {
//		session("getresult",$_POST);
//		$module = $_GET['module'];  
//		$action = $_GET['action'];  
//		$token = md5sum($module.date('Y-m-d',time()).'#$@%!*'.$action);  
//		if($token != $_GET['token']){  
//		    alarm('access deny');  
//		    exit();  
//		} 
//
//	}
//	public function GetWeChat() {
//		$this->assign('getresult',session("getresult"));
//		$this->display('default/person/gettoken');
//	}
//	
//	//lib/api.php 里面的方法是：
//	function postData($url, $data){
//	    $ch = curl_init();
//	    $timeout = 300;
//	    curl_setopt($ch, CURLOPT_URL, $url);
//	    //curl_setopt($ch, CURLOPT_REFERER, "http://www.jb51.net/");   //构造来路
//	    //curl_setopt($ch, CURLOPT_POST, true);
//	    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
//	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
//	    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
//	    //下面这句是绕过SSL验证，http://www.jb51.net/article/29282.htm，不然有些机器无法获得返回数据
//	    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 
//	    $handles = curl_exec($ch);
//	    curl_close($ch);
//	    return $handles;
//	}
//	function GetData($url){
//	    $ch = curl_init();
//	    $timeout = 300;
//	    curl_setopt($ch, CURLOPT_URL, $url);
//	    //curl_setopt($ch, CURLOPT_REFERER, "http://www.jb51.net/");   //构造来路
//	    //curl_setopt($ch, CURLOPT_POST, true);
//	    //curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
//	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
//	    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
//	    //下面这句是绕过SSL验证，http://www.jb51.net/article/29282.htm，不然有些机器无法获得返回数据
//	    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 
//	    $handles = curl_exec($ch);
//	    curl_close($ch);
//	    return $handles;
//	}
}