<?php
 namespace M\Model;
/**
 * ============================================================================
 * 粗卡云:
  
 * 联系方式:
 * ============================================================================
 * 一卡易接 口—— 卡券类
 */
class OneCardTestModel extends OneCardModel {
	/* 获取一卡易中的卡券
	 */
	public function GetTick()
	{
		$rd = array('status'=>-1);		 
		$data = array("userAccount"=>"10000"
			,"where"=>"1=1"
			,"pageIndex"=>"0"
			,"pageSize"=>"20"
			,"orderBy"=>" CreateTime desc "
		);
		$url='OpenApi/Get_CouponPaged';	
		//更新卡券列表	
		$onecres= $this->GetData($url, $data);
		return $onecres;
	}
	
	
	/* 发送卡券
	 */
	public function SendTick($mobile)
	{
		$rd = array('status'=>-1);
				 
		$data = array("userAccount"=>"10000"
			,"mobiles"=>$mobile
			,"couponGuid"=>$tickid
			,"sendCount"=>1
		);
		$url='OpenApi/SendCoupon';		
		$onecres= $this->GetData($url, $data);
		
		return $onecres;
	}
	
	 
	public function GetTickMList($mobile)
	{
		$rd = array('status'=>-1);		
		$data = array(
			 "userAccount"=>"10000"
			,"where"=>" 1=1 and  mobile='$mobile' "
			,"pageIndex"=>"0"
			,"pageSize"=>"20"
			,"orderBy"=>" cardId desc "
		);
		$url='OpenApi/Get_CoupnSendPaged';
		$onecres=$this->GetData($url, $data);
		return $onecres;
	}
	
	
}