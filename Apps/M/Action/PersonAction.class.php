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
class PersonAction extends CommonAction {
	public function index() {
		
		$key="18620554231";
		$mMember = D('M/Member');
		$result=$mMember->GetByCardID($key);
		
		
		session("uid",$result["uid"]);
	
		$this->assign('title', "粗卡云");
		$this->assign('data', $result);		
		
		$userlogin=session('userloginobj');
	
		layout(TRUE);
		$this->display();
	}

	/*****一卡易与用户数据同步***/
	public function Sync()
	{
		$key="18620554231";
		$mMember = D('M/MemberOneCardSync');
		$result=$mMember->DataSync($key);
		$this->ajaxReturn($result, "JSON");
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
	
//	public function userscore()
//	{
//		$m = D('M/OneCard');
//		$cardid="18620554231";
//		$res=$m->GetScoreList($cardid,0,20);
// 
// 		//用户信息
//		$status= $res["status"];
//		echo dump($res);
//		if($status == 0)
//		{
//			$data=$res["data"];
//			$a=	json_decode($data,true);
//			$this->assign('data', $a);		
//			echo dump($data);
//		}
//		else
//		{
//			//出错处理
//		}
//
//		layout(TRUE);
//		$this->display();
//	}
	/*积分列表*/
	public function scorelist()
	{ 
		$mMember = D('M/Member');
		$result=$this->scorelistPage(10,1,0);
		$this->assign('data', $result);
		$this->assign('title', "积分记录");
		layout(TRUE);
		$this->display();
	}
	public function scorelistPage($pageSize = 10, $pageNum = 1,$type=1)
	{
		$mMember = D('M/Member');
		$result=$mMember->GetScoreList(session("uid"),$pageSize,$pageNum);
		if($type==0)
		{
			return $result;
		}		
		$this->ajaxReturn($result, "JSON");
	}
	/*充值记录*/
	public function rechargelist()
	{
		$mMember = D('M/Member');
		$result=$this->rechargelistPage(10,1,0);		
		$this->assign('data', $result);
		$this->assign('title', "储值记录");
		layout(TRUE);
		$this->display();
	}
	public function rechargelistPage($pageSize = 10, $pageNum = 1,$type=1)
	{
		$mMember = D('M/Member');
		$result=$mMember->GetRechargeList(session("uid"),$pageSize,$pageNum);
		if($type==0)
		{
			return $result;
		}		
		$this->ajaxReturn($result, "JSON");
	}
	
	public function consumelist()
	{
		$mMember = D('M/Member');
		$result=$this->consumelistPage(10,1,0);		
		$this->assign('data', $result);
		$this->assign('title', "储值记录");
		layout(TRUE);
		$this->display();
	}
	
	public function consumelistPage($pageSize = 10, $pageNum = 1,$type=1)
	{
		$mMember = D('M/Member');
		$result=$mMember->GetConsumeList(session("uid"),$pageSize,$pageNum);
		if($type==0)
		{
			return $result;
		}		
		$this->ajaxReturn($result, "JSON");
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
	

 
}