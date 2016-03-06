<?php
 namespace M\Model;
/**
 * ============================================================================
 * 粗卡云:
  
 * 联系方式:
 * ============================================================================
 * 卡券服务类
 */
class ActivityTicketModel extends BaseModel {
    public function queryAll($uid) {
    		$pageSize = 12;
		$pageNo = intval(I('pageNo', 1));
    		return $this->field('s.shopName, t.limitUseShopId, t.ticketID, t.title, ifnull(t.imagePath, s.shopImg) imagePath, t.ticketAmount, t.totalCount, t.sendCount, t.efficacySDate, t.efficacyEDate, t.miniConsumption, t.maxiConsumption, t.typeName, t.content,  isnull(tm.uid) isReceived, t.onlyNewUser')
			->join('t left join __SHOPS__ s on s.shopId = t.limitUseShopID')
    			->join('left join __ACTIVITY_TICKET_M__ tm on t.ticketID = tm.ticketID and tm.uid = '.$uid.'')
			->where('t.ticketStatus = 1 and t.efficacyEDate >= CURDATE()')
    			->order('t.createTime desc')->page($pageNo, $pageSize)->select();
    }
	
	public function queryPersonAll($uid) {
    		$pageSize = 12;
		$pageNo = intval(I('pageNo', 1));
		$type = intval(I('type', 0));
		if($type == 0) { // 有效
			$filter = 'ticketMStatus = 0 and t.efficacyEDate >= CURDATE()';
		} else if($type == 1) { // 过期
			$filter = 'ticketMStatus = 0 and t.efficacyEDate < CURDATE()';
		} else if($type == 2) { //已使用
			$filter = 'ticketMStatus = 1';
		}
    		return $this->field('s.shopName, t.limitUseShopId, t.ticketID, t.title, ifnull(t.imagePath, s.shopImg) imagePath,t.onlyNewUser,'
    			.' t.ticketAmount, tm.efficacySDate, tm.efficacyEDate, t.miniConsumption, t.maxiConsumption, t.typeName, t.content,'
    			.$type.' as status')
			->join('t left join __SHOPS__ s on s.shopId = t.limitUseShopID')
    			->join('inner join __ACTIVITY_TICKET_M__ tm on t.ticketID = tm.ticketID and tm.uid = '.$uid.'')
			->where($filter)
    			->order('t.createTime desc')->page($pageNo, $pageSize)->select();
    }

	public function queryUseAll($uid, $shopIds) {
		$sql = 'select t.ticketID, t.title, t.typeName, t.ticketAmount, t.content, t.limitUseShopID, s.shopId, s.shopName, '
				.'(CURDATE() between t.efficacySDate and t.efficacyEDate) valid, t.onlyNewUser, '
				.'t.efficacySDate, t.efficacyEDate, t.miniConsumption, t.maxiConsumption from cky_activity_ticket t '
				.'left join cky_shops s on s.shopId = t.limitUseShopID '
				.'where (t.limitUseShopID = 0 or t.limitUseShopID in('.$shopIds.')) '
				.'and EXISTS(select * from cky_activity_ticket_m tm where tm.ticketMStatus = 0 and t.ticketID = tm.ticketID and tm.uid='.$uid.') '
				.' order by t.createTime desc ';
		return $this->query($sql);
	}
	
	public function updateSendCount($id) {
		$map['ticketID'] = $id;
		$data['sendCount'] = array('exp', '`sendCount` + 1');
		return $this->where($map)->save($data);
	}
	
	public function updateUsedCount($id) {
		$map['ticketID'] = $id;
		$data['usedCount'] = array('exp', '`usedCount` + 1');
		return $this->where($map)->save($data);
	}

	public function getById($id, $uid) {
		$sql = 'select t.ticketID, t.ticketStatus, t.ticketAmount, t.content, t.limitUseShopID, t.onlyNewUser, t.efficacySDate, '
			.'s.shopName, t.detail, ifnull(t.imagePath, s.shopImg) imagePath, t.title, t.typeName, t.onlyNewUser, tm.ticketMStatus, '
			.'t.efficacyEDate, t.miniConsumption, t.maxiConsumption, UNIX_TIMESTAMP(t.efficacySDate) stime, tm.usekey, '
			.'UNIX_TIMESTAMP(t.efficacyEDate) etime from __ACTIVITY_TICKET__ t '
			.'inner join __ACTIVITY_TICKET_M__ tm on t.ticketID = tm.ticketID '
			.'left join __SHOPS__ s on s.shopId = t.limitUseShopID '
			.'where t.ticketID=\''.$id.'\' ' 
			.'and tm.uid='.$uid;
		$list = $this->query($sql);
		if(!empty($list)) {
			return $list[0];
		}
		return null;
	}
};
?>