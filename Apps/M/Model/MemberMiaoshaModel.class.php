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
			->page($pageNo, $pageSize)
			->select();
//		echo $this->getLastSql();
		return $list;
	}
	
	public function me($uid) {
		$pageSize = (int)I('pageSize', 20);
		$pageNo = (int)I('pageNo', 1);
		$type = (int)I('type');
		
		$start = ($pageNo - 1) * $pageSize;
		$sql = null;
		if($type == 0) { // 秒杀纪录	
			$sql = "select mm.mmid, mm.miaoshaId, mm.qishu, mm.miaoshaCount, mm.createTime, g.goodsName, g.marketPrice, g.goodsThums, m.subTitle, m.miaoshaStatus, 0 isPrized, 0 prizeCode, 0 endTime
			 from cky_member_miaosha mm 
			 inner join cky_miaosha m on mm.qishu = m.qishu and mm.uid=$uid and mm.miaoshaId = m.miaoshaId and m.miaoshaStatus < 3
	inner join cky_goods g on g.miaoshaId = mm.miaoshaId
	union all
	select mm.mmid, mm.miaoshaId, mm.qishu, mm.miaoshaCount, mm.createTime, g.goodsName, g.marketPrice, g.goodsThums, m.subTitle, 3 miaoshaStatus, (mh.prizeUid = mm.uid and exists(select 0 from cky_miaosha_code mc where mc.mmid = mm.mmid and mc.mcid = mh.prizeId)) isPrized, 0 prizeCode, 0 endTime
	 from cky_member_miaosha mm
	  inner join cky_miaosha_history mh on mm.qishu = mh.qishu and mm.uid=$uid and mm.miaoshaId = mh.miaoshaId
	inner join cky_miaosha m on mm.miaoshaId = m.miaoshaId
	inner join cky_goods g on g.miaoshaId = mm.miaoshaId
	order by createTime desc limit $start, $pageSize";
		} else { //中奖纪录 
			$sql = "select mm.miaoshaId, mm.qishu, mm.miaoshaCount, mm.createTime, g.goodsName, g.marketPrice, g.goodsThums, m.subTitle, 3 miaoshaStatus, 1 isPrized, mh.prizeCode, mh.endTime
			 from cky_member_miaosha mm 
			 inner join cky_miaosha_history mh on mm.qishu = mh.qishu and mm.uid=$uid and mm.miaoshaId = mh.miaoshaId and mh.prizeUid=mm.uid
inner join cky_miaosha m on mm.miaoshaId = m.miaoshaId
inner join cky_goods g on g.miaoshaId = mm.miaoshaId
inner join cky_miaosha_code mc on mc.miaoshaId = mm.miaoshaId and mc.qishu = mm.qishu and mh.prizeId = mc.mcid and mm.mmid = mc.mmid
order by createTime desc limit $start, $pageSize";
		}
		
		$list = $this->query($sql);
		return $list;
	}
}	
?>
