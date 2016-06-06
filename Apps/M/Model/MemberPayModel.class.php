<?php
 namespace M\Model;
/**
 * ============================================================================
 * 粗卡云:  
 * 联系方式:
 * ============================================================================
 * 用户数据与一卡易数据同步
 */
class MemberPayModel extends BaseModel {
	/*获取用户信息*/
	public function InitPay($dataInfo)
	{
		 $rd = array('status'=>-1);
		
		if($this->checkEmpty($data,true)){
			$db = M('member_pay');
			$rs = $db->add($dataInfo);
			if(false !== $rs){
				$rd['status']= 1;
			}
		}
		return $rd;
	}
	
	public function GetByPayNo($key)
	{
		$db = M('member_pay');
		$filter["payNo"]=$key;
		return $db->where($filter)->find();
	}
	/*储值卡支付*/
	public function OrderValuePay($dataInfo,$carid)
	{
		$dbMemberpay = M('member_pay');
		$mOneCard = D('M/OneCard'); //出错处理
		$accountmoney=$dataInfo["accountmoney"];
		$accountscore=$dataInfo["accountscore"];
		$paystatus=0;
		if($accountmoney>0)
		{
			$res=$mOneCard->PayValue($carid,$dataInfo["accountmoney"]);
			if($res["status"] == 0){
				$dataInfo["accountmoneyStatus"]=99;
			}
			else{
				$dataInfo["accountmoneyStatus"]=-1;
				$paystatus=-1;
			}
		}
		if($accountscore>0)
		{
			$scorerate =(int)C("scorerate");//积分兑换比例
			$payscore=$accountscore*$scorerate*-1;
			$res=$mOneCard->PayScore($carid,$payscore);
			if($res["status"] == 0){
				$dataInfo["accountscoreStatus"]=99;
			}
			else{
				$dataInfo["accountscoreStatus"]=-1;
				$paystatus=-1;
			}
		}
			//消费，给帐户添加积分
						
		$payamount=(int)$dataInfo["amount"];
		if($payamount>0)
		{
			$remark='粗卡管理平台消费';
			$mOneCard->AddScore($carid,$payamount,$remark);//出错也忽略	
		}
			
		if($paystatus == 0)
		{
			$dataInfo["Status"]=99;//主表状态
			//更新订单状态
			$orderid=$dataInfo["extendid"];
			$dbOrders =D('M/Orders');
			$res=$dbOrders->payOrder((int)$orderid ,1);
			
			if($res['status'] != 1) {
				
				//重要日志，这是非常严重的错误
				$content="扣费成功，更新订单状态失败.----extendid=$orderid,accountmoney=$accountmoney,accountscore=$accountscore";
				ImpoLogger($content);
				
				//也需要更新状态，因为它已经扣积分和钱了。
				$dbMemberpay->save($dataInfo);
				return $res;
			}			 
			//同步用户记录
			$mSync = D('M/MemberOneCardSync');
			$result=$mSync->DataSync($carid);
		}
		else
		{
			$dataInfo["extendMeno"]="储值卡支付失败".$res["message"];
			$dataInfo["Status"]=88;
			
			$content="-----------------OrderValuePay-充值出错-----------------";
			$content=$content.',PayType='.$dataInfo["PayType"].',Status='.$dataInfo["Status"];
			$content=$content.',Carid='.$carid;
			logger($content);
			logger($res["message"]);
		}
		//保存支付状态
		$dbMemberpay->save($dataInfo);			
		return $res;
	}
	
	/** 向一卡易帐户充值 **/
	public function UpdateRechange($dataInfo,$carid)
	{
		$dbMember = M('member_pay');
		//向一卡易帐户充值
		$mOneCard = D('M/OneCard'); //出错处理
		$res=$mOneCard->AddValue($carid,$dataInfo["TotalMoney"]);
 		
		if($res["status"] == 0)
		{
			//同步支付记录
			$mSync = D('M/MemberOneCardSync');
			$result=$mSync->DataSync($carid);//同步用户记录
			$res["status"]=1;
		}
		else
		{
			$dataInfo["extendMeno"]="充值失败".$res["message"];
			$dataInfo["Status"]=88;
			
			$content="-----------------Pay01 在线支付-支付成功，添加值失败-----------------";
			$content=$content.',PayType='.$dataInfo["PayType"].',Status='.$dataInfo["Status"];
			$content=$content.',Carid='.$carid."错误信息：".$res["message"];
			ImpoLogger($content);
			$res["status"]=-1;
			return $res;
		}
		$rs=$dbMember->save($dataInfo);
		if($rs == FALSE)
		{
			$payid=$dataInfo["payid"];
			$content="-----------------Pay02 在线支付-添加值成功，更新最后记录失败-----------------";
			$content=$content."carid=$carid,payid=$payid"."错误信息：".$res["message"];
			ImpoLogger($content);
			//更新状态失败，可能导致重复向卡里充值.
		}
		return $res;
	}
	/*在线支付*/
	public function UpdatePayOrder($dataInfo)
	{
		$orderid=$dataInfo["extendid"].'';
		$cardid=$dataInfo["cardid"];
		//如果有错误，更改其它状态或备注
		$res["status"]=-1;
		if(strlen($orderid)<1)
		{
			$dataInfo["extendMeno"]="错误的订单号：".$orderid;
		}
		else
		{
			//更新订单状态
			$dbOrders =D('M/Orders');
			$res=$dbOrders->payOrder((int)$orderid, 0);			
			if($res["status"] == 1)
			{
				$res["status"]=1;
				$dataInfo["extendMeno"]="在线支付成功";
			}
			//消费，给帐户添加积分
			$mOneCard = D('M/OneCard');
			$payamount=(int)$dataInfo["amount"];
			if($payamount >0)
			{
				$remark='粗卡管理平台消费';
				$mOneCard->AddScore($cardid,$payamount,$remark);//出错也忽略
				
				//同步用户记录
				$mSync = D('M/MemberOneCardSync');
				$result=$mSync->DataSync($carid);	
			}
		}
		$dbMember = M('member_pay');
		$dbMember->save($dataInfo); 
		 
 		//用户信息
		if($res["status"] == 1)
		{	
//			$mSync = D('M/MemberOneCardSync');
//			$result=$mSync->DataSync($carid);//同步用户记录
		}
		else
		{
			$content="-----------------UpdatePayOrder-----------------";
			$content=$content.',PayType='.$dataInfo["PayType"].',Status='.$dataInfo["Status"];
			$content=$content.',Carid='.$cardid;
			logger($content);
			logger($res["message"] );
		}
		return $res;
	}
	
	public function UpdatePay($dataInfo)
	{
		$rd = array('status'=>-1);	 
		$dbMember = M('member_pay');
//		$rs = $dbMember->save($dataInfo); 
		$payid=$dataInfo['payid'];
		echo $payid;
		$rs = $dbMember->where("payid=".$payid)->save($dataInfo); 
		if(false !== $rs){
			$rd['status']= 1;
		}
		return $rd;
	}
	
}