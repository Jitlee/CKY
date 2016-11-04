<?php
namespace M\Action;
/**
 * ============================================================================
 * 粗卡云:
 * 
#                       _oo0oo_
#                      o8888888o
#                      88" . "88
#                      (| -_- |)
#                      0\  =  /0
#                    ___/`---'\___
#                  .' \\|     |// '.
#                 / \\|||  :  |||// \
#                / _||||| -:- |||||- \
#               |   | \\\  -  /// |   |
#               | \_|  ''\---/''  |_/ |
#               \  .-\__  '-'  ___/-. /
#             ___'. .'  /--.--\  `. .'___
#          ."" '<  `.___\_<|>_/___.' >' "".
#         | | :  `- \`.;`\ _ /`;.`/ - ` : | |
#         \  \ `_.   \_ __\ /__ _/   .-` /  /
#     =====`-.____`.___ \_____/___.-`___.-'=====
#                       `=---='
#
#
#     ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
#
#               佛祖保佑         永无BUG
  
 * 联系方式:
 * ============================================================================
 * 商家 控制器
 * 作者 万品佳
 */
use Think\Controller;
class ShopsAction extends BaseAction {
	public function index() {
		$user_agent = $_SERVER['HTTP_USER_AGENT'];
		if (strpos($user_agent, 'MicroMessenger') >0) {
			try_login();
		}
		$this->assign('title', "商家");
		$this->assign('tabid', 'shops');
		
		$catid=I("catid",0);
		$catid=$catid.'';
		 
		/****分享与定位***/
		$wxm= new WxUserInfo();
		$signPackage=$wxm->getSignPackage();			 
		$this->assign('signPackage', $signPackage);
		$this->assign('catid', $catid);
		
		$this->display();
	}
	
	public function page() {
		$m = D('M/Shops');
		$list = $m->shops();
//		echo $m->getLastSql();
		$this->ajaxReturn($list, 'JSON');
	}
	
	// 商家详情
	public function detail() {		
		$m = D('M/Shops');
		$data = $m->detail();
//		echo $m->getLastSql();
		$data['shopDesc'] = htmlspecialchars_decode(html_entity_decode($data['shopDesc']));
		$this->assign('data', $data);
		$this->assign('title', $data['shopName']);
		
		/****分享与定位***/
		$wxm= new WxUserInfo();
		$signPackage=$wxm->getSignPackage();			 
		$this->assign('signPackage', $signPackage);
		
		$shopDesc=substr($data['shopDesc'],0,50);
		$shopDesc=str_replace("'","‘",$shopDesc);
		$shopDesc=str_replace("\r\n","",$shopDesc);
		$shopDesc=str_replace("\n","",$shopDesc);
		$this->assign('shopDesc', $shopDesc);
		
		$catId = I('catId', 0);
		if($catId == 1) {
			$this->display("Fast/detail");
		} else {			
			$this->display();
		}
	}
	
	public function map() {
		$wxm= new WxUserInfo();
		$signPackage=$wxm->getSignPackage();			 
		$this->assign('signPackage', $signPackage);
		/****地图apk*******/
		$m = D('Home/System');
		$GLOBALS['CONFIG'] = $m->loadConfigs();
		$this->assign('CONF',$GLOBALS['CONFIG']);
		
		$this->assign('title', I('shopName'));
		$this->display();
	}
	
	// 商家跳转
	public function rdirect() {
		$m = D('M/Shops');
		$shop = $m->detail();
		$this->assign('data', $shop);
		
		$m = D('M/GoodsCats');
		$cats = $m->queryByParentId(0, I('id', 0));
		$this->assign('cats', $cats);
		
//		echo $m->getLastSql();
//		echo dump($cats);

		if(count($cats) == 1) {
			$catId = (int)$cats[0]['catId'];
			if($catId == 336) {
				$this->redirect('Shops/detail', array('id' => I('id', 0)));
			} else if($catId == 1) {
				$this->redirect('Fast/detail', array('id' => I('id', 0)));
			}
			return;
		}
		
		$this->assign('title', $shop['shopName']);
		$this->display('redirect');
	}
	
	public function gallery() {
		$m = D('M/Shops');
		$configs = $m->configs();
		$configs["shopThumbs"] = explode("#@#", str_replace(".", "_thumb.", $configs["shopAds"]));
		$this->assign('shopThumbs', $configs["shopThumbs"]);
		
		$this->assign('title', $configs['shopName']);
		$this->display();
	}
	
	public function activity() {
		$this->display();
	}
	
	public function pa() {
		$m = D('Activity');
		$list = $m->shopActivities();
		$this->ajaxReturn($list, "json");
	}
	
	public function search() {
		$this->display();
	}
	
	public function s() {
		$m = D("Shops");
		$list = $m->search();
		$this->ajaxReturn($list, "json");
	}
	
//	public function s() {
//		$m = D("Goods");
//		$list = $m->searchShopsGoods();
//		$this->ajaxReturn($list, "json");
//	}
}