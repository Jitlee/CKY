<?php
 namespace M\Model;
/**
 * ============================================================================
 * 粗卡云:
  
 * 联系方式:
 * ============================================================================
 * 商品图片服务类
 */
class GoodsGallerysModel extends BaseModel {
	public function lst($goodsId = 0) {
		$goodsId = $goodsId > 0 ? $goodsId : I('id');
		$map = array('goodsId'	=> $goodsId);
		return $this->where($map)->select();
	}
	
	public function lstByMiaoshaId($miaoshaId) {
		$filter = "exists(select 0 from cky_goods g where g.goodsId= cky_goods_gallerys.goodsId and g.miaoshaId='$miaoshaId')";
		return $this->where($filter)->select();
	}
};
?>