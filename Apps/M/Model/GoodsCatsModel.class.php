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
		
		$sql = 'select * from __GOODS_CATS__ gc where isShow = 1 and catFlag=1  and parentId = '.$parentId;
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
	
	// 热门市场
	public function queryMallCats() {
		$pageNo = (int)I('pageNo', 1);
		$pageSize = (int)I('pageSize', 10);
		$catId = (int)I('catId', 0);
		$filter = array('c1.isShow'=>1,
			'c1.catFlag'=>1
		);
		if($catId > 0) {
			$filter['c2.catId'] = $catId;
		}
		
		return $this->field('c1.catId, c1.catName, replace(c1.catIcon, \'.\', \'_thumb.\') catIcon')
			->join('c1 inner join __GOODS_CATS__ c2 on c1.parentId = c2.catid')
			->join('inner join __GOODS_CATS__ c3 on c2.parentId = c3.catid and c3.catkey = \'mall\'')
			->where($filter)
			->order('c1.catSort desc, c1.catId desc')
			->page($pageNo, $pageSize)
			->select();
	}
};
?>