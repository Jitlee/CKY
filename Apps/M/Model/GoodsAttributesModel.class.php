<?php
 namespace M\Model;
/**
 * ============================================================================
 * 粗卡云:
  
 * 联系方式:
 * ============================================================================
 * 商品属性服务类
 */
class GoodsAttributesModel extends BaseModel {
	public function reduceStock($attributeId, $count) {
		$data = array(
			'attrStock'	 	=> array('exp', '`attrStock` - '.$count)
		);
		$map = array(
			'id'				=> $attributeId
		);
		return $this->where($map)->save($data);
	}
};
?>