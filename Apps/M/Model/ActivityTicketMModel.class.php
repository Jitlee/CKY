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
		.' where ticketID = \''.$ticketId.'\' and sendCount < totalCount and ticketStatus = 1'
		.' and not exists(select 0 from __ACTIVITY_TICKET_M__ where uid = '.$uid.' and ticketID = \''.$ticketId.'\'); ';			
		$ret = $this->query($sql);
		if($ret != FALSE)
		{
			$sql="update cky_activity_ticket_m set usekey=Cast(ticket_m_ID+10000000 as CHAR) 
	where createTime > date_add(now(),interval -1 minute) AND usekey='' ";
			$ret = $this->query($sql);
			//更新认领
			$sql="update cky_activity_ticket set sendCount=sendCount+1 where ticketID = '".$ticketId."' ";	
			$ret = $this->query($sql);
		}
		return $ret;
	}
	
	public function total($uid) {
		$filter = 'ticketMStatus = 0 and efficacyEDate >= CURDATE() and uid='.$uid;
		$unuse = $this->where($filter)->count();
		
		$filter = 'ticketMStatus = 1 and uid='.$uid;
		$used = $this->where($filter)->count();
		
		$filter = 'ticketMStatus = 0 and efficacyEDate < CURDATE() and uid='.$uid;
		$overdue = $this->where($filter)->count();
		
		return array(
			'unuse'			=> $unuse,
			'used'			=> $used,
			'overdue'		=> $overdue,
		);
	}
	
	public function isReceived($uid, $ticketId) {
		$map = array(
			'uid'		=> $uid,
			'ticketID'	=> $ticketId,
		);
		$data = $this->where($map)->find();
		return empty($data);
	}
	
	public function isNewUser($uid, $ticketId) {
		$sql = 'select 1 from cky_activity_ticket_m tm where tm.ticketID = '.$ticketId.' and '.
				'exists(select 0 from cky_users u where u.userId='.$uid.' and date_sub(curdate(), INTERVAL 30 DAY) > date(u.`createTime`)) '.
				'and exists(select 0 from cky_activity_ticket t where t.ticketID = tm.ticketID and t.onlyNewUser)';
		$list = $this->query($sql);
		return empty($list);
	}
};
?>