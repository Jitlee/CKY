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
	
	public function records() {
		$goodsId = I("goodsId");
		 
		$pageSize = 20;
		$pageNo = intval(I('pageNo', 1));
		$map = "og.goodsId='$goodsId'";
		return $this->where($map)->field('o.createTime, count(og.id) as count,INSERT(userName,ROUND(CHAR_LENGTH(o.userName) / 2),ROUND(CHAR_LENGTH(o.userName) / 4),\'****\') userName')
			->join('og INNER JOIN __ORDERS__ o on og.orderId = o.orderId')->having('count(og.id) > 0')->page($pageNo, $pageSize)->select();
	}
	
}