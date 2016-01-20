<?php
namespace M\Action;
use Think\Controller;
 

class BaseUserAction extends BaseAction {

	protected function _initialize() {
			$userlogin=session('userloginobj');
			$openid=$userlogin["openid"];
			
			 
//			if(!$userlogin) {
//				$userlogin["openid"]="oKxDRv3qgqwZVsHkOZXvcEgDkQyI";
//				session('userloginobj',$userlogin);
//				$openid=$userlogin["openid"];
//			}
			
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
	}
	
	function GetUserOpenID()
	{
		$userlogin=session('userloginobj');
		$openid=$userlogin["openid"];
		if(strlen($openid)>10)
		{
			return $openid;
		}
		else
		{
			//1、获取openid
			vendor('Weixinpay.WxPayJsApiPay');
	        $tools = new \JsApiPay();
	        $openId = $tools->GetOpenid();
			
			$userlogin["openid"]=$openId;
			session('userloginobj',$userlogin);
			return $openid;
		}
	}
	
}