<?php
namespace M\Action;
use Think\Controller;
 

class BaseUserAction extends BaseAction {

	protected function _initialize() {
<<<<<<< HEAD
			$openid=session('openid').'';
			//测试默认用户 
			if(strlen($openid)<10) {
				$openid="o4CBRwu4gN7w8JZsVCw6leu9g2-Y";
				session('openid',$openid);
			}
=======
		$openid=session('openid').'';
		//测试默认用户 
		if(strlen($openid)<10) {
			//$openid="o4CBRwu4gN7w8JZsVCw6leu9g2-Y";
			$openid="o4CBRwu4gN7w8JZsVCw6leu9g2-C";
			session('openid',$openid);
		}
>>>>>>> origin/master

		//如果openid不存在重新获取
		if(strlen($openid)>10){}
		else{
			$openid=$this->GetUserOpenID();
		}
		//echo $openid;
		if(strlen($openid)>10)
		{
			$result=session("MemberItem");
			$strcardid=$result["CardId"];
			if(!(strlen($strcardid)>3))//session 中不存在。
			{
				//验证是否已经关联
				$mMember = D('M/Member');
				$result=$mMember->GetByOpenid($openid);
				if(!$result)//如果用户不存在
				{
					$this->redirect('Home/selectreg');
				}
			}
			if($result)
			{
				session("cardid",$result["CardId"]);					
				session("uid",$result["uid"]);
				session("MemberItem",$result);	
			}
		}
		else
		{
			$this->redirect('Home/getwxerror');
		}
		$this->assign('tabid', "member");
	}
	
	function GetUserOpenID()
	{
		$openId=session('openid').'';
	 			
		if(strlen($openId)>10)
		{
			return $openId.'';
		}
		else
		{
			//1、获取openid
			vendor('Weixinpay.WxPayJsApiPay');
	        $tools = new \JsApiPay();
	        $openId = $tools->GetOpenid().'';
			session('openid',$openId);
			return $openId.'';
		}
	}
	
}