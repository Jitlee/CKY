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
class DeliverAction extends Controller {
	 
		
	public function index() { 
		 
		/****分享与定位***/
		$wxm= new WxUserInfo();
		$signPackage=$wxm->getSignPackage();			 
		$this->assign('signPackage', $signPackage);
	 
		
		$this->assign('title', "粗卡");
		$this->assign('tabid', 'home');
		
		$addb = D('ads');
		$ads = $addb->queryByType(-10);
		$this->assign('ads', $ads); 
		 
		$this->display();
	}
	 
	public function detail() {
		// 获取广告
		$addb = D('ads');		
		$shopId=I("shopid",0);
		$ads = $addb->queryByShopid($shopId);
		$this->assign('ads', $ads);  
	 
		 
		/****分享与定位***/
		$wxm= new WxUserInfo();
		$signPackage=$wxm->getSignPackage();			 
		$this->assign('signPackage', $signPackage);
		
		$m = D('M/Shops');
		$data = $m->detail($shopId);
		
		$this->assign('shopId', $shopId);
		$this->assign('title', '速达农村配送-'.$data["shopName"]);
		$this->display();
	}
	
	public function ptg() { 		
		$db = D('Goods');
		$list = $db->pageByShopsId();
		$this->ajaxReturn($list, 'JSON');
	}
	
	
	
}