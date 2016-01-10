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
class PersonAction extends BaseUserAction {
	public function index() {
		$openid=$this->GetOpenid();
		$mMember = D('M/Member');
		$result=$mMember->GetByOpenid($openid);
		if(!$result)	
		{
			$this->redirect('Home/selectreg');
			exit;
		} 
				
		session("MemberItem",$result);
		
		$this->assign('title', "粗卡云");
		$this->assign('data', $result);
		//layout(TRUE);
		$this->display();
	}

	/*****一卡易与用户数据同步***/
	public function Sync($v=0)
	{
		$CardId=$this->GetCardId();
		$mMember = D('M/MemberOneCardSync');
		$result=$mMember->DataSync($CardId);
		$this->ajaxReturn($result, "JSON");
	}
	
	public function changepwd()
	{
		if(IS_POST) {
			$key=$this->GetCardId();
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
		$uid=session("uid");
		$result=$mMember->GetScoreList($uid,$pageSize,$pageNum);
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
		$uid=session("uid");
		$result=$mMember->GetRechargeList($uid,$pageSize,$pageNum);
		if($type==0)
		{
			return $result;
		}		
		$this->ajaxReturn($result, "JSON");
	}
	
	/*储值*/
	public function consumelist()
	{
		$mMember = D('M/Member');
		$result=$this->consumelistPage(10,1,0);		
		$this->assign('data', $result);
		$this->assign('title', "消费记录");
		layout(TRUE);
		$this->display();
	}
	
	public function consumelistPage($pageSize = 10, $pageNum = 1,$type=1)
	{
		$mMember = D('M/Member');
		$uid=session("uid");
		$result=$mMember->GetConsumeList($uid,$pageSize,$pageNum);
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
	public function userinfo()
	{
		$openid=$this->GetOpenid();
		$mMember = D('M/Member');
		$result=$mMember->GetByOpenid($openid);
		if(!$result)	
		{
			$this->redirect('Home/selectreg');
			exit;
		}
		
		$this->assign('data', $result);
		$this->assign('title', "个人信息");		
		$this->display();
	}	

//	public function erwima()
//	{
//		$openid=$this->GetOpenid();
//		$mMember = D('M/Member');
//		$result=$mMember->GetByOpenid($openid); 
//		$this->assign('title', "粗卡云");
//		$this->assign('data', $result);
//		layout(FALSE);
//		$this->display();
//	}

	/*我的充次*/
 	public function countlist()
	{
		$this->assign('title', "我的充次");		
		$this->display();
	}
	/*绑定手机*/
 	public function bindmobile(){
		$this->assign('title', "绑定手机");		
		$this->display();
	}
	/*我的收藏*/
 	public function favor()	{
		$this->assign('title', "我的收藏");		
		$this->display();
	}
	/*我的优惠券*/
 	public function youhuiquan()	{
		$this->assign('title', "我的优惠券");		
		$this->display();
	}
	/*订单查询*/
 	public function orderlist()	{
		$this->assign('title', "订单查询");		
		$this->display();
	}
	
	/*生成二维码*/
 	public function qrcode($level=3,$size=8)
 	{ 	 	
			$cardid=$this->GetCardId();
			Vendor('phpqrcode.phpqrcode');
			$errorCorrectionLevel =intval($level) ;//容错级别 
			$matrixPointSize = intval($size);//生成图片大小 
			//生成二维码图片 
			//echo $_SERVER['REQUEST_URI'];
			$object = new \QRcode();
			$object->png($cardid, false, $errorCorrectionLevel, $matrixPointSize, 2);
	}
	/**一维码*/
	public function ywm()
	{
		$code ="18620554231";// $_GET['code'];
		  
		Vendor('phpqrcode.UPCtools');
		$obj= new \UPCtools();
		$obj->UPCAbarcode($code);
	}
	
	
}