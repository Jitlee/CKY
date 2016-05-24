<?php
 namespace M\Model;
/**
 * ============================================================================
 * 粗卡云:
  
 * 联系方式:
 * ============================================================================
 * 商品服务类
 */
class GoodsModel extends BaseModel {
	public function goods() {
		$shopId = I('shopId');
		$pageSize = (int)I('pageSize', 12);
		$pageNo = intval(I('pageNo', 1));
		$map = array('g.shopId'	=> $shopId
			,"g.isSale"=>1
			,"g.goodsFlag"=>1
		);
		$map['goodsStock']  = array('gt',0);//库存大于0
		 
		// 过滤
		$shopCatId = intval(I('shopCatId', 0));
		if($shopCatId > 0) {
			$map['shopCatId1'] = $shopCatId;
		}
		
		// 排序
		$order = 'createTime desc';
		$sortType = intval(I('sortType', 0));
		switch($sortType) {
			case 1:
				$order = 'saleCount desc';
				break;
		}
		
		return $this->field('g.goodsId, goodsSn, goodsName, goodsThums, shopPrice, goodsUnit, saleCount, shopCatId1, goodsSpec, round(totalScore / 3.0 / totalUsers, 2) score')
			->join('g LEFT JOIN __GOODS_SCORES__ gc on g.goodsId = gc.goodsId')
			->where($map)->order($order)->page($pageNo, $pageSize)->select();
	}
	
	public function detail() {
		$goodsId = I('id');
		$field = 'g.goodsId, g.shopId, goodsSn, goodsName, shopCatId1, g.goodsCatId1, goodsImg, goodsThums, shopPrice, goodsStock, saleCount, goodsDesc,goodsSpec, shopName, deliveryStartMoney, deliveryFreeMoney, deliveryMoney, deliveryCostTime, serviceStartTime, serviceEndTime'
			.", mam.mactmid activeId, mam.priceMode activeType, mam.amount activeAmount";
		$join = 'g inner join __SHOPS__ s on s.shopId = g.shopId';
		$leftJoin1 = 'left join __MALL_ACTIVITYGOODS__ mag on mag.goodsId = g.goodsId';
		$leftJoin2 = 'left join __MALL_ACTIVITYM__ mam on mag.mactmid = mam.mactmid';
		$data = $this->field($field)->join($join)->join($leftJoin1)->join($leftJoin2)->where(array('g.goodsId'=> $goodsId))->find();
	
		return $data;
	}
	
	public function info($goodsId, $goodsAttrId) {
		$sql = "SELECT g.*,sp.shopId,sp.shopName,sp.deliveryFreeMoney,sp.deliveryMoney,sp.deliveryStartMoney,sp.isInvoice,sp.serviceStartTime startTime,sp.serviceEndTime endTime,sp.deliveryType
					,mam.mactmid activeId, mam.priceMode activeType, mam.amount activeAmount
				FROM __PREFIX__goods g inner join __PREFIX__shops sp on g.shopId = sp.shopId
					left join __MALL_ACTIVITYGOODS__ mag on mag.goodsId = g.goodsId
					left join __MALL_ACTIVITYM__ mam on mag.mactmid = mam.mactmid
				WHERE g.goodsId = $goodsId AND g.isSale=1 AND g.goodsFlag = 1 AND g.goodsStatus = 1";
		$rs = $this->queryRow($sql);
//		echo $this->getLastSql();
	    if(!empty($rs) && $rs['attrCatId']>0){
        	$sql = "select ga.id,ga.attrPrice,ga.attrStock,a.attrName,ga.attrVal,ga.attrId from __PREFIX__attributes a,__PREFIX__goods_attributes ga
			        where a.attrId=ga.attrId and a.catId=".$rs['attrCatId']." 
			        and ga.goodsId=".$rs['goodsId']." and id=".$goodsAttrId;
			$priceAttrs = $this->query($sql);
			if(!empty($priceAttrs)){
				$rs['attrId'] = $priceAttrs[0]['attrId'];
				$rs['goodsAttrId'] = $priceAttrs[0]['id'];
				$rs['attrName'] = $priceAttrs[0]['attrName'];
				$rs['attrVal'] = $priceAttrs[0]['attrVal'];
				$rs['shopPrice'] = $priceAttrs[0]['attrPrice'];
				$rs['goodsStock'] = $priceAttrs[0]['attrStock'];
			}
        }
		return $rs;
	}
	
