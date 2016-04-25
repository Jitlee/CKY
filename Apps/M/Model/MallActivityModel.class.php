<?php
 namespace M\Model;
/**
 * ============================================================================
 * 粗卡云:
  
 * 联系方式:
 * ============================================================================
 * 商城活动类
 */
class MallActivityModel extends BaseModel {
    public function queryTop6() {
    		return $this->field("mactid, adpath")->where('now() between `starttime` and `endtime`')->order('sort desc, createTime')->page(1, 6)->select();
    }
};
?>