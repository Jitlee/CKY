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
    		return $this->field('s.shopImg, s.shopName, t.limitUseShopId, t.ticketID, t.title, t.imagePath, t.ticketAmount, t.totalCount, t.usedCount, t.efficacySDate, t.efficacyEDate, t.miniConsumption, t.typeName, t.content,  isnull(tm.uid) isReceived')
			->join('t left join __SHOPS__ s on s.shopId = t.limitUseShopID')
    			->join('left join __ACTIVITY_TICKET_M__ tm on t.ticketID = tm.ticketID')
			->where('t.ticketStatus = 1 and t.efficacyEDate >= CURDATE()')
    			->order('t.createTime')->page($pageNo, $pageSize)->select();
    }
};
?>