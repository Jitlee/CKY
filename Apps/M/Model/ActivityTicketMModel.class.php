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
		//查询卡券信息
		$map = array('ticketID'	=> $ticketId);
		$mtick= M('activity_ticket');
		$mtickobj =$mtick->where($map)->find();
		
		$rd = array('status'=>-1);
		if($mtickobj)
		{
			$isone=(int)$mtickobj["IsOneCardyTick"];
			if($isone==1)
			{
				$m = D('M/OneCardTick');
				$mobile=session("Mobile");
				$res = $m->SendTick($ticketId,$mobile);
				return $res;
			}
			else
			{
				$sql = 'insert into __ACTIVITY_TICKET_M__(ticketID, efficacySDate, efficacyEDate, uid, ticketMStatus)'
				.'select ticketID, efficacySDate, efficacyEDate, '.$uid.', 0 from __ACTIVITY_TICKET__'
				.' where ticketID = \''.$ticketId.'\' and sendCount < totalCount and ticketStatus = 1'
				.' and not exists(select 0 from __ACTIVITY_TICKET_M__ where uid = '.$uid.' and ticketID = \''.$ticketId.'\'); ';			
				$ret = $this->query($sql);
				if($ret !== false)
				{
					$sql="update cky_activity_ticket_m 
							set usekey=Cast(FLOOR(1000 + (RAND() * 8000)) * 100000 +ticket_m_ID+100000 as CHAR)
						  where
						  	createTime > date_add(now(),interval -1 minute) AND usekey='' ";
					$ret = $this->query($sql);
					if($ret !== false) {
						//更新认领
						$sql="update cky_activity_ticket set sendCount=sendCount+1 where ticketID = '".$ticketId."' ";	
						$ret = $this->query($sql);
					}
				}
				return $ret;	
			}
		}
	}
	
	public function total($uid) {
		$filter = 'ticketMStatus = 0 and t.efficacyEDate >= CURDATE() and uid='.$uid;
		$unuse = $this->join('tm inner join __ACTIVITY_TICKET__ t on t.ticketID = tm.ticketID')->where($filter)->count();
		
		$filter = 'ticketMStatus = 1 and uid='.$uid;
		$used = $this->where($filter)->count();
		
		$filter = 'ticketMStatus = 0 and t.efficacyEDate < CURDATE() and uid='.$uid;
		$overdue = $this->join('tm inner join __ACTIVITY_TICKET__ t on t.ticketID = tm.ticketID')->where($filter)->count();
		
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
		// 新用户改为三天内有效。
		$sql = 'select 1 from cky_activity_ticket_m tm where tm.ticketID = '.$ticketId.' and '.
				'exists(select 0 from cky_member u where u.uid='.$uid.' and date_sub(curdate(), INTERVAL 3 DAY) < date(u.`RegisterTime`)) '.
				'and exists(select 0 from cky_activity_ticket t where t.ticketID = tm.ticketID and t.onlyNewUser)';
		$list = $this->query($sql);
		return empty($list);
	}

	public function updateStatus($uid, $ticketId, $status = 1) {
		$map = array(
			'uid'		=> $uid,
			'ticketID'	=> $ticketId,
		);
		$data['ticketMStatus'] = $status;
		return $this->where($map)->save($data);
	}
};
?>