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
			$filter = $filter.' and plateId2='.$catId;
		} else {
			$filter = $filter.' and plateId1=4';
		}
		if($areaId > 0) {
			$filter = $filter.' and areaId3='.$areaId;
		}
		if($lng > 0 && $lat > 0 && $distance > 0) {
			$filter = $filter.sprintf(' AND SQRT(POW(%f - latitude, 2) + POW(%f - longitude, 2)) <= ',
				$lat, $lng).$distance;
			$order = 'distance';
		}
		
		$field = "s.shopId,shopSn,shopName,replace(shopImg, '.', '_thumb.') shopImg,shopTel,
			latitude,longitude,deliveryOff,shopAddress,gc.catName";
		if($lng > 0 && $lat > 0) {
			$field .= sprintf(',SQRT(POW(%f - latitude, 2) + POW(%f - longitude, 2))',
				$lat, $lng).' distance';
		} else {
			$field .= ',0 distance';
		}
		
		$list = $this->field($field)
			->join('s INNER JOIN __SHOP_PLATES__ sp on sp.shopId = s.shopId')
			->join('INNER JOIN __GOODS_CATS__ gc on gc.catId = sp.plateId2')
			->where($filter)->order($order)->page($pageNo, $pageSize)->select();
//		echo $this->getLastSql();
		return $list;
	 }
	 
	 /**
	  * 获取商铺
	  */
	 public function detail($shopId = 0) {
	 	if($shopId == 0) {
	 		$shopId = I('id');
		}
		$field = "s.shopId, shopSn, shopName, replace(s.shopImg, '.', '_thumb.') shopImg, shopTel, shopAddress, serviceStartTime, serviceEndTime, deliveryStartMoney, deliveryCostTime, deliveryMoney, deliveryFreeMoney, latitude,longitude, mapLevel, shopDesc, shopWishes, shopProfile, ROUND(`totalScore`/3/`totalUsers`, 1) score";
		$join = 's left join __SHOP_SCORES__ ss on s.shopId = ss.shopId';
		$map = array('s.shopId' => $shopId);
		return $this->field($field)->join($join)->where($map)->find();
	 }

	public function fast() {
		$pageSize = 200; // 不需要翻页
		$pageNo = intval(I('pageNo', 1));
		$catId = intval(I('catId', 0));
		$areaId = intval(I('areaId', 0));
		$sortType = intval(I('sortType', 0));
		$lng = floatval(I('lng', 0));
		$lat = floatval(I('lat', 0));
		$order = "shopId desc";
		$filter = 'shopStatus = 1 and shopFlag = 1';
		if($catId > 0) {
			$filter = $filter.' and plateId2='.$catId;
		} else {
			$filter = $filter.' and plateId1=1';
		}
		if($areaId > 0) {
			$filter = $filter.' and areaId3='.$areaId;
		}
		
		switch($sortType) {
			case 1: // 人气
				$order = "totalUsers desc";
				break;
			case 2: // 评价
				$order = "totalScore desc";
				break;
			case 3: // 最近
				$order = "distance";
				break;
			default:
				break;
		}
		
		$field = "s.shopId,shopSn,shopName,replace(s.shopImg, '.', '_thumb.') shopImg,shopTel,
			deliveryStartMoney, deliveryCostTime, serviceStartTime, serviceEndTime, deliveryMoney, deliveryFreeMoney,
			totalScore, totalUsers,
			latitude,longitude,deliveryOff,shopAddress";
		if($lng > 0 && $lat > 0) {
			$field .= sprintf(',SQRT(POW(%f - latitude, 2) + POW(%f - longitude, 2))',
				$lat, $lng).' distance';
		} else {
			$field .= ',0 distance';
		}
		
		$list = $this->field($field)
			->join('s INNER JOIN __SHOP_SCORES__ ss on ss.shopId = s.shopId')
			->join('INNER JOIN __SHOP_PLATES__ sp on sp.shopId = s.shopId')
			->join('INNER JOIN __GOODS_CATS__ gc on gc.catId = sp.plateId2')
			->where($filter)->order($order)->page($pageNo, $pageSize)->select();
//		echo $this->getLastSql();
		return $list;
	}
};
?>