<?php
namespace M\Action;
use Think\Controller;
 

class BaseUserAction extends BaseAction {
	
	protected function _initialize() {
 		$this->must_login();
	}
	
	function must_login() {
		$openid=session('openid').'';
		// 测试默认用户 
	//	if(strlen($openid)<10) {
	//		$openid="o4CBRwu4gN7w8JZsVCw6leu9g2-Y";
	//		session('openid',$openid);
	//	}
		
		//如果openid不存在重新获取
		if(strlen($openid) <= 10) {
			$openid = get_user_open_id();
		}
		//echo $openid;
		if(strlen($openid) > 10) {
			$result=session("MemberItem");
			$strcardid=$result["CardId"];
			if(strlen($strcardid) <= 3) {//session 中不存在。
				//验证是否已经关联
				$mMember = D('M/Member');
				$result = $mMember->GetByOpenid($openid);
				if(!$result) { //如果用户不存在
					$this->redirect('Home/selectreg');
				}
			}
			if($result) {
				session("cardid",$result["CardId"]);					
				session("uid",$result["uid"]);
				session("Mobile",$result["Mobile"]);
				session("MemberItem",$result);	
			}
		} else {
			$this->redirect('Home/getwxerror');
		}
	}
		
	
}