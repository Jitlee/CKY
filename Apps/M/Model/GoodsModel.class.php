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
		$pageSize = 12;
		$pageNo = intval(I('pageNo', 1));
		$map = array('shopId'	=> $shopId);
		
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
		
		return $this->field('goodsId, goodsSn, goodsName, goodsThums, shopPrice, goodsUnit, saleCount, goodsSpec')
			->where($map)->order($order)->page($pageNo, $pageSize)->select();
	}
	
	public function detail($queryType = 0) {
		$goodsId = I('id');
		$field = 'goodsId, goodsSn, goodsName, shopCatId1, goodsImg, goodsThums, shopPrice, saleCount';
		switch($queryType) {
			case 0:
				$field .= ',goodsDesc';
				break;
			case 1:
				$filed .= ',goodsSpec';
		}
		return $this->field($field)->find($goodsId);
	}
};
?>