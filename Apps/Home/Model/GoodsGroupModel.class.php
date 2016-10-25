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
		$shopId=(int)session('RTC_USER.shopId');
		$shopCatId1 = (int)I('shopCatId1',0);
		$shopCatId2 = (int)I('shopCatId2',0);
		$goodsName = I('goodsName');
		$like = empty($goodsName) ? '' : " and g.goodsName like '%$goodsName%'";
		$cat1 = $shopCatId1 == 0 ? '' : 'and g.shopCatId1='.$shopCatId1;
		$cat2 = $shopCatId2 == 0 ? '' : 'and g.shopCatId2='.$shopCatId2;
		$sql = "select g.goodsId, goodsSn, goodsName, goodsThums, goodsStock, saleCount, shopPrice, goodsUnit from __GOODS__ g where g.shopId='$shopId' and not exists(select 0 from cky_goods_group gg
		 where g.goodsId = gg.goodsId and now() between gg.groupStartTime and gg.groupEndTime)
		 $like $cat1 $cat2 order by g.createTime desc";
		return $this->pageQuery($sql);
	}
	
	public function lst($shopId = 0) {
		$shopId=(int)session('RTC_USER.shopId');
		$shopCatId1 = (int)I('shopCatId1',0);
		$shopCatId2 = (int)I('shopCatId2',0);
		$goodsName = I('goodsName');
		$like = empty($goodsName) ? '' : " and g.goodsName like '%$goodsName%'";
		$cat1 = $shopCatId1 == 0 ? '' : 'and g.shopCatId1='.$shopCatId1;
		$cat2 = $shopCatId2 == 0 ? '' : 'and g.shopCatId2='.$shopCatId2;
		$sql = "select g.goodsId, goodsSn, goodsName, goodsThums, goodsStock, shopPrice, goodsUnit,
			 gg.groupGoodsId, gg.groupPrice, gg.groupNumbers, gg.groupPreNumbers,
			 DATE_FORMAT(gg.groupStartTime, '%Y-%m-%d') AS groupStartTime, DATE_FORMAT(gg.groupEndTime, '%Y-%m-%d') AS groupEndTime 
			 from __GOODS__ g INNER JOIN __GOODS_GROUP__ gg on g.goodsId = gg.goodsId
			 INNER JOIN __SHOPS__ s on g.shopId = s.shopId
			 where g.shopId='$shopId'
		 	$like $cat1 $cat2 order by gg.createTime desc";
		return $this->pageQuery($sql);
	}
	
	public function detail() {
		$groupGoodsId = (int)I('groupGoodsId',0);
		$object = $this->field('groupGoodsId, groupPrice, groupNumbers, groupPreNumbers,
			 DATE_FORMAT(groupStartTime, \'%Y-%m-%d\') AS groupStartTime, DATE_FORMAT(groupEndTime, \'%Y-%m-%d\') AS groupEndTime')->find($groupGoodsId);
		return $object;
	}
	
     public function insert() {
     	$rd = array('status'=>1, 'msg'=> '操作成功');
     	$goodsId = I('goodsId');
     	$now = date('y-m-d-H-i-s');
     	$where = array(
     		'goodsId'	=> $goodsId,
     		'groupStartTime' => array('elt', $now),
     		'groupEndTime' => array('egt', $now)
     	);
     	$count = $this->where($where)->count();
     	if($count > 0) {
     		$rd['status'] = -1;
     		$rd['msg'] = '此商品已经参与拼团活动！';
     	} else {
     		$data['goodsId'] = $goodsId;
	 		$data['groupPreNumbers'] = (int)I('groupPreNumbers');
	 		$data['groupMaxNumbers'] = (int)I('groupMaxNumbers');
	 		$data['groupPrice'] = (float)I('groupPrice');
	 		$data['groupStartTime'] = I('groupStartTime');
	 		$data['groupEndTime'] = I('groupEndTime');
	 		$data['groupNumbers'] = (int)I('groupNumbers');
     		if(!$this->add($data)) {
     			$rd['status'] = -2;
     			$rd['msg'] = '保存数据失败！';
     		}
     	}
     	return $rd;
     }
     
     public function update() {
     	$rd = array('status'=>1, 'msg'=> '操作成功');
     	$groupGoodsId = I('groupGoodsId');
     	$where = array(
     		'goodsId'	=> $goodsId,
     		'groupNumbers' => array('gt', 0)
     	);
     	$count = $this->where($where)->count();
     	if($count > 0) {
     		$rd['status'] = -1;
     		$rd['msg'] = '正在活动中的商品不允许修改！';
     	} else {
	 		$data['groupPreNumbers'] = (int)I('groupPreNumbers');
	 		$data['groupMaxNumbers'] = (int)I('groupMaxNumbers');
	 		$data['groupPrice'] = (float)I('groupPrice');
	 		$data['groupStartTime'] = I('groupStartTime');
	 		$data['groupEndTime'] = I('groupEndTime');
	 		$data['groupNumbers'] = (int)I('groupNumbers');
	 		if(!$this->where($groupGoodsId)->save($data)) {
	 			$rd['status'] = -2;
	 			$rd['msg'] = '更新数据失败！';
	 		}
	 	}
     	return $rd;
     }
     
     public function remove() {
     	$rd = array('status'=>1, 'msg'=> '操作成功');
     	$m = M('GoodsGroup');
     	$groupGoodsId = I('groupGoodsId');
     	$where = array(
     		'goodsId'	=> $goodsId,
     		'groupStartTime' => array('elt', $now),
     		'groupStartTime' => array('egt', $now)
     	);
     	$count = $m->join(' gg inner join __GROUP__ g where gg.groupGoodsId = g.groupGoodsId')->count();
     	if($count > 0) {
     		$rd['status'] = -1;
     		$rd['msg'] = '正在活动中的商品不允许删除！';
     	} else {
	 		if(!$m->where($groupGoodsId)->delete) {
	 			$rd['status'] = -2;
	 			$rd['msg'] = '删除数据失败！';
	 		}
	 	}
     	return $rd;
     }
};
?>