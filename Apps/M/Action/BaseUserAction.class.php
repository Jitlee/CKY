<?php
namespace M\Action;
use Think\Controller;
 

class BaseUserAction extends BaseAction {

	protected function _initialize() {
			$userlogin=session('userloginobj');
			$openid=$userlogin["openid"];
			if(!$userlogin)
			{
				$userlogin["openid"]="oKxDRv3qgqwZVsHkOZXvcEgDkQyI";
				session('userloginobj',$userlogin);
				$openid=$userlogin["openid"];
			}
			 
			if(strlen($openid)>10)
			{
				//$this->assign('openid', $openid);
				$this->assign('nickname', $userlogin["nickname"]);
				$this->assign('headimgurl', $userlogin["headimgurl"]);
				
				$result=session("MemberItem");
				$strcardid=$result["CardId"];
				if(!($result && strlen($strcardid)>3))//session 中不存在。
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
				$this->redirect('Wx/getcodeurl');
			}
	}
}