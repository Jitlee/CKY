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
		
		$field = array('s.shopId','shopSn','shopName','shopImg','shopTel',
			'deliveryStartMoney', 'deliveryCostTime', 'serviceStartTime', 'serviceEndTime', 'deliveryMoney', 'deliveryFreeMoney',
			'totalScore', 'totalUsers',
			'latitude','longitude','deliveryOff','shopAddress');
		if($lng > 0 && $lat > 0) {
			$field[sprintf('SQRT(POW(%f - latitude, 2) + POW(%f - longitude, 2))',
				$lat, $lng)] = 'distance';
		} else {
			$field['(0)'] = 'distance';
		}
		
		return $this->field($field)
					->join('s INNER JOIN __SHOP_SCORES__ ss on ss.shopId = s.shopId')
	 				->join('INNER JOIN __SHOP_PLATES__ sp on sp.shopId = s.shopId')
					->join('INNER JOIN __GOODS_CATS__ gc on gc.catId = sp.plateId2')
					->where($filter)->order($order)->page($pageNo, $pageSize)->select();
	}
};
?>