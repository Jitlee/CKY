<?php
 namespace M\Model;
/**
 * ============================================================================
 * 粗卡云:
  
 * 联系方式:
 * ============================================================================
 * 一卡易接 口—— 卡券类
 */
class OneCardTickModel extends OneCardModel {
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
		//return $onecres;
		if($onecres["status"] != 0)
		{
			$rd["msg"]=$onecres["message"];
			return $rd;
		}
		//与本地库同步		 
		$ticklist=json_decode($onecres["data"],true);
//		echo dump($ticklist);
//		exit;
		$tickdb = M('activity_ticket');
		for($i=0;$i<count($ticklist);$i++)
		{		
			$onecitem=$ticklist[$i];
			/****设置内容*****/
			$data = array();
			$data["title"] = $onecitem["Title"];
			
			$typeshowname=$onecitem["TypeName"];
			//echo $typeshowname;
			if($typeshowname== "代金券"){$data["typeName"] ="djq";}
			else if($typeshowname == "折扣券"){$data["typeName"] ="zkq";}
			else if($typeshowname == "普通券"){$data["typeName"] ="ptq";}
			
		 
			if($onecitem["EndDate"]!='永久有效')
			{
				$indexofz=strpos($onecitem["EndDate"],"到");
				if($indexofz>1)
				{
					$data["efficacySDate"]=substr($onecitem["EndDate"],0, $indexofz);
					$data["efficacyEDate"]=substr($onecitem["EndDate"], $indexofz+3);	
				}					
			}
			else
			{
				$data["efficacySDate"]=date('Y-m-d H:i:s');
				$data["efficacyEDate"]=date('Y-m-d H:i:s',strtotime('+10 year'));
			}
			$data["totalCount"] = (int)$onecitem["TotalCount"];
			$data["sendCount"] = (int)$onecitem["SendCount"];
			$data["usedCount"] = (int)$onecitem["UsedCount"];
			/****END设置内容*****/
				
			$filter["ticketID"]=$onecitem["Guid"];
			$cukaItem= $tickdb->where($filter)->find();
			if($cukaItem)//存在
			{
				$data["ticketAmount"] = (double)$onecitem["CouponValue"];
				$data["limitUseShopName"] = $onecitem["useStoreNameLimit"];//限制商家 
				$data["detail"] =$onecitem["Content"];//卡券详情				
				  
				$rs = $tickdb->where("ticketID='".$onecitem["Guid"]."'")->save($data);
				if(false !== $rs){
					$rd['status']= 1;
				}
			}
			else//不存在
			{
				$data["imagePath"] = $onecitem["ImagePath"];
				$data["content"]  = " ";
			
				$data["ticketID"]=$onecitem["Guid"];
				//$data["endDate"] = I("endDate");		
				$data["miniConsumption"]  =(double)$onecitem["MinConsumeValue"];
				$data["maxiConsumption"]  =0;//	$onecitem["ImagePath"];
				
				$data["limitDayUse"] = 1;//(int)$onecitem["limitDayUse"];
				$data["limitDayGet"] = 1;// (int)$onecitem["limitDayGet"];
				$data["limitGetnum"] = 1;// (int)$onecitem["limitGetnum"];
				$data["onlyNewUser"] = 0;// (int)$onecitem["onlyNewUser"];
				
				$data["ticketAmount"] = (double)$onecitem["CouponValue"];
				$data["limitUseShopID"] = 0;//(int)$onecitem["limitUseShopID"];//限制商家
				$data["limitUseShopName"] = $onecitem["useStoreNameLimit"];//限制商家
				
				$data["detail"] =$onecitem["Content"];//卡券详情				
				$data["createTime"] = date('Y-m-d H:i:s');
				$data["IsOneCardyTick"]=1;
				 
				$rs = $tickdb->add($data);
			    if(false !== $rs){
					$rd['status']= 1;
				}
				
			}//结束 添加
		}// end for
		 
		return $rd;
	}
	
	
	/* 发送卡券
	 */
	public function SendTick($tickid,$mobile, $uid='')
	{
		$rd = array('status'=>-1);
				 
		$data = array("userAccount"=>"10000"
			,"mobiles"=>$mobile
			,"couponGuid"=>$tickid
			,"sendCount"=>1
		);
		$url='OpenApi/SendCoupon';		
		$onecres= $this->GetData($url, $data);
		if($onecres["status"] != 0)
		{
			$rd["msg"]=$onecres["message"];
			return $rd;
		}		
		if(empty($uid))
		{
			$uid=session("uid");
		}
		return $this->GetTickMList($mobile,$uid);
	}
	
	public function GetTickMList($mobile, $uid='')
	{
		if(empty($uid))
		{
			$uid=session("uid");
		}
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
		if($onecres["status"] != 0)
		{
			$rd["msg"]=$onecres["message"];
			return $rd;
		}
		//echo $onecres["data"];
		//return $onecres;
		//与本地库同步		 
		$ticklist=json_decode($onecres["data"],true);
		
		$tickdb = M('activity_ticket_m');
		for($i=0;$i<count($ticklist);$i++)
		{
			$onecitem=$ticklist[$i];
			$tickid=$onecitem["CouponGuid"];
			/****设置内容*****/
			$data = array();
			/****END设置内容*****/				
			$filter["ticketmID"]=$onecitem["Guid"];
			$cukaItem= $tickdb->where($filter)->find();
			if($cukaItem)//存在
			{
				$data["ticketMStatus"]=0;
				if((int)$onecitem["EnableCount"] ==0)
				{
					$data["ticketMStatus"]=1;	
				}				  
				$rs = $tickdb->where("ticketmID='".$onecitem["Guid"]."'")->save($data);
				if(false !== $rs){
					$rd['status']= 1;
				}
			}
			else//不存在
			{
				if(strlen($tickid) >0)
				{
					$data["ticketmID"] = $onecitem["Guid"];
					$data["ticketID"] = $tickid;  
					
					$data["efficacySDate"]=$onecitem["StartDate"];
					$data["efficacyEDate"]=$onecitem["EndDate"];
					$data["usekey"]=$onecitem["CouponCode"]; 
					$data["ticketMStatus"]=0;
					$data["uid"]=$uid;
					if((int)$onecitem["EnableCount"] ==0)
					{
						$data["ticketMStatus"]=1;	
					}
					$data["ticketMStatus"]=0;
					if((int)$onecitem["EnableCount"] ==0)
					{
						$data["ticketMStatus"]=1;	
					}
								 
					$rs = $tickdb->add($data);
				    if(false !== $rs){
						$rd['status']= 1;
					}
				}
			}//结束 添加
		}// end for
		return $rd;
	}
	
	
	
}