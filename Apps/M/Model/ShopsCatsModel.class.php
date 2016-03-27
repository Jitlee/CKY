<?php
 namespace M\Model;
/**
 * ============================================================================
 * 粗卡云:
  
 * 联系方式:
 * ============================================================================
 * 商家分类服务类
 */
class ShopsCatsModel extends BaseModel {
	public function query() {
		$shopId = I('shopId');
		$map = array(
			'shopId'		=> $shopId,
			'parentId'		=> 0,
			'isShow'		=> 1,
			'catFlag'		=> 1
		);
		return $this->where($map)->select();
	}
};
?>