	public function guess() {
		$pageNo = 1;
		$pageSize = 20;
		$map = array("s.shopFlag"=>1
			,"g.isSale"=>1
			,"g.goodsFlag"=>1
			,"gl.likestatus"=>1
		);
		$map['goodsStock']  = array('gt',0);//库存大于0
		
		return $this->field('g.goodsId, g.goodsSn, g.goodsName, g.goodsThums, g.marketPrice, g.shopPrice, g.goodsUnit, g.saleCount, g.shopCatId1, g.goodsSpec')
			->join('g inner join __SHOPS__ s on s.shopId = g.shopId')
			->join(' inner join cky_goods_like gl on gl.goodsId = g.goodsId')
			->where($map)->order('gl.sort desc,g.createTime')->page($pageNo, $pageSize)->select();
	}
	
	public function reduceStock($goodsId, $count) {
		$data = array(
			'goodsStock'	 	=> array('exp', '`goodsStock` - '.$count)
		);
		$map = array(
			'goodsId'		=> $goodsId
		);
		$rst = $this->where($map)->save($data);
//		echo $this->getLastSql();
		return $rst;
	}
	
	public function pageTop($pageNo = 0, $pageSize = 0, $catId = 0) {
		if($pageNo == 0) {
			$pageSize = (int)I('pageSize', 20);
			$pageNo = (int)I('pageNo', 1);
			$catId = (int)I('catId');
		}
		$catId3 = (int)I('catId3', 0);
		$brands = I('brands');
		
		$field = "g.goodsId, goodsName, marketPrice, shopPrice, goodsThums, isHot, saleCount,s.shopId, s.shopName, replace(s.shopImg, '.', '_thumb.') shopThums"
			.", g.goodsStock,goodsUnit, g.shopCatId1"
			.", mam.mactmid activeId, mam.priceMode activeType, mam.amount activeAmount";
		$filter = array(
			'g.shopId'			=> array('gt', 0),
			'isSale'			=> 1,
			'goodsFlag'		=> 1,
			//'g.goodsCatId1'	=> $catId
		);
		if($catId>0){
			$filter['g.goodsCatId1'] = $catId;
		}
		if(!empty($brands)) {
			$filter['brandId'] = array('in', $brands);
		}
		if($catId3 > 0) {
			$filter['g.goodsCatId3'] = $catId3;
		}

		$keywords = I('keywords', '-');
		if($keywords != '-') {
			$keywordsArray = preg_split('/[\s,]+/', I('keywords'));
			$likeArray = array();
			foreach($keywordsArray as $key) {
				array_push($likeArray, '%'.$key.'%');
			}
			$filter['goodsName'] = array('like', $likeArray, 'or');
		}
		
		$shopId = (int)I('shopId', 0);
		if($shopId > 0) {
			$filter['g.shopId'] = $shopId;
		}
		
		$shopCatId = intval(I('shopCatId', 0));
		if($shopCatId > 0) {
			$filter['g.shopCatId1'] = $shopCatId;
		}
		
		$join = 'g inner join __SHOPS__ s on g.shopId = s.shopId';
		$leftJoin1 = 'left join __MALL_ACTIVITYGOODS__ mag on mag.goodsId = g.goodsId';
		$leftJoin2 = 'left join __MALL_ACTIVITYM__ mam on mag.mactmid = mam.mactmid';

		$order = 'saleCount desc, isBest desc, isHot desc, goodsId desc';
		
		$sortType = (int)I('sortType', 0);
		
		switch($sortType) {
			case 1:
				$order = 'isBest desc, isHot desc, goodsId desc';
				break;
			case 2:
				break;
			case 3:
				$order = 'shopPrice asc,'.$order;
				break;
			case 4:
				$order = 'shopPrice desc,'.$order;
				break;
			case 101:
				$order = 'shopPrice desc,'.$order;
				break;
		}
		
		$list = $this->field($field)->join($join)
			->join($leftJoin1)
			->join($leftJoin2)
			->where($filter)->order($order)->page($pageNo, $pageSize)->select();
//		echo $this->getLastSql();
		return $list;
	}
	
	public function countTop() {
		$catId = (int)I('catId');
		$catId3 = (int)I('catId3', 0);
		$brands = I('brands');
		
		$filter = array(
			'shopId'			=> array('gt', 0),
			'isSale'			=> 1,
			'goodsFlag'		=> 1,
			'goodsCatId1'	=> $catId
		);
		
		
		if(!empty($brands)) {
			$filter['brandId'] = array('in', $brands);
		}
		if($catId3 > 0) {
			$filter['goodsCatId3'] = $catId3;
		}
		
		$count = $this->where($filter)->count();
//		echo $this->getLastSql();
		return $count;
	}
};
?>