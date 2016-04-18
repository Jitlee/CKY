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
	public function query($goodsId = 0) {
		$goodsId = $goodsId > 0 ? $goodsId : I('id');
		$map = array('goodsId'	=> $goodsId);
		return $this->where($map)->select();
	}
};
?>