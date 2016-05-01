<?php
namespace M\Action;
/**
 * ============================================================================
 * 粗卡云:
  
 * 联系方式:
 * ============================================================================
 * 首页（默认）控制器
 */
use Think\Controller;
class WxAction extends Controller {
 	public function getsharekey() {
		$wxm= new WxUserInfo();
		$signPackage=$wxm->getSignPackage();
		$this->ajaxReturn($signPackage, 'JSON');
	}
}
