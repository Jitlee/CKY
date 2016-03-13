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
		return $this->find($activityId);
	}
	
	public function queryComing() {
    	$pageSize = 12;
		$pageNo = intval(I('pageNo', 1));
		
    		$catId = I('catId', 0);
		$time= strftime("%Y-%m-%d");
		$map = array(
			'isShow'				=> 1,
			'efficacySDate'		=> array('GT', $time),
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
		$time= strftime("%Y-%m-%d");
		$map = array(
			'limitUseShopID'		=> $shopId,
			'isShow'				=> 1,
			'efficacySDate'		=> array('ELT', $time),
			'efficacyEDate'		=> array('EGT', $time),
		);
		return $this->field('activityId, activityTitle, activityImg')->where($map)
			->order('createTime')->page($pageNo, $pageSize)->select();
	}
	
};
?>