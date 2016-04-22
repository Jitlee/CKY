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
		
		$filter = array();
		$filter['isSale'] = 1;
		$filter['miaoshaStatus'] = array('lt', 2);
		
		if($type == 0) {
			$filter['jishijiexiao'] = 0;
		} else if($type == 1) {
			$filter['jishijiexiao'] = array('gt', 0);
		}
		
		$order = "goodsId desc"; // 最新
		switch($sortType) {
			case 1: // 人气
				$order .= ", goodsId desc";
				break;
			case 3:  // 剩余人数
				$order .= ", shengyurenshu asc";
				break;
			case 4: // 价值
				$order .= ", marketPrice desc";
				break;
			case 5: // 价值
				$order .= ", marketPrice asc";
				break;
			case 6: // 剩余时间
				$order .= "(timestamp + jishijiexiao * 3600) asc";
				break;
			default: 
				break;
		}
		
		$field = 'g.goodsId, g.goodsName, g.goodsImg, g.goodsThums, g.marketPrice, g.shopPrice, g.goodsSpec,
				m.miaoshaId, m.qishu, m.zongrenshu, m.canyurenshu, m.shengyurenshu, m.jishijiexiao, m.xiangou,
				m.miaoshaStatus, m.subTitle, m.createTime';
				
		if($type == 1) {
			$field .= ', unix_timestamp(m.createTime) timestamp';
		}
		
		$list = $this
			->field($field)
			->join('m inner join __GOODS__ g on m.miaoshaId = g.miaoshaId')
			->where($filter)
			->order($order)
			->page($pageNum, $pageSize)
			->select();
//		echo $this->getLastSql();
		return $list;
	}

	public function queryHistory() {
		$pageSize = (int)I('pageSize', 20);
		$pageNo = (int)I('pageNo', 1);
		$sortType = (int)I('sortType', 0);
		
		$filter = array();
		$order = "endTime desc"; // 最新
		
		$field = 'g.goodsId, g.goodsName, g.goodsImg, g.goodsThums, g.marketPrice, g.shopPrice, g.goodsSpec,
				h.miaoshaId, h.qishu, h.subTitle, h.endTime, h.prizeCode,h.prizeCount, 
				h.prizeUid, INSERT(u.trueName,ROUND(CHAR_LENGTH(u.trueName) / 2),ROUND(CHAR_LENGTH(u.trueName) / 4),\'****\') userName';
		
		$list = $this
			->field($field)
			->join('m inner join __GOODS__ g on m.miaoshaId = g.miaoshaId')
			->join('inner join __MIAOSHA_HISTORY__ h on m.miaoshaId = h.miaoshaId and ((h.qishu = m.qishu-1 and m.miaoshaStatus < 2) or (h.qishu = m.qishu and m.miaoshaStatus = 2))')
			->join('inner join __MEMBER__ u on u.uid = h.prizeUid')
			->where($filter)
			->order($order)
			->page($pageNum, $pageSize)
			->select();
		return $list;
	}
	
	public function get($miaoshaId = null, $qishu = 0) {
		$miaoshaId = I('id', $miaoshaId);
		$qishu = I('qishu', $qishu);
		$map = array('m.miaoshaId'	 => $miaoshaId);
		$field = 'goodsId, shopId, goodsName, marketPrice, goodsImg, goodsThums, shopPrice, miaoshaStatus,'.
			'm.miaoshaId, qishu, subTitle, xiangou, canyurenshu, zongrenshu, shengyurenshu, goumaicishu, UNIX_TIMESTAMP() time,
			(jishijiexiao > 0 and miaoshaStatus < 2 and now() > adddate(m.createTime,interval jishijiexiao HOUR)) jiexiao';
		$join = 'inner join __GOODS__ g on m.miaoshaId = g.miaoshaId';
		$m = $this;
		
		$list = null;
		if($qishu > 0) { // 查看历史
			$field .= ', prizeCount, prizeCode, prizeUid, endTime, replace(concat(\'/\', u.ImagePath), \'/http://\', \'http://\') userImg, INSERT(u.trueName,ROUND(CHAR_LENGTH(u.trueName) / 2),ROUND(CHAR_LENGTH(u.trueName) / 4),\'****\') username';
			$map['qishu'] = $qishu;
			$m = M('MiaoshaHistory');
			$m->join('m left join __MEMBER__ u on u.uid = m.prizeUid');
		} else { // 查看主表纪录
			$join = 'm '.$join;
		}
		$data = $m->join($join)->field($field)->where($map)->find();
//		echo $m->getLastSql();
		if($qishu == 0 && (int)$data['miaoshaStatus'] == 3) {
			return $this->get($data['miaoshaId'], (int)$data['qishu']);
		}
		return $data;
	}
}	
?>
