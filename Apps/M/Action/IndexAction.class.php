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
class IndexAction extends Controller {
	 
		
	public function index() {
		Header("HTTP/1.1 301 Moved Permanently");
     	Header("Location:/index.php/Mobile/Index/index");
		
//		$user_agent = $_SERVER['HTTP_USER_AGENT'];
//		if (strpos($user_agent, 'MicroMessenger') >0) {
//			try_login();
//		}
//		/****分享与定位***/
//		$wxm= new WxUserInfo();
//		$signPackage=$wxm->getSignPackage();			 
//		$this->assign('signPackage', $signPackage);
//		/****地图apk*******/
//		$m = D('Home/System');
//		$GLOBALS['CONFIG'] = $m->loadConfigs();
//		$this->assign('CONF',$GLOBALS['CONFIG']);	
//		
//		
//		$this->assign('title', "粗卡");
//		$this->assign('tabid', 'home');
//		
//		$addb = D('ads');
//		$ads = $addb->queryByType(-1);
//		$this->assign('ads', $ads); 
//		 
//		$this->display();
	}
	public function indext() {

		/****分享与定位***/
		$wxm= new WxUserInfo();
		$signPackage=$wxm->getSignPackage();			 
		$this->assign('signPackage', $signPackage);
		/****地图apk*******/
		$m = D('Home/System');
		$GLOBALS['CONFIG'] = $m->loadConfigs();
		$this->assign('CONF',$GLOBALS['CONFIG']);	
		
		
		$this->assign('title', "粗卡");
		$this->assign('tabid', 'home');
		
		$addb = D('ads');
		$ads = $addb->queryByType(-1);
		$this->assign('ads', $ads); 
		 
		$this->display();
	}
	public function guess() {
		$m = D('M/Goods');
		$list = $m->guess();
		$this->ajaxReturn($list, 'JSON');
	}
}