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
	public function OrderValuePay($dataInfo,$carid)
	{
		//向一卡易帐户充值
		$mOneCard = D('M/OneCard'); //出错处理
		$res=$mOneCard->PayValue($carid,$dataInfo["TotalMoney"]);
 		//用户信息
		if($res["status"] == 0)
		{
			//保存支付状态
			$dbMember = M('member_pay');
			$dbMember->save($dataInfo);
			
			//同步用户记录
			$mSync = D('M/MemberOneCardSync');
			$result=$mSync->DataSync($carid);
		}
		else
		{
			$content="-----------------OrderValuePay-充值出错-----------------";
			$content=$content.',PayType='.$dataInfo["PayType"].',Status='.$dataInfo["Status"];
			$content=$content.',Carid='.$carid;
			logger($content);
			logger($res["message"] );
		}
		return $res;
	}
	/*订单在线支付*/
	public function UpdateRechange($dataInfo,$carid)
	{
		$dbMember = M('member_pay');
		$dbMember->save($dataInfo);
		
		//向一卡易帐户充值
		$mOneCard = D('M/OneCard'); //出错处理
		$res=$mOneCard->AddValue($carid,$dataInfo["TotalMoney"]);
 		//用户信息
		if($res["status"] == 0)
		{
			$mSync = D('M/MemberOneCardSync');
			$result=$mSync->DataSync($carid);//同步用户记录
		}
		else
		{
			$content="-----------------订单在线支付-充值出错-----------------";
			$content=$content.',PayType='.$dataInfo["PayType"].',Status='.$dataInfo["Status"];
			$content=$content.',Carid='.$carid;
			logger($content);
			logger($res["message"] );
		}
	}
	/*余额支付*/
	public function UpdatePayOrder($dataInfo)
	{
		$orderid=$dataInfo["extendid"].'';
		//如果有错误，更改其它状态或备注
		$res["status"]=-1;
		if(strlen($orderid)<5)
		{
			$dataInfo["extendMeno"]="错误的订单号：".$orderid;
		}
		else
		{
			//更新订单状态
			$dbOrders = M('Orders');
			$res=$dbOrders->OrderPay($orderid)
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