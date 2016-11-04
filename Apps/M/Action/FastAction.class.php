<?php
namespace M\Action;
/**
 * ============================================================================
 * 粗卡云:
  
 * 联系方式:
 * ============================================================================
 * 快餐 控制器
 */
use Think\Controller;
class FastAction extends BaseAction {
	public function page() {
		$m = D('M/Shops');
		$list = $m->fast();
//		echo $m->getLastSql();
		 
		$this->ajaxReturn($list, 'JSON');
	}
	
	public function index() {
		$user_agent = $_SERVER['HTTP_USER_AGENT'];
		if (strpos($user_agent, 'MicroMessenger') >0) {
			try_login();
		}
		
		$this->assign('title', "本地商家");
//		$this->assign('tabid', 'shops');
		
		/****分享与定位***/
		$wxm= new WxUserInfo();
		$signPackage=$wxm->getSignPackage();			 
		$this->assign('signPackage', $signPackage);
		
		$this->display();
	}
	
	// 商家详情
	public function detail() {		
		$m = D('M/Shops');
		$data = $m->detail();
//		echo $m->getLastSql();
		$data['shopDesc'] = htmlspecialchars_decode(html_entity_decode($data['shopDesc']));
		$this->assign('data', $data);
		$this->assign('title', $data['shopName']);
		$this->display();
	}
	
	public function cats() {
		$m = D('M/ShopsCats');
		$list = $m->query();
		$this->ajaxReturn($list, "JSON");
	}
}