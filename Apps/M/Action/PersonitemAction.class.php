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
class PersonitemAction extends BaseUserAction {
		
	public function email()
	{	
		if(IS_POST) {
			$key=$this->GetCardId();
			$mMember = D('M/OneCard');
			$email=I('email','');
			$result = $mMember->UpdateMember($key,'email',$email);
			if($result["status"]==1)
			{
				$result=session("MemberItem");
				$result["email"]=$email;
				session("MemberItem",$result);
				//更新到数据库
				$uid=session("uid");
 
				$mdb = D('M/Member');
				$result=$mdb->UpdateMember($uid,'Email',$email);
			}
			
			$this->ajaxReturn($result, "JSON");
		}
		else
		{
			$openid=$this->GetOpenid();
			$mMember = D('M/Member');
			$result=$mMember->GetByOpenid($openid); 
			$email=$result["Email"];
			 
			$this->assign('email', $email);
			$this->assign('title', "修改电子邮件");
			$this->display();	
		}
	}
	
	

	public function birthday()
	{	
		if(IS_POST) {
			$key=$this->GetCardId();
			$mMember = D('M/OneCard');
			$Birthday=I('Birthday','');
			$result = $mMember->UpdateMember($key,'Birthday',$Birthday);
			if($result["status"]==1)
			{
				$result=session("MemberItem");
				$result["email"]=$Birthday;
				session("MemberItem",$result);
				//更新到数据库
				$uid=session("uid");
 
				$mdb = D('M/Member');
				$result=$mdb->UpdateMember($uid,'Email',$Birthday);
			}
			
			$this->ajaxReturn($result, "JSON");
		}
		else
		{
			$openid=$this->GetOpenid();
			$mMember = D('M/Member');
			$result=$mMember->GetByOpenid($openid); 
			$Birthday=$result["Birthday"];
			 
			$this->assign('Birthday', $Birthday);
			$this->assign('title', "修改生日");
			$this->display();	
		}
	}
	
	public function truename()
	{	
		if(IS_POST) {
			$key=$this->GetCardId();
			$mMember = D('M/OneCard');
			$TrueName=I('TrueName','');
			$result = $mMember->UpdateMember($key,'truename',$TrueName);
			if($result["status"]==1)
			{
				$result=session("MemberItem");
				$result["TrueName"]=$TrueName;
				session("MemberItem",$result);
				//更新到数据库
				$uid=session("uid");
 
				$mdb = D('M/Member');
				$result=$mdb->UpdateMember($uid,'Email',$TrueName);
			}
			
			$this->ajaxReturn($result, "JSON");
		}
		else
		{
			$openid=$this->GetOpenid();
			$mMember = D('M/Member');
			$result=$mMember->GetByOpenid($openid); 
			$TrueName=$result["TrueName"];
			 
			$this->assign('TrueName', $TrueName);
			$this->assign('title', "修改姓名");
			$this->display();	
		}
	}
	
	
}
	