<?php
 namespace M\Model;
/**
 * ============================================================================
 * 粗卡云:
  
 * 联系方式:
 * ============================================================================
 * 商家分类服务类
 */
class RecommendModel extends BaseModel {
	
	public function fast() {
		$pageSize = 10000; // 不需要翻页
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
				$order = "rc.sort,totalUsers desc";
				break;
			case 2: // 评价
				$order = "rc.sort,totalScore desc";
				break;
			case 3: // 最近
				$order = "rc.sort, distance";
				break;
			default:
				break;
		}
		
		$field = 'cky_shops.shopId,shopSn,shopName,shopImg,shopTel,
			deliveryStartMoney, deliveryCostTime, serviceStartTime, serviceEndTime, deliveryMoney, deliveryFreeMoney,
			totalScore, totalUsers,latitude,longitude,deliveryOff,shopAddress';
		if($lng > 0 && $lat > 0) {
			$field=$field.sprintf(',SQRT(POW(%f - latitude, 2) + POW(%f - longitude, 2)) AS distance',$lat, $lng);
		} else {
			$field=$field.',0 AS distance';
		}		
		$Model = M('shops');
		return $Model->field($field)
					->join('INNER JOIN cky_shop_scores ss on ss.shopId = cky_shops.shopid')
	 				->join('INNER JOIN cky_shop_plates sp on sp.shopId = cky_shops.shopId')
					->join('INNER JOIN cky_goods_cats  gc on gc.catId = sp.plateId2')
					->join('INNER JOIN cky_recommend   rc  on cky_shops.shopid=rc.shopsid')
					->where($filter)->order($order)->page($pageNo, $pageSize)->select();
	}
};
?>