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
		$map = array(
			'isShow'		=> 1,
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
};
?>