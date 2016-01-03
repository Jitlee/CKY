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
		$moc = D('M/OneCard');
		$onecard=$moc->GetUserInfo($userid);
		
	 	$mMember = D('M/Member');
		$Member=$mMember->Get($key);
		
		
	} 
}