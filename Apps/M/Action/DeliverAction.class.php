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
		$user_agent = $_SERVER['HTTP_USER_AGENT'];
		if (strpos($user_agent, 'MicroMessenger') >0) {
			try_login();
		}
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
	public function shops() { 
		$user_agent = $_SERVER['HTTP_USER_AGENT'];
		if (strpos($user_agent, 'MicroMessenger') >0) {
			try_login();
		}
	 	$shopId=I("shopid");

	 	$m = D('M/Shops');
		$data = $m->detail($shopId);

		$this->assign('title', $data["shopName"].'-商家');
		$this->assign('tabid', 'home');
		$this->assign('shopid', $shopId); 
		$this->display();
	}
	public function shopspage() { 		 
		$shopId=I("shopId");
		//查询商家
		$m = D('M/ShopsSub');
    	$list=$m->queryByList($shopId);
		$this->ajaxReturn($list, 'JSON');
	}
	
	public function shopsitem() {
		
		$db = D('M/ShopsSub');
		$ShopsSubObj = $db->getitem(); 
		
		$shopId=I("shopid");
	 	$m = D('M/Shops');
		$data = $m->detail($shopId);
		$data['shopName']=$ShopsSubObj['shopName'];
		
		$data['shopDesc'] = htmlspecialchars_decode(html_entity_decode($data['shopDesc']));
		$this->assign('data', $data);
		$this->assign('title', $data['shopName']);
		$this->display();
	}
	
	public function shopsitemdetail() {
		$m = D('M/Goods');
		$data = $m->detail();
		$data['goodsDesc'] = htmlspecialchars_decode(html_entity_decode($data['goodsDesc']));
		$this->assign('data', $data);
		$this->assign('title', $data['goodsName']);
		//评价
		$goodsId = I('id');
		$mAppraises = D('M/GoodsAppraises');
		$Appraises=$mAppraises->getGoodsAppraises($goodsId);
		$this->assign('appraise', $Appraises);
		
		$this->display();
	}
	public function itemptg() { 		
		$db = D('M/ShopsSub');
		$list = $db->pageByShopsId();
		$this->ajaxReturn($list, 'JSON');
	}
	
	/*查询子商家，商品分类 **/
	public function cats() {	
		$m = D('M/ShopsSub');
    	$list=$m->cats();
		$this->ajaxReturn($list, 'JSON');
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
		$this->assign('shop', $data);
		
		$this->assign('shopId', $shopId);
		$this->assign('title', '速达农村配送-'.$data["shopName"]);
		$this->display();
	}
	
	public function ptg() { 		
		$db = D('Goods');
		$list = $db->pageByShopsId();
		$this->ajaxReturn($list, 'JSON');
	}
	
	public function milkdetail() {
		// 获取广告
		$addb = D('ads');		
		$shopId=74;
		$ads = $addb->queryByType(-101);	// 农村配送-鲜奶
		$this->assign('ads', $ads);  
	 		 
		/****分享与定位***/
		$wxm= new WxUserInfo();
		$signPackage=$wxm->getSignPackage();			 
		$this->assign('signPackage', $signPackage);
		
		$m = D('M/Shops');
		$data = $m->detail($shopId);
		$this->assign('shop', $data);
		
		$this->assign('shopId', $shopId);
		$this->assign('title', '速达-鲜奶配送');
		$this->display();
	}
	
	public function milkptg() { 		
		$db = D('Goods');
		$list = $db->pageByShopsId();
		$this->ajaxReturn($list, 'JSON');
	}
	
}