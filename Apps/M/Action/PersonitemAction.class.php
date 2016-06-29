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
			if($result["status"]==0)
			{
				$result=session("MemberItem");
				$result["Email"]=$email;
				session("MemberItem",$result);
				//更新到数据库
				$mdb = D('M/Member');
				$result=$mdb->UpdateMember($key,'Email',$email);
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
			$result = $mMember->UpdateMember($key,'birthday',$Birthday);
			if($result["status"]==0)
			{
				$result=session("MemberItem");
				$result["Birthday"]=$Birthday;
				session("MemberItem",$result);
				//更新到数据库
				$mdb = D('M/Member');
				$result=$mdb->UpdateMember($key,'Birthday',$Birthday);
			}
			$this->ajaxReturn($result, "JSON");
		}
		else
		{
			$openid=$this->GetOpenid();
			$mMember = D('M/Member');
			$result=$mMember->GetByOpenid($openid); 
			$Birthday=$result["Birthday"];
			
			$blen=strlen($Birthday);
			
			$this->assign('blen', $blen);
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
			$result = $mMember->UpdateMember($key,'trueName',$TrueName);
//			echo $key;
			if($result["status"]==0)
			{
				$result=session("MemberItem");
				$result["TrueName"]=$TrueName;
				session("MemberItem",$result);
				//更新到数据库 
				$mdb = D('M/Member');
				$result=$mdb->UpdateMember($key,'TrueName',$TrueName);
			}
			
			$this->ajaxReturn($result, "JSON");
		}
		else
		{
			$openid=$this->GetOpenid();
			$mMember = D('M/Member');
			$result=$mMember->GetByOpenid($openid); 
			$TrueName=$result["TrueName"];
			 
			$key=$this->GetCardId();
			  
			$this->assign('TrueName', $TrueName);
			$this->assign('title', "修改姓名");
			$this->display();	
		}
	}
	
	public function sex()
	{	
		if(IS_POST) {
			$key=$this->GetCardId();
			$mMember = D('M/OneCard');
			$Sex=I('sex',1);
			$result = $mMember->UpdateMember($key,'sex',$Sex);
			if($result["status"]==0)
			{
				$result=session("MemberItem");
				$result["Sex"]=$Sex;
				session("MemberItem",$result);
				//更新到数据库
				$mdb = D('M/Member');
				$result=$mdb->UpdateMember($key,'Sex',$Sex);
			}
			
			$this->ajaxReturn($result, "JSON");
		}
		else
		{
			$openid=$this->GetOpenid();
			$mMember = D('M/Member');
			$result=$mMember->GetByOpenid($openid); 
			$Sex=$result["Sex"];
			 
			$this->assign('Sex', $Sex);
			$this->assign('title', "");
			$this->display();	
		}
	}
	
	/*
	 * 修改手机号码
	 * */
	public function mobilenew()
	{	
		if(IS_POST) {
			$key=$this->GetCardId();
			$mMember = D('M/OneCard');
			$Mobile=I('mobile','');
			$verycode=I('verycode','');
			
			//验证 验证码.
			$mcode = D('M/Verifycode');	
			$rs=$mcode->Check($Mobile,$verycode);
			$status= (int)$rs["status"];
			if($status != 1)
			{
				 $result["msg"]="验证码无效。";
				 $this->ajaxReturn($result, "JSON");
				 return;
			}
			// end 验证 验证码
			
			$result = $mMember->UpdateMember($key,'mobile',$Mobile);
			if($result["status"]==0)
			{
				$result=session("MemberItem");
				$result["Mobile"]=$Mobile;
				session("MemberItem",$result);
				//更新到数据库
				$mdb = D('M/Member');
				$result=$mdb->UpdateMember($key,'Mobile',$Mobile);
			}
			
			$this->ajaxReturn($result, "JSON");
		}
		else
		{
			$openid=$this->GetOpenid();
			$mMember = D('M/Member');
			$result=$mMember->GetByOpenid($openid); 
			$Mobile=$result["Mobile"];
			 
			$this->assign('Mobile', $Mobile);
			$this->assign('title', "更换手机码");
			$this->display();	
		}
	}
	
	
}
	