<?php
 namespace M\Model;
/**
 * ============================================================================
 * 粗卡云:
  
 * 联系方式:
 * ============================================================================
 * 商家服务类
 */
class ShopsModel extends BaseModel {
	
	 /**
	  * 商铺分页列表
	  */
     public function shops(){
     	$pageSize = 12;
		$pageNo = intval(I('pageNo', 1));
		$catId = intval(I('catId', 0));
		$areaId = intval(I('areaId', 0));
		$lng = floatval(I('lng', 0));
		$lat = floatval(I('lat', 0));
		$distance = floatval(I('distance', 0));
		$order = "shopId desc";
		$filter = 'shopStatus = 1 and shopFlag = 1';
		if($catId > 0) {
			$filter = $filter.' and catId='.$catId;
		}
		if($areaId > 0) {
			$filter = $filter.' and areaId3='.$areaId;
		}
		if($lng > 0 && $lat > 0 && $distance > 0) {
			$filter = $filter.sprintf(' AND SQRT(POW(%f - latitude, 2) + POW(%f - longitude, 2)) <= ',
				$lat, $lng).$distance;
			$order = 'distance';
		}
		
		$field = array('cky_shops.shopId','shopSn','shopName','shopImg','shopTel',
			'latitude','longitude','deliveryOff','shopAddress','cky_goods_cats.catName');
		if($lng > 0 && $lat > 0) {
			$field[sprintf('SQRT(POW(%f - latitude, 2) + POW(%f - longitude, 2))',
				$lat, $lng)] = 'distance';
		} else {
			$field['(0)'] = 'distance';
		}
		
		return $this->field($field)
	 				->join('cky_goods_cats on cky_goods_cats.catId = cky_shops.goodsCatId1')
					->where($filter)->order($order)->page($pageNo, $pageSize)->select();
	 }
	 
	 /**
	  * 获取商铺
	  */
	 public function detail() {
	 	$shopId = I('id');
		return $this->field('shopId, shopSn, shopName, shopImg, shopTel, shopAddress, serviceStartTime, serviceEndTime, deliveryStartMoney, deliveryCostTime, deliveryMoney, deliveryFreeMoney, latitude,longitude, mapLevel, shopDesc')
			->find($shopId);
	 }

	public function fast() {
		$pageSize = 10000; // 不需要翻页
		$pageNo = intval(I('pageNo', 1));
		$catId = intval(I('catId', 0));
		$areaId = intval(I('areaId', 0));
		$lng = floatval(I('lng', 0));
		$lat = floatval(I('lat', 0));
		$distance = floatval(I('distance', 0));
		$order = "shopId desc";
		$filter = 'shopStatus = 1 and shopFlag = 1';
		if($catId > 0) {
			$filter = $filter.' and catId='.$catId;
		}
		if($areaId > 0) {
			$filter = $filter.' and areaId3='.$areaId;
		}
		if($lng > 0 && $lat > 0 && $distance > 0) {
			$filter = $filter.sprintf(' AND SQRT(POW(%f - latitude, 2) + POW(%f - longitude, 2)) <= ',
				$lat, $lng).$distance;
			$order = 'distance';
		}
		
		$field = array('cky_shops.shopId','shopSn','shopName','shopImg','shopTel',
			'deliveryStartMoney', 'deliveryCostTime', 'deliveryMoney', 'deliveryFreeMoney',
			'totalScore', 'totalUsers',
			'latitude','longitude','deliveryOff','shopAddress');
		if($lng > 0 && $lat > 0) {
			$field[sprintf('SQRT(POW(%f - latitude, 2) + POW(%f - longitude, 2))',
				$lat, $lng)] = 'distance';
		} else {
			$field['(0)'] = 'distance';
		}
		
		return $this->field($field)
	 				->join('cky_shop_scores on cky_shop_scores.shopId = cky_shops.shopId')
					->where($filter)->order($order)->page($pageNo, $pageSize)->select();
	}
};
?>