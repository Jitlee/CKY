<?php
 namespace M\Model;
/**
 * ============================================================================
 * 粗卡云:  
 * 联系方式:
 * ============================================================================
 * 用户数据与一卡易数据同步
 */
class MemberOneCardSyncModel extends BaseModel {
	 
	/*获取用户信息*/
	public function DataSync($userid)
	{	
	 	$mMember = D('M/Member');
		$MemberItem=$mMember->Get($userid);
		 
		if($MemberItem)
		{
			$moc = D('M/OneCard');
			$onecard=$moc->GetUserInfoObj($userid);			 
			if($onecard)
			{					
				//积分
				//$result["message"]=$MemberItem["EnablePoint"].$onecard["EnablePoint"];
				if($MemberItem["EnablePoint"] != $onecard["EnablePoint"])
				{
					//写入历史
					$this->UserScoreCheck($MemberItem["uid"],$userid);
					//更新主表
					$dbMember = M('Member');
					$MemberItem["EnablePoint"]=$onecard["EnablePoint"];
					$dbMember->save($MemberItem);
				}
				//储值金额
				if($MemberItem["EnableValue"] != $onecard["EnableValue"])
				{
					//写入历史
					$this->RechargeCheck($MemberItem["uid"],$userid);
					//更新主表
					$dbMember = M('Member');
					$MemberItem["EnableValue"]=$onecard["EnableValue"];
					$dbMember->save($MemberItem);
				}
			}
			else
			{
				$result["message"]="一卡易数据获取失败。";
			}
		}
		else
		{
			$result["message"]="用户数据查询失败。";
		}
		return $result;
	} 
	/***		用户积分历史		***/
	public function UserScoreCheck($uid,$cardid)
	{
		$m = D('M/OneCard');
		$res=$m->GetScoreList($cardid,0,30);//可能有多条。		 
 		//用户信息
		$status= $res["status"];
		if($status == 0)
		{
			$data=$res["data"];
			$list=json_decode($data,true);			
			
			//查询最后一条记录
			$Model=M("member_score");
			$filter["uid"]=$uid;
			$topm=$Model->order('OperateTime desc')->where($filter)->find();
			if($topm)
			{
				$OperateTime=strtotime($topm["OperateTime"]);	
			}
			//将一卡易数据写入到本地表
			for($i=0;$i<count($list);$i++){
				//echo dump($OperateTime);
				if(!$OperateTime || ($OperateTime && strtotime($list[$i]["OperateTime"]) > $OperateTime))
				{
					$ctemp=	$list[$i];					
					$item["uid"]=$uid;
					$item["BillNumber"]=$ctemp["BillNumber"];
					$item["Point"]= 	$ctemp["Point"];
					$item["Type"]= $ctemp["Type"];
					$item["Way"]= 	$ctemp["Way"];
					$item["Meno"]= 	$ctemp["Meno"];					
					$item["UserAccount"]= 	$ctemp["UserAccount"];
					$item["StoreName"]= 	$ctemp["StoreName"];					
					$item["OperateTime"]=$ctemp["OperateTime"];
					$item["IsUndo"]= 	$ctemp["IsUndo"];
					$Model->add($item);
				}
			}
		}		
	}
	
	/***		用户充值历史		***/
	public function RechargeCheck($uid,$cardid)
	{
		$m = D('M/OneCard');
		$res=$m->GetRechargeList($cardid,0,30);//可能有多条。		 
 		//用户信息
		$status= $res["status"];
		if($status == 0)
		{
			$data=$res["data"];
			$list=json_decode($data,true);			
			
			//查询最后一条记录
			$Model=M("member_recharge");
			$filter["uid"]=$uid;
			$topm=$Model->order('OperateTime desc')->where($filter)->find();
			if($topm)
			{
				$OperateTime=strtotime($topm["OperateTime"]);	
			}
			//将一卡易数据写入到本地表
			for($i=0;$i<count($list);$i++){
				//echo dump($OperateTime);
				if(!$OperateTime || ($OperateTime && strtotime($list[$i]["OperateTime"]) > $OperateTime))
				{
					$ctemp=	$list[$i];					
					$item["uid"]=$uid;
					$item["BillNumber"]=$ctemp["BillNumber"];
					$item["Value"]= 	$ctemp["Value"];
					$item["PaidMoney"]= 	$ctemp["PaidMoney"];
					$item["Type"]= $ctemp["Type"];
					$item["Way"]= 	$ctemp["Way"];
					$item["Meno"]= 	$ctemp["Meno"];					
					$item["UserAccount"]= 	$ctemp["UserAccount"];
					$item["StoreName"]= 	$ctemp["StoreName"];					
					$item["OperateTime"]=$ctemp["OperateTime"];
					$item["IsUndo"]= 	$ctemp["IsUndo"];
					$Model->add($item);
				}
			}
		}		
	}
	//end 
}