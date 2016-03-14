<?php
 namespace M\Model;
/**
 * ============================================================================
 * 粗卡云:
  
 * 联系方式:
 * ============================================================================
 * 行业服务类
 */
class GoodsCatsModel extends BaseModel {
	public function queryByParentId($parentId = 0, $shopId = 0) {
		if($parentId == 0) {
			$parentId = I('parentId', 0);
		}
		if($shopId == 0) {
			$shopId = I('shopId', 0);
		}
		
		$sql = 'select * from __GOODS_CATS__ gc where isShow = 1 and parentId = '.$parentId;
		if($shopId > 0) {
			$sql .= ' and exists(select 0 from __SHOP_PLATES__ sp where sp.shopId = '.$shopId.' and sp.plateId1 = gc.catId)';
		}
		
		$sql .= ' order by catSort';
		return $this->query($sql);
		
//		$map = array(
//			'parentId'	=> $parentId,
//			'isShow'		=> '1',
//			
//		);
		
//		return $this->where($map)->order('catSort')->select();
	}
	
	public function queryByParentkey($key) {
//		$m = M('goods_cats');
		$sql = "select gc.catId,gc.catName,gc.catKey from __PREFIX__goods_cats gp
		left join  __PREFIX__goods_cats gc on gc.parentId=gp.catId
		where gc.catFlag=1  and  gp.catkey='".$key."' order by gc.catSort asc";
		//echo $sql;
	  	$rs1 = $this->query($sql);
		return $rs1;
	}
};
?>