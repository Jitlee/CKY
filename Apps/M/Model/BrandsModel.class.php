<?php
 namespace M\Model;
/**
 * ============================================================================
 * 粗卡云:
  
 * 联系方式:
 * ============================================================================
 * 品牌服务类
 */
class BrandsModel extends BaseModel {
	
	// 根据goodsCatId3查询品牌
    public function queryByCatId3($catId = 0) {
    		if($catId == 0) {
    			$catId = (int)I('catId');
    		}
    		$sql = "select brandId, brandIco brandIcon, brandName from __BRANDS__ b where brandFlag=1 and exists(select 0 from __GOODS__ g where g.brandId=b.brandId and g.goodsCatId3=$catId)";
		return $this->query($sql);
    }
};
?>