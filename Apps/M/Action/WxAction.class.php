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
 	/*JsApi相关接口参数*/
 	public function getsharekey() {
		$wxm= new WxUserInfo();
		$signPackage=$wxm->getSignPackage();
		$this->ajaxReturn($signPackage, 'JSON');
	} 
	
	public function SendOrderNotifyToShops($orderid) {
			$orderid="285";			 
			$wxm= new WxNotify();
			$result=$wxm->SendOrderNotifyToShops($orderid);
			echo $result;
	}
}
