<?php
 namespace Home\Model;
/**
 * ============================================================================
 * 粗卡云:
  
 * 联系方式:
 * ============================================================================
 *  
 */
class TicketModel extends BaseModel {
     
     /**
	  * 修改
	  */
	 public function check(){
	 	$rd = array('status'=>-1);
	 	
	 	$ticketkey =I("ticketkey");
		$data = array();
		$data["userName"] = I("userName"); 
		$m = M('activity_ticket_m');			 
		$rs = $m->where("usekey='".$ticketkey."'")->select(); 
		if(count($rs)>0){
			$tick=$rs[0];
			$checkStatus=(int)$tick["checkStatus"];
			if($checkStatus==0)
			{
				$USER = session('RTC_USER');
				//指定商家，其它商家不能验证
				$cshopid=(int)$USER["shopId"];
				$ticketId=$tick["ticketID"];
				//查询主表
				$maintb = M('activity_ticket');			 
				$tickMain = $maintb->where("ticketID='".$ticketId."'")->select();
				$limitSendShopID=(int)$tickMain[0]["limitSendShopID"];
				if($limitSendShopID >0 && $cshopid != $limitSendShopID)
				{
					$rd["msg"]="您无限验证此优惠券。";	
				}
				else
				{
					$tick["checkStatus"]=1;
					$tick["checkUser"]=(int)$USER['userId'];
					$tick["checkDate"]= date('Y-m-d H:i:s');	
					$result=$m->where("usekey='".$ticketkey."'")->save($tick);
					if($result != FALSE)
					{
						$rd["status"]=1;
						$sql="select act.* from __PREFIX__activity_ticket_m  actm inner join __PREFIX__activity_ticket act on actm.ticketID=act.ticketID 
								where actm.usekey='$ticketkey'";
						$blist = $this->query($sql);
						//更新使用数量
						$sql="update __PREFIX__activity_ticket set usedCount=usedCount+1 where ticketID = '".$ticketId."' ";	
						$ret = $this->query($sql);					
						$rd["obj"]=$blist[0];
					}
					else
					{
						$rd["msg"]="更新失败。";	
					}
				}
			}
			else
			{
				$rd["msg"]="卡券已验证";	
			}
		}
		else
		{
			$rd["msg"]="卡券无效";
		}
		return $rd;
	 } 
};
?>