<?php
 namespace M\Model;
/**
 * ============================================================================
 * 粗卡云:
  
 * 联系方式:
 * ============================================================================
 * 广告服务类
 */
class AdsModel extends BaseModel {
    public function queryByType($type) {
		return $this->field('adId, adFile,adFileThumb,adURL')->where('SYSDATE() BETWEEN adStartDate AND adEndDate AND adPositionId='.$type)->order('adSort')->select();
    }
	public function queryByShopid($shopid) {
		return $this->field('adId, adFile,adFileThumb,adURL')->where('SYSDATE() BETWEEN adStartDate AND adEndDate AND shopId='.$shopid)->order('adSort')->select();
    }
};
?>