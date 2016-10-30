<?php
namespace Api\Model;
/**
 * ============================================================================
 * 粗卡云:
  
 * 联系方式:
 * ============================================================================
 * 广告服务类
 */
use Think\Model;
class AdsModel extends Model {
	/**
	 * 获取广告
	 */
	public function get($type = 0){
		return $this->field('adId, adFile,adFileThumb,adURL')->where('SYSDATE() BETWEEN adStartDate AND adEndDate AND adPositionId='.$type)->order('adSort')->select();
	}
}
?>