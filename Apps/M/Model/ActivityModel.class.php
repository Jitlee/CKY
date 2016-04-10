<?php
 namespace M\Model;
/**
 * ============================================================================
 * 粗卡云:
  
 * 联系方式:
 * ============================================================================
 * 活动服务类
 */
class ActivityModel extends BaseModel {
    public function queryByCatId() {
    		$pageSize = 12;
		$pageNo = intval(I('pageNo', 1));
		
   	 	$catId = I('catId', 0);
		$time= strftime("%Y-%m-%d");
		$map = array(
			'isShow'				=> 1,
			'efficacySDate'		=> array('ELT', $time),
			'efficacyEDate'		=> array('EGT', $time),
		);
		if($catId > 0) {
			$map['catId']	= $catId;
		}
		return $this->field('activityId, activityTitle, activityImg')->where($map)
			->order('createTime')->page($pageNo, $pageSize)->select();
    }
	
	public function getById() {
		$activityId = I('id', 0);
		$uid = getuid();
		return $this
			->field("a.*, s.shopName, t.ticketID, t.title, ifnull(t.imagePath, s.shopImg) imagePath,left(t.imagePath,4) strhttp,
			t.IsOneCardyTick, t.ticketAmount, t.totalCount, t.sendCount, t.efficacySDate,
			(CURDATE() < t.efficacySDate) tooearly,
			(CURDATE() > t.efficacyEDate) toolate,
			t.efficacyEDate, t.miniConsumption, t.maxiConsumption, 
			t.typeName, t.content,  isnull(tm.uid) isReceived, t.onlyNewUser")
			->join('a left join __ACTIVITY_TICKET__ t on a.ticketId = t.ticketId')
			->join('left join __ACTIVITY_TICKET_M__ tm on a.ticketId = tm.ticketId and tm.uid='.$uid)
			->join('left join __SHOPS__ s on s.shopId = t.limitUseShopID')
			->find($activityId);
	}
	
	public function queryComing() {
    	$pageSize = 12;
		$pageNo = intval(I('pageNo', 1));
		
    		$catId = I('catId', 0);
		$map = array(
			'isShow'				=> 1,
			'efficacySDate'		=> array('exp', ' > CURDATE()'),
		);
		if($catId > 0) {
			$map['catId']	= $catId;
		}
		return $this->field('activityId, activityTitle, activityImg')->where($map)
			->order('createTime')->page($pageNo, $pageSize)->select();
    }
	
	public function queryByShopId() {
		$pageSize = 120;
		$pageNo = intval(I('pageNo', 1));
		
    		$shopId = I('shopId', 0);
//		$time= strftime("%Y-%m-%d");
		$map = array(
			'limitUseShopID'		=> $shopId,
			'isShow'				=> 1,
			'efficacySDate'		=> array('exp', ' <= CURDATE()'),
			'efficacyEDate'		=> array('exp', ' >= CURDATE()'),
		);
		return $this->field('activityId, activityTitle, activityImg')->where($map)
			->order('createTime')->page($pageNo, $pageSize)->select();
	}
	
};
?>