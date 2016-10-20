<?php
 namespace Home\Model;
/**
 * ============================================================================
 * 粗卡云:
  
 * 联系方式:
 * ============================================================================
 * 商品拼团扩展表
 */
class GoodsGroupModel extends BaseModel {
	public function goods() {
		$shopId = $shopId == 0 ? I('shopId') : $shopId;
		$pageSize = (int)I('pageSize', 15);
		$pageNo = intval(I('pageNo', 1));
		$order = 'g.createTime desc';
		$m = M('Goods');
		$map = array(
			'g.shopId'	=> $shopId,
			'g.goodsId'	=> array('exp', 'not exists(select 0 from cky_goods_group gg where g.goodsId = gg.goodsId and now() between gg.groupStartTime and gg.groupEndTime)')
		);
		
		$goodsName = I('goodsName');
		if(!empty($goodsName)) {
			$map['g.goodsName'] = array('like', "%$goodsName%");
		}
		
		return $m->field('g.goodsId, goodsSn, goodsName, goodsThums, goodsStock, shopPrice, goodsUnit')
			->join('g INNER JOIN __SHOPS__ s on g.shopId = s.shopId')
			->where($map)->order($order)->page($pageNo, $pageSize)->select();
	}
	
	public function lst($shopId = 0) {
		$shopId = $shopId == 0 ? I('shopId') : $shopId;
		$pageSize = (int)I('pageSize', 15);
		$pageNo = intval(I('pageNo', 1));
		$order = 'gg.createTime desc';
		$map = array(
			'g.shopId'	=> $shopId
		);
		return $this->field('g.goodsId, goodsSn, goodsName, goodsThums, goodsStock, shopPrice, goodsUnit,
			 gg.groupPrice, gg.groupNumbers, gg.groupPreNumbers, gg.groupStartTime, gg.groupEndTime')
			->join('g INNER JOIN __GOODS_GROUP__ gg on g.goodsId = gg.goodsId')
			->join('INNER JOIN __SHOPS__ s on g.shopId = s.shopId')
			->where($map)->order($order)->page($pageNo, $pageSize)->select();
	}
	
     public function insert() {
     	$rd = array('status'=>1, 'message'=> '操作成功');
     	$goodsId = I('goodsId');
     	$now = date('y-m-d-H-i-s');
     	$where = array(
     		'goodsId'	=> $goodsId,
     		'groupStartTime' => array('elt', $now),
     		'groupStartTime' => array('egt', $now)
     	);
     	$count = $this->where($where)->count();
     	if($count > 0) {
     		$rd['status'] = -1;
     		$rd['message'] = '此商品已经参与拼团活动！';
     	} else {
     		$data['goodsId'] = $goodsId;
     		$data['groupPreNumbers'] = I('groupPreNumbers');
     		$data['groupMaxNumbers'] = I('groupMaxNumbers');
     		$data['groupPrice'] = I('groupPrice');
     		$data['groupStartTime'] = I('groupStartTime');
     		$data['groupEndTime'] = I('groupEndTime');
     		if(!$this->add($data)) {
     			$rd['status'] = -2;
     			$rd['message'] = '保存数据失败！';
     		}
     	}
     	return $rd;
     }
     
     public function update() {
     	$rd = array('status'=>1, 'message'=> '操作成功');
     	$goodsGroupId = I('goodsGroupId');
     	$where = array(
     		'goodsId'	=> $goodsId,
     		'goodsNumbers' => array('gt', 0)
     	);
     	$count = $this->where($where)->count();
     	if($count > 0) {
     		$rd['status'] = -1;
     		$rd['message'] = '正在活动中的商品不允许修改！';
     	} else {
	 		$data['groupPreNumbers'] = I('groupPreNumbers');
	 		$data['groupMaxNumbers'] = I('groupMaxNumbers');
	 		$data['groupPrice'] = I('groupPrice');
	 		$data['groupStartTime'] = I('groupStartTime');
	 		$data['groupEndTime'] = I('groupEndTime');
	 		if(!$this->where($goodsGroupId)->save($data)) {
	 			$rd['status'] = -2;
	 			$rd['message'] = '更新数据失败！';
	 		}
	 	}
     	return $rd;
     }
     
     public function remove() {
     	$rd = array('status'=>1, 'message'=> '操作成功');
     	$m = M('GoodsGroup');
     	$goodsGroupId = I('goodsGroupId');
     	$where = array(
     		'goodsId'	=> $goodsId,
     		'groupStartTime' => array('elt', $now),
     		'groupStartTime' => array('egt', $now)
     	);
     	$count = $m->join(' gg inner join __GROUP__ g where gg.goodsGroupId = g.goodsGroupId')->count();
     	if($count > 0) {
     		$rd['status'] = -1;
     		$rd['message'] = '正在活动中的商品不允许删除！';
     	} else {
	 		if(!$m->where($goodsGroupId)->delete) {
	 			$rd['status'] = -2;
	 			$rd['message'] = '删除数据失败！';
	 		}
	 	}
     	return $rd;
     }
};
?>