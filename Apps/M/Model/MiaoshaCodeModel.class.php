<?php
 namespace M\Model;
/**
 * ============================================================================
 * 粗卡云:
  
 * 联系方式:
 * ============================================================================
 * 秒杀服务类
 */
class MiaoshaCodeModel extends BaseModel {
	
	public function lst() {
		$pageSize = (int)I('pageSize', 50);
		$pageNo = (int)I('pageNo', 1);
		$mmid = (int)I('mmid', 0);
		$uid = (int)I('uid', 0);
		$miaoshaId = I('id');
		$qishu = (int)I('qishu', 0);
		
		$order = "mcid asc"; // 最新
		$field = 'mcid, miaoshaCode';
		$filter = array();
		if($mmid > 0) {
			$filter['mmid'] = $mmid;
		} else {
			$filter['uid'] = $uid;
			$filter['miaoshaId'] = $miaoshaId;
			$filter['qishu'] = $qishu;
		}
				
		$list = $this
			->field($field)
			->where($filter)
			->order($order)
			->page($pageNum, $pageSize)
			->select();
//		echo $this->getLastSql();
		return $list;
	}
	
	// 统计所有数量
	public function cnt() {
		$mmid = (int)I('mmid', 0);
		$uid = (int)I('uid', 0);
		$miaoshaId = I('id');
		$qishu = (int)I('qishu', 0);
		
		$filter = array();
		if($mmid > 0) {
			$filter['mmid'] = $mmid;
		} else {
			$filter['uid'] = $uid;
			$filter['miaoshaId'] = $miaoshaId;
			$filter['qishu'] = $qishu;
		}
				
		return $this->where($filter)->count();
	}
	
}	
?>
