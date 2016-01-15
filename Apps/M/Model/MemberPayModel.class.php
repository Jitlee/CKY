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
		$db = M('member');
		$filter["PayNo"]=$key;
		return $db->where($filter)->find();
	}
	
	public function UpdateRechange($dataInfo,$carid)
	{
		$dbMember = M('member_pay');
		$dbMember->save($dataInfo);
		
		$content="-----------------更新到一卡易---支付记录-----------------attach=".$attach;
		logger($content);
		
		//更新到一卡易
		$mOneCard = D('M/OneCard'); //出错处理
		$res=$mOneCard->AddValue($carid,$dataInfo["TotalMoney"]);
		$content="-----------------更新到一卡易-----------------attach=".$attach;
		logger($content);
 		//用户信息
		if($res["status"] == 0)
		{
			//同步用户记录
			$result=$mOneCard->DataSync($CardId);
		}
	}
	
}