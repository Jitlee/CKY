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
		return $this->field('adId,adName, adFile,adFileThumb,adURL')->where('SYSDATE() BETWEEN adStartDate AND adEndDate AND adPositionId='.$type)->order('adSort')->select();
    }
    
	public function queryByShopid($shopid) {
		return $this->field('adId,adName, adFile,adFileThumb,adURL')->where('SYSDATE() BETWEEN adStartDate AND adEndDate AND shopId='.$shopid)->order('adSort')->select();
    }
    /*资讯广告查询     
     * */
    public function queryArticlesad() {
		return $this->field('adId,adName, adFile,adFileThumb,adURL,adPositionId')
		->where('adPositionId >=-69 and adPositionId <=-60  and SYSDATE() BETWEEN adStartDate AND adEndDate')->order('adSort')->select();
    }
    
};
?>