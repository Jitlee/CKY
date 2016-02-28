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
	/*订单在线支付*/
	public function OrderValuePay($dataInfo,$carid)
	{
		$mOneCard = D('M/OneCard'); //出错处理
		$res=$mOneCard->PayValue($carid,$dataInfo["TotalMoney"]);
		if($res["status"] == 0)
		{
			//更新订单状态
			$orderid=$dataInfo["extendid"];
			$dbOrders =D('M/Orders');
			$res=$dbOrders->OrderPay($orderid);
			
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
		$dbMember = M('member_pay');
		$dbMember->save($dataInfo);
			
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
		}
		else
		{
			$dataInfo["extendMeno"]="充值失败".$res["message"];
			$dataInfo["Status"]=88;
			
			$content="-----------------订单在线支付-充值出错-----------------";
			$content=$content.',PayType='.$dataInfo["PayType"].',Status='.$dataInfo["Status"];
			$content=$content.',Carid='.$carid;
			logger($content);
			logger($res["message"]);
		}
		$dbMember->save($dataInfo);
	}
	/*余额支付*/
	public function UpdatePayOrder($dataInfo)
	{
		$orderid=$dataInfo["extendid"].'';
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
			$res=$dbOrders->OrderPay($orderid);
			
			if($res["status"] == 1)
			{
				$dataInfo["extendMeno"]="在线支付成功";
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
			$content=$content.',Carid='.$carid;
			logger($content);
			logger($res["message"] );
		}
	}
	
}