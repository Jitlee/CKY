<?php
namespace M\Action;
use Think\Controller;
use P\Model;

class HomeAction extends BaseAction {
	
	
	public function _initialize(){
		$this->assign('tabid', "member");
	}
	
	public function selectreg() {
		$openid=$this->GetOpenid();
		if(empty($openid))			 
		{
			echo dump($userlogin);
			$this->display("getwxerror");
			exit;
		}
		$this->assign('openid', $openid); 
		$this->display();
	}
	/*错误提示*/
	public function getwxerror() {
		$openid=session('openid');
		echo $openid;
		$openid=''.$openid;		
		if($openid && strlen($openid)>10)
		{
			$this->redirect('Person/index');
			exit;
		}
		$this->assign('data', $userlogin); 
		$this->display();
	}
	/*申请新卡车*/
	public function reg() {
		if(IS_POST) {
			$result["status"]=0;
			$result["msg"]="注册成功。";			
			$openid=$this->GetOpenid();
			
			$openid=session('openid'); 
			if(empty($openid))
			{
				$result["msg"]="获取参数失败。".$openid;	
				$this->ajaxReturn($result, "JSON");
				exit;
			}
					  
			$password= $_POST['password'];
			$Mobile = $_POST['mobile'];
						
			$mMember = D('M/Member');
			$user=$mMember->GetByMobile($Mobile);	//根据手机查询	
			$userOpenkey=$mMember->GetByOpenid($openid);	//根据手机查询
			
			if($userOpenkey && $userOpenkey["OpenID"]==$openid) {
				$result["status"]=1000;
				$result["msg"]='您已是会员不能重复注册。';
			}
			else if($user && $user["Mobile"]==$Mobile) 
			{
				$result["msg"]="手机号：".$Mobile." 已经绑定。";
			}
			else
			{
				$data = array(
					"cardId"	=>$Mobile
					,"password"	=>$password 
					,"mobile" 	=>$_POST['mobile']
					,"memberGroupName"=>"默认级别"//"416f147d-0a89-e511-ab53-001018640e2a"	//默认级别
					,"trueName"	=>$_POST['trueName']
					,"sex"		=>$_POST['sex']
					,"userAccount"=>"10000"
				);
				
				$mOnecard = D('M/OneCard');
				$res=$mOnecard->UserReg($data);//可能有多条。
				$status= $res["status"];
				if($status == 0)//查询结果并写入到数据库
				{
					 //查询
					$res=$mOnecard->GetUserInfo($Mobile);					
					$status= $res["status"];
					if($status == 0)
					{
						$data=$res["data"][0];
						session("cardid",$data["CardId"]);
						$mMember = D('M/Member');
						$data["OpenID"]=$openid;
						$result=$mMember->Insert($data);
						if($result["status"]==-1)
						{
							$result["msg"]=  "绑定失败-add database。";
						}
						else
						{
							$result["status"]=1;
						}
					}
					else
					{
						$result["msg"]= $res["message"]."code:003";						
					}
				}
				else
				{
					$result["msg"]= $res["message"].$Mobile;
				}
			}
			$this->ajaxReturn($result, "JSON");
		}
		else
		{
			$this->assign('title', '会员注册');
			$this->display();
		}
		
	}
	/*关联根据手机号码*/
	public function conncardmobile() {
		if(IS_POST) {
			$result["status"]=0;
			$result["msg"]="绑定成功。";			
			
			$openid=$this->GetOpenid();
			if(empty($openid))
			{
				$result["msg"]="获取参数失败。";	
				$this->ajaxReturn($result, "JSON");
				exit;
			}				  
			//$verycode= $_POST['verycode'];
			$Mobile = $_POST['mobile'];
						
			$mMember = D('M/Member');
			$user=$mMember->GetByMobile($Mobile);	//根据手机查询	
			$userOpenkey=$mMember->GetByOpenid($openid);	//根据手机查询
			
			if($userOpenkey && $userOpenkey["OpenID"]==$openid) {
				$result["status"]=1000;
				$result["msg"]='您已是会员不需要重复绑定。';
			}
			else if($user && $user["Mobile"]==$Mobile) 
			{
				$result["msg"]="手机号：".$Mobile." 已经绑定。";
			}
			else
			{
				$mOnecard = D('M/OneCard');
				$res=$mOnecard->GetUserInfo($Mobile);//查询					
				$status= $res["status"];
				if($status == 0)
				{
					$data=$res["data"][0];
					session("cardid",$data["CardId"]);
					$mMember = D('M/Member');
					$data["OpenID"]=$openid;
					$result=$mMember->Insert($data);
					if($result["status"]==-1)
					{
						$result["msg"]=  "绑定失败-add database。";
					}
					else
					{
						//同步数据
						$mMember = D('M/MemberOneCardSync');
						$result=$mMember->DataSync($data["CardId"]);
						$result["status"]=1;
					}
				}
				else
				{
					$result["msg"]= $res["message"]."code:003";						
				}
			}
			$this->ajaxReturn($result, "JSON");
		}
		else
		{
			$this->assign('title', '绑定会员卡');
			$this->display();
		}
	}
	/*关联会员卡根据卡号，密码*/
	public function conncardbycardid() {
		if(IS_POST) {
			$result["status"]=0;
			$result["msg"]="注册成功。";			
			$openid=$this->GetOpenid();
			if(empty($openid))
			{
				$result["msg"]="获取参数失败。";	
				$this->ajaxReturn($result, "JSON");
				exit;
			}
			
			$password= $_POST['password'];
			$CardId = $_POST['cardid'];
						
			$mMember = D('M/Member');
			$user=$mMember->GetByCardID($CardId);	//根据手机查询	
			$userOpenkey=$mMember->GetByOpenid($openid);	//根据手机查询
			
			if($userOpenkey && $userOpenkey["OpenID"]==$openid) {
				$result["status"]=1000;
				$result["msg"]='您已绑定不需要重复。';
			}
			else if($user && $user["CardId"]==$CardId) 
			{
				$result["msg"]="会员卡：".$CardId." 已经绑定。";
			}
			else
			{
				$mOnecard = D('M/OneCard');
				$res=$mOnecard->MemberLogin($CardId,$password);
				$status= $res["status"];
				if($status == 0)//查询结果并写入到数据库
				{
					$res=$mOnecard->GetUserInfo($CardId);					
					$status= $res["status"];
					if($status == 0)
					{
						$data=$res["data"][0];
						session("cardid",$data["CardId"]);
						$mMember = D('M/Member');
						$data["OpenID"]=$openid;
						$result=$mMember->Insert($data);
						if($result["status"]==-1)
						{
							$result["msg"]="绑定失败-add database。";
						}
						else
						{
							$mMember = D('M/MemberOneCardSync');
							$result=$mMember->DataSync($data["CardId"]);
							$result["status"]=1;
						}
					}
					else
					{
						$result["msg"]= $res["message"]." E:003";						
					}
				}
				else
				{
					$result["msg"]= $res["message"].$Mobile;
				}
			}
			$this->ajaxReturn($result, "JSON");
		}
		else
		{
			$openid=$this->GetOpenid();
			
			$this->assign('openid', $openid); 
			$this->assign('title', '绑定会员卡');
			$this->display();
		}
	}
	
	
	public function ftest()
	{
 		$strvaldb="[快速充值][接口]粗卡云平台充值";
		$strval=str_replace("[快速充值][接口]","",$strvaldb);
		echo $strval; 		
		$this->display("Pay/orderpay");
	}

	
	 
}