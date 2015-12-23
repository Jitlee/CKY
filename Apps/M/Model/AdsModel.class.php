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
		return $this->field('adId, adFile,adURL')->where('SYSDATE() BETWEEN adStartDate AND adEndDate')->order('adSort')->select();
    }
};
?>