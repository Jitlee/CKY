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
	public function queryPage($pageNo = 1, $pageSize = 10) {
		$start = ($pageNo - 1) * $pageSize;
		$sql = 'select g.goodsId, goodsSn, goodsName, goodsThums, goodsStock, shopPrice, goodsUnit,g.goodsSpec,
			 gg.groupGoodsId, gg.groupPrice, gg.groupNumbers, gg.groupPreNumbers,
			 DATE_FORMAT(gg.groupStartTime, \'%Y-%m-%d\') AS groupStartTime, DATE_FORMAT(gg.groupEndTime, \'%Y-%m-%d\') AS groupEndTime 
			 from __GOODS__ g INNER JOIN __GOODS_GROUP__ gg on g.goodsId = gg.goodsId
			 left JOIN __SHOPS__ s on g.shopId = s.shopId
			 where now() between gg.groupStartTime and gg.groupEndTime
			 order by gg.createTime desc limit '.$start.','.$pageSize;
		$data = $this->query($sql);
//		return $this->getLastSql();
		return $data;
	}
}
?>