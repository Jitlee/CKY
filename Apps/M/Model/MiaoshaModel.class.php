<?php
 namespace M\Model;
/**
 * ============================================================================
 * 粗卡云:
  
 * 联系方式:
 * ============================================================================
 * 秒杀服务类
 */
class MiaoshaModel extends BaseModel {
	public function queryMiaosha() {
		$pageSize = (int)I('pageSize', 20);
		$pageNo = (int)I('pageNo', 1);
		$sortType = (int)I('sortType', 0);
		$type = (int)I('type', 0);
		$catId = (int)I('catId', 0);
		
		$filter = array();
		$filter['isSale'] = 1;
		$filter['miaoshaStatus'] = array('lt', 3);
		
		if($type == 0) {
			$filter['jishijiexiao'] = 0;
		} else if($type == 1) {
			$filter['jishijiexiao'] = array('gt', 0);
		}
		
		if($catId > 0) {
			$filter['goodsCatId2'] = $catId;
		}
		
		$order = "g.createTime desc"; // 最新
		switch($sortType) {
			case 1: // 人气
				$order = "canyurenshu desc, ".$order;
				break;
			case 3:  // 剩余人数
				$order = "shengyurenshu asc, ".$order;
				break;
			case 4: // 价值
				$order = "marketPrice desc, ".$order;
				break;
			case 5: // 价值
				$order = " marketPrice asc, ".$order;
				break;
			case 6: // 剩余时间
				$order = "(now + jishijiexiao * 3600) asc, ".$order;
				break;
			default: 
				break;
		}
		
		$field = 'g.goodsId, g.goodsName, g.goodsImg, g.goodsThums, g.marketPrice, g.shopPrice, g.goodsSpec,
				m.miaoshaId, m.qishu, m.zongrenshu, m.canyurenshu, m.shengyurenshu, m.jishijiexiao, m.xiangou,
				m.subTitle, m.createTime,
				if(m.miaoshaStatus < 2 and m.shengyurenshu = 0 and 
					m.`zongrenshu` = (select count(0) from cky_miaosha_code mc 
							where mc.miaoshaId=m.miaoshaId and mc.qishu=m.qishu), 2, m.miaoshaStatus) miaoshaStatus,
				unix_timestamp() * 1000 now,
				unix_timestamp(date_add(m.lastTime, interval 3 minute))*1000 last';
				
		if($type == 1) {
			$field .= ', unix_timestamp(date_add(m.createTime, interval jishijiexiao hour))*1000 end';
		}
		
		$list = $this
			->field($field)
			->join('m inner join __GOODS__ g on m.miaoshaId = g.miaoshaId and g.goodsStatus=1 and g.goodsFlag=1')
			->where($filter)
			->order($order)
			->page($pageNo, $pageSize)
			->select();
//		echo $this->getLastSql();
		return $list;
	}

	public function queryHistory() {
		$pageSize = (int)I('pageSize', 20);
		$pageNo = (int)I('pageNo', 1);
		$sortType = (int)I('sortType', 0);
		$catId = (int)I('catId', 0);
		
		$filter = array();
		$order = "endTime desc"; // 最新
		
		if($catId > 0) {
			$filter['goodsCatId2'] = $catId;
		}
		
		$field = 'g.goodsId, g.goodsName, g.goodsImg, g.goodsThums, g.marketPrice, g.shopPrice, g.goodsSpec,
				h.miaoshaId, h.qishu, h.subTitle, h.endTime, h.prizeCode,h.prizeCount, h.shengyurenshu,
				h.prizeUid, INSERT(u.trueName,ROUND(CHAR_LENGTH(u.trueName) / 2),ROUND(CHAR_LENGTH(u.trueName) / 4),\'****\') userName';
		
		$list = $this
			->field($field)
			->join('m inner join __GOODS__ g on m.miaoshaId = g.miaoshaId')
			->join('inner join __MIAOSHA_HISTORY__ h on m.miaoshaId = h.miaoshaId')
			->join('left join __MEMBER__ u on u.uid = h.prizeUid')
			->where($filter)
			->order($order)
			->page($pageNo, $pageSize)
			->select();
//		echo $this->getLastSql();
		return $list;
	}
	
	public function get($miaoshaId = null, $qishu = -1) {
		if(!$miaoshaId) {
			$miaoshaId = I('id', $miaoshaId);
		}
		if($qishu <= 0) {
			$qishu = I('qishu', 0);
		}
		
		$filter = array('m.miaoshaId'	 => $miaoshaId);
		$field = 'goodsId, shopId, goodsName, marketPrice, goodsImg, goodsThums, shopPrice,jishijiexiao,
			m.miaoshaId, qishu, subTitle, xiangou, canyurenshu, zongrenshu, shengyurenshu, goumaicishu,
			if(m.miaoshaStatus < 2 and m.shengyurenshu = 0 and 
					m.`zongrenshu` = (select count(0) from cky_miaosha_code mc 
							where mc.miaoshaId=m.miaoshaId and mc.qishu=m.qishu), 2, m.miaoshaStatus) miaoshaStatus,
			unix_timestamp() * 1000 now,
			unix_timestamp(date_add(m.createTime, interval m.jishijiexiao hour))*1000 end,
			unix_timestamp(date_add(m.lastTime, interval 3 minute))*1000 last';
		$join = 'm inner join __GOODS__ g on m.miaoshaId = g.miaoshaId';
		$data = $this->join($join)->field($field)->where($filter)->find();
		$current = (int)$data['qishu'];
		$currentStatus = (int)$data['miaoshaStatus'];
//		echo dump($data);
		if(($current == $qishu || $qishu <= 0) && $currentStatus < 3) {
			$data['current'] = $current;
			$data['currentStatus'] = $currentStatus;
			return $data;
		}
		
		$filter = array('m.miaoshaId'	 => $miaoshaId, 'm.qishu' => $qishu <= 0 ? $current : $qishu);
		$field .= ', prizeCount, prizeCode, prizeUid, prizeNo, endTime, replace(concat(\'/\', u.ImagePath), \'/http://\', \'http://\') userImg, INSERT(u.trueName,ROUND(CHAR_LENGTH(u.trueName) / 2),ROUND(CHAR_LENGTH(u.trueName) / 4),\'****\') username';
		$db = M('MiaoshaHistory');
		$history = $db->join($join)->join('left join __MEMBER__ u on u.uid = m.prizeUid')->field($field)->where($filter)->find();
		$history['current'] = $current;
		$history['currentStatus'] = $currentStatus;
//		echo $db->getLastSql();
		if(empty($history)) {
			return $data;
		}
		
		return $history;
	}
}	
?>
