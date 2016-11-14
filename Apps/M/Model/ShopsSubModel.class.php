<?php
 namespace M\Model;
/**
 * ============================================================================
 * 粗卡云:
  
 * 联系方式:
 * ============================================================================
 * 品牌服务类
 */
class ShopsSubModel extends BaseModel {
	
	 
	/**
	  * 获取列表
	  */
	public function queryByList($shopId){
	     $m = M('shopssub');
		 $rs = $m->where("shopId=$shopId")->select();
		 return $rs;
	}
	
	public function getitem(){
	 	$m = M('shopssub');
	 	$id = (int)I('id',0);
		$shop = $m->where("shopsubId=".$id)->find();
		return $shop;
	}
	
	public function cats(){
		$shopId=I("shopId");
		$shopsubId=I("shopsubId");
		$sql="SELECT DISTINCT sc.`catId` ,sc.`catName`   FROM `cky_goods`  g
		INNER JOIN cky_shops_cats sc on g.`shopCatId2` =sc.`catId` 
		WHERE g.`shopId` =$shopId and g.`shopssubId` =$shopsubId";
		return M()->query($sql);
	}
	
	public function pageByShopsId($pageNo = 0, $pageSize = 0) {
		if($pageNo == 0) {
			$pageSize = (int)I('pageSize', 6);
			$pageNo = (int)I('pageNo', 1);
//			$catId = (int)I('catId');
		}
		
		
		$field = 'g.goodsId, goodsName, marketPrice, shopPrice, goodsThums, isHot, saleCount,s.shopId, s.shopName, replace(s.shopImg, \'.\', \'_thumb.\') shopThums,
			g.goodsStock,goodsUnit, g.shopCatId1,
			s.provideBox, s.boxMoney, s.deliveryMoney, s.deliveryFreeMoney,s.serviceStartTime, s.serviceEndTime,s.deliveryCostTime,
			mam.mactmid activeId, mam.priceMode activeType, mam.amount activeAmount';
		
		$filter = array(
			'g.shopId'			=> array('gt', 0),
			'isSale'			=> 1,
			'goodsFlag'		=> 1,
		);
		
		$catId = (int)I('shopCatId', 0);
		if($catId>0){
			$filter['g.shopCatId2'] = $catId;
		} 
		
		$shopssubId = (int)I('shopssubId', 0);	
		$filter['g.shopssubId'] = $shopssubId;
 
		
		$join = 'g inner join __SHOPS__ s on g.shopId = s.shopId';
		$leftJoin1 = 'left join __MALL_ACTIVITYGOODS__ mag on mag.goodsId = g.goodsId';
		$leftJoin2 = 'left join __MALL_ACTIVITYM__ mam on mag.mactmid = mam.mactmid';

		$order = 'saleCount desc, isBest desc, isHot desc, goodsId desc'; 
		
		$m = M('goods');
		$list = $m->field($field)->join($join)
			->join($leftJoin1)
			->join($leftJoin2)
			->where($filter)->order($order)->page($pageNo, $pageSize)->select();
//		echo $this->getLastSql();
		return $list;
	}
	
}