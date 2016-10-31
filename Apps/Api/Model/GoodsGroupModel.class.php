<?php
namespace Api\Model;
/**
 * ============================================================================
 * 粗卡云:
  
 * 联系方式:
 * ============================================================================
 * 拼团服务类
 */
use Think\Model;
class GoodsGroupModel extends Model {
	/**
	 * 获取广告
	 */
	public function newest(){
		$sql = "select g.goodsId, goodsSn, goodsName, goodsThums, goodsStock, shopPrice, goodsUnit,
			 gg.groupGoodsId, gg.groupPrice, gg.groupNumbers, gg.groupPreNumbers,
			 DATE_FORMAT(gg.groupStartTime, '%Y-%m-%d') AS groupStartTime, DATE_FORMAT(gg.groupEndTime, '%Y-%m-%d') AS groupEndTime 
			 from __GOODS__ g INNER JOIN __GOODS_GROUP__ gg on g.goodsId = gg.goodsId
			 INNER JOIN __SHOPS__ s on g.shopId = s.shopId
		 	$like $cat1 $cat2 order by gg.createTime desc limit 10";
		$data = $this->query($sql);
//		return $this->getLastSql();
		return $data;
	}
}
?>