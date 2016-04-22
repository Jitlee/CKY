<?php
 namespace M\Model;
/**
 * ============================================================================
 * 粗卡云:
  
 * 联系方式:
 * ============================================================================
 * 秒杀服务类
 */
class MemberMiaoshaModel extends BaseModel {
	// 根据UID获取用户的头像，名称
	public function findUserByMmid($mmid = 0) {
		return $this
			->field("u.uid, replace(concat('/', u.ImagePath), '/http://', 'http://') userImg, INSERT(u.trueName,ROUND(CHAR_LENGTH(u.trueName) / 2),ROUND(CHAR_LENGTH(u.trueName) / 4),'****') userName")
			->join('mm inner join __MEMBER__ u on mm.uid = u.uid')
			->where(array('mmid' => I('mmid', $mmid)))
			->find();
	} 
	
	public function lst() {
		$pageSize = (int)I('pageSize', 20);
		$pageNo = (int)I('pageNo', 1);
		$miaoshaId = I('id');
		$qishu = (int)I('qishu');
		
		$filter = array();
		$filter['miaoshaId'] = $miaoshaId;
		$filter['qishu'] = $qishu;
		
		
		$order = "mmid asc"; // 最新
		
		$field = 'mm.mmid, mm.createTime, mm.miaoshaCount, mm.uid, INSERT(u.trueName,ROUND(CHAR_LENGTH(u.trueName) / 2),ROUND(CHAR_LENGTH(u.trueName) / 4),\'****\') userName';
				
		$list = $this
			->field($field)
			->join('mm inner join __MEMBER__  u on mm.uid = u.uid')
			->where($filter)
			->order($order)
			->page($pageNum, $pageSize)
			->select();
//		echo $this->getLastSql();
		return $list;
	}
}	
?>
