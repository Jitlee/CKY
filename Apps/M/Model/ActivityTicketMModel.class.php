<?php
 namespace M\Model;
/**
 * ============================================================================
 * 粗卡云:
  
 * 联系方式:
 * ============================================================================
 * 卡券服务类
 */
class ActivityTicketMModel extends BaseModel {
	
	/**
	 * 领取券
	 */
	public function pick($ticketId, $uid) {
		$sql = 'insert into __ACTIVITY_TICKET_M__(ticketID, efficacySDate, efficacyEDate, uid, ticketMStatus)'
			.'select ticketID, efficacySDate, efficacyEDate, '.$uid.', 0 from __ACTIVITY_TICKET__'
			.' where ticketID = \''.$ticketId.'\' and usedCount < totalCount and ticketStatus = 1'
			.' and not exists(select 0 from __ACTIVITY_TICKET_M__ where uid = '.$uid.' and ticketID = \''.$ticketId.'\')';
		$ret = $this->query($sql);
//		echo $this->getLastSql();
		return $ret;
	}
};
?>