<?php
 namespace M\Model;
/**
 * ============================================================================
 * 粗卡云:
  
 * 联系方式:
 * ============================================================================
 * 商品服务类
 */
class GoodsModel extends BaseModel {
	public function goods() {
		$shopId = I('shopId');
		$pageSize = 12;
		$pageNo = intval(I('pageNo', 1));
		$map = array('shopId'	=> $shopId);
		
		// 过滤
		$shopCatId = intval(I('shopCatId', 0));
		if($shopCatId > 0) {
			$map['shopCatId1'] = $shopCatId;
		}
		
		// 排序
		$order = 'createTime desc';
		$sortType = intval(I('sortType', 0));
		switch($sortType) {
			case 1:
				$order = 'saleCount desc';
				break;
		}
		
		return $this->field('goodsId, goodsSn, goodsName, goodsThums, shopPrice, goodsUnit, saleCount, shopCatId1, goodsSpec')
			->where($map)->order($order)->page($pageNo, $pageSize)->select();
	}
	
	public function detail($queryType = 0) {
		$goodsId = I('id');
		$field = 'goodsId, shopId, goodsSn, goodsName, shopCatId1, goodsImg, goodsThums, shopPrice, goodsStock, saleCount';
		$join = null;
		switch($queryType) {
			case 0:
				$field .= ',goodsDesc';
				break;
			case 1: // 快餐商品详情
				$join = 'cky_shops on cky_shops.shopId = cky_goods.shopId';
				$field .= ',goodsSpec, cky_shops.shopId, deliveryStartMoney, deliveryFreeMoney, deliveryMoney, deliveryCostTime, serviceStartTime, serviceEndTime';
		}
		return $this->field($field)->join($join)->find($goodsId);
	}
	
	public function info($goodsId, $goodsAttrId) {
		$sql = "SELECT g.*,sp.shopId,sp.shopName,sp.deliveryFreeMoney,sp.deliveryMoney,sp.deliveryStartMoney,sp.isInvoice,sp.serviceStartTime startTime,sp.serviceEndTime endTime,sp.deliveryType 
				FROM __PREFIX__goods g, __PREFIX__shops sp 
				WHERE g.shopId = sp.shopId AND g.goodsId = $goodsId AND g.isSale=1 AND g.goodsFlag = 1 AND g.goodsStatus = 1";
		$rs = $this->queryRow($sql);
	    if(!empty($rs) && $rs['attrCatId']>0){
        	$sql = "select ga.id,ga.attrPrice,ga.attrStock,a.attrName,ga.attrVal,ga.attrId from __PREFIX__attributes a,__PREFIX__goods_attributes ga
			        where a.attrId=ga.attrId and a.catId=".$rs['attrCatId']." 
			        and ga.goodsId=".$rs['goodsId']." and id=".$goodsAttrId;
			$priceAttrs = $this->query($sql);
			if(!empty($priceAttrs)){
				$rs['attrId'] = $priceAttrs[0]['attrId'];
				$rs['goodsAttrId'] = $priceAttrs[0]['id'];
				$rs['attrName'] = $priceAttrs[0]['attrName'];
				$rs['attrVal'] = $priceAttrs[0]['attrVal'];
				$rs['shopPrice'] = $priceAttrs[0]['attrPrice'];
				$rs['goodsStock'] = $priceAttrs[0]['attrStock'];
			}
        }
		return $rs;
	}
	
	public function guess() {
		$pageNo = 1;
		$pageSize = 20;
		return $this->field('goodsId, goodsSn, goodsName, goodsThums, marketPrice, shopPrice, goodsUnit, saleCount, shopCatId1, goodsSpec')
			->order('createTime')->page($pageNo, $pageSize)->select();
	}
};
?>