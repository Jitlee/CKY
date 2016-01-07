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
		$pageSize = 10;
		$pageNo = intval(I('pageNo', 1));
		$map = array('shopId'	=> $shopId);
		return $this->field('goodsId, goodsSn, goodsName, goodsThums, shopPrice')
			->where($map)->order('createTime desc')->page($pageNo, $pageSize)->select();
	}
	
	public function detail() {
		$goodsId = I('id');
		return $this->field('goodsId, goodsSn, goodsName, goodsImg, shopPrice, saleCount, goodsDesc')
			->find($goodsId);
	}
	
	public function gallerys() {
		$goodsId = I('id');
		$db = M('goods_gallerys');
		$map = array('goodsId'	=> $goodsId);
		return $db->where($map)->select();
	}
};
?>