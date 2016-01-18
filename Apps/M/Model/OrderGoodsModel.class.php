<?php
namespace M\Model;
/**
 * ============================================================================
 * 粗卡云:
  
 * 联系方式:
 * ============================================================================
 * 订单服务类
 */
class OrderGoodsModel extends BaseModel {
	
	/**
	 * 获取订单商品信息
	 */
	public function goods($obj){	
		$orderId = $obj["orderId"];
		$map = array('orderId' => $orderId);
		$field = 'g.*, cky_order_goods.goodsNums as ogoodsNums,cky_order_goods.goodsPrice as ogoodsPrice';
		return $this->field($field)
			->join('__GOODS__ g on g.goodsId = __ORDER_GOODS__.goodsId')
			->where($map)->select();
	}
	
	
	
}