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
	  * 分页列表
	  */
     public function queryByPage(){
	 	$sql = "select s.shopId,shopSn,shopName,shopImg,shopTel,shopAtive,shopStatus,IFNULL(c.shopKeywords, gc.catName) shopKeywords
	 		from __PREFIX__shops s join __PREFIX__goods_cats gc on gc.catId = s.goodsCatId1
	 		left join __PREFIX__shop_configs c on s.shopId = c.shopId
	 	    where shopStatus=1 and shopFlag=1 order by shopId desc";
		return $this->query($sql);
	 }
};
?>