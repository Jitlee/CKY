<?php
namespace Api\Action;
/**
 * ============================================================================
 * 粗卡云:
  
 * 联系方式:
 * ============================================================================
 * 首页（默认）控制器
 */

class AdsAction extends BaseAction {
	
	public function _empty($type = 0) {
		$this->lst($type);
	}
	
	public function lst($type = 0) {
		$m = D('Ads');
		$ads = $m->get($type);
		$this->json($ads);
	}
}
?>