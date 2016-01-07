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
     	$pageSize = 10;
		$pageNo = intval(I('pageNo', 1));
	 	$sql = sprintf('select s.shopId,shopSn,shopName,shopImg,shopTel,shopAtive,shopStatus,latitude,longitude,deliveryOff,shopAddress,gc.catName
	 		from __PREFIX__shops s join __PREFIX__goods_cats gc on gc.catId = s.goodsCatId1
	 	    where shopStatus=1 and shopFlag=1 order by shopId desc limit %d, %d', ($pageNo - 1) * $pageSize, $pageSize);
		return $this->query($sql);
	 }
	 
	 /**
	  * 获取商铺
	  */
	 public function detail() {
	 	$shopId = I('id');
		return $this->field('shopId, shopSn, shopName, shopImg, shopTel, shopAddress, serviceStartTime, serviceEndTime, latitude,longitude, mapLevel, shopDesc')
			->find($shopId);
	 }
};
?